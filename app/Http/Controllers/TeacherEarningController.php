<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\TeacherEarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;

class TeacherEarningController extends Controller
{
    /**
     * INDEX (Admin & Guru - Laporan Pendapatan Bagi Hasil)
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $teacherPercentage = 0.70;
            if ($user->hasRole('guru')) {
                $earnings = DB::table('teacher_earnings')
                    ->join('payments', 'teacher_earnings.payment_id', '=', 'payments.id')
                    ->join('courses', 'payments.course_id', '=', 'courses.id')
                    ->join('users as students', 'payments.student_id', '=', 'students.id')
                    ->where('teacher_earnings.teacher_id', $user->id)
                    ->select(
                        'payments.id as payment_id',
                        'courses.title as course_title',
                        'students.name as student_name',
                        'payments.amount as gross_amount',
                        'payments.invoice_number',
                        'teacher_earnings.amount_earned',
                        'teacher_earnings.status as earning_status', // Berisi 'unpaid' atau 'withdrawn'
                        'teacher_earnings.created_at'
                    )
                    ->latest('teacher_earnings.created_at')
                    ->paginate(10)
                    ->withQueryString();

                // 2. Hitung Widget Saldo Sudah Ditransfer
                $totalPaid = DB::table('teacher_earnings')
                    ->where('teacher_id', $user->id)
                    ->where('status', 'withdrawn')
                    ->sum('amount_earned');

                // 3. Hitung Widget Saldo Belum Ditransfer
                $totalPending = DB::table('teacher_earnings')
                    ->where('teacher_id', $user->id)
                    ->where('status', 'unpaid')
                    ->sum('amount_earned');

                return view('guru.pendapatan', compact('earnings', 'totalPending', 'totalPaid', 'teacherPercentage'));
            }

            // ==========================================
            // ALUR KHUSUS ADMIN (Membaca Tabel payments untuk Verifikasi Manual)
            // ==========================================
            if ($user->hasRole('admin')) {
                // 1. Inisialisasi Query Utama untuk Admin membaca tabel murni teacher_earnings
                // Menggunakan relasi payment untuk filter pencarian dan filter status
                $query = DB::table('teacher_earnings')
                    ->join('payments', 'teacher_earnings.payment_id', '=', 'payments.id')
                    ->join('courses', 'payments.course_id', '=', 'courses.id')
                    ->join('users as teachers', 'teacher_earnings.teacher_id', '=', 'teachers.id')
                    ->join('users as students', 'payments.student_id', '=', 'students.id')
                    ->where('payments.status', 'approved'); // Hanya tampilkan transaksi yang sudah sah

                // 2. Filter Pencarian Nama Guru
                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->where('teachers.name', 'like', '%' . $search . '%');
                }

                // 3. Filter Berdasarkan Status Transfer Bagi Hasil ('unpaid' / 'withdrawn')
                if ($request->filled('status')) {
                    $query->where('teacher_earnings.status', $request->status);
                }

                // 4. Perhitungan Widget Angka Ringkasan yang Cepat dan Akurat dari Database
                $totalPending = (clone $query)->where('teacher_earnings.status', 'unpaid')->sum('teacher_earnings.amount_earned');
                $totalPaid    = (clone $query)->where('teacher_earnings.status', 'withdrawn')->sum('teacher_earnings.amount_earned');

                // 5. Ambil Data Pagination dengan Properti Flat Object agar Seragam dan Ringan
                $earnings = $query->select(
                    'payments.id as payment_id',
                    'payments.invoice_number',
                    'payments.amount as gross_amount',
                    'teachers.name as teacher_name',
                    'courses.title as course_title',
                    'students.name as student_name',
                    'teacher_earnings.amount_earned',
                    'teacher_earnings.status as earning_status', // Kita jadikan alias agar view blade tidak pecah
                    'teacher_earnings.created_at',
                    'teacher_earnings.updated_at'
                )
                    ->latest('teacher_earnings.created_at')
                    ->paginate(10)
                    ->withQueryString();

                return view('admin.earning', compact('earnings', 'totalPending', 'totalPaid', 'teacherPercentage'));
            }

            // Fallback jika ada user dengan role lain mencoba masuk
            abort(403, 'Anda tidak memiliki hak akses untuk melihat halaman pendapatan ini.');
        } catch (Exception $e) {
            Log::error('Gagal memuat data pendapatan bagi hasil pengajar: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat laporan pendapatan.');
        }
    }

    /**
     * UPDATE STATUS PENCAIRAN (Hanya Admin)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:withdrawn,unpaid'
        ]);
        try {
            TeacherEarning::where('payment_id', $id)
                ->update([
                    'status'     => $request->status,
                    'updated_at' => now()
                ]);

            return redirect()->back()->with('success', 'Status transfer bagi hasil guru berhasil diperbarui!');
        } catch (Exception $e) {
            Log::error('Gagal memperbarui pencairan Guru untuk Payment ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses perubahan status pencairan.');
        }
    }
}
