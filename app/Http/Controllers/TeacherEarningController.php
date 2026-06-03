<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class TeacherEarningController extends Controller
{
    /**
     * INDEX (Admin & Guru - Laporan Pendapatan Bagi Hasil)
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $teacherPercentage = 0.70; // Guru mendapatkan porsi bagi hasil 70% dari harga kelas

            // 1. Query Utama: Ambil dari tabel payments yang sudah disetujui (approved)
            // Lakukan Eager Loading ke relasi course, teacher, dan student agar performa cepat
            $query = Payment::with(['course.teacher', 'student'])
                ->where('status', 'approved');

            // 2. Proteksi Hak Akses: Menggunakan string role 'guru' sesuai standar proyek Anda
            if ($user->hasRole('guru')) {
                $query->whereHas('course', function ($q) use ($user) {
                    $q->where('teacher_id', $user->id);
                });
            }

            // 3. Fitur Filter Pencarian Nama Guru (Hanya berlaku untuk sisi Admin)
            if ($request->filled('search') && $user->hasRole('admin')) {
                $search = $request->search;
                $query->whereHas('course.teacher', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            }

            // 4. Fitur Filter Berdasarkan Kolom 'earning_status' (pending / paid)
            if ($request->filled('status')) {
                $query->where('earning_status', $request->status);
            }

            // Buat kloning query terpisah khusus untuk kalkulasi nominal total widget
            $pendingQuery = clone $query;
            $paidQuery = clone $query;

            // Hitung Total Pendapatan Tertunda (Pending) berdasarkan porsi bagi hasil guru (amount * 70%)
            $totalPending = $pendingQuery->where('earning_status', 'pending')->get()->sum(function ($payment) use ($teacherPercentage) {
                return $payment->amount * $teacherPercentage;
            });

            // Hitung Total Pendapatan Selesai Dicairkan (Paid) berdasarkan porsi bagi hasil guru (amount * 70%)
            $totalPaid = $paidQuery->where('earning_status', 'paid')->get()->sum(function ($payment) use ($teacherPercentage) {
                return $payment->amount * $teacherPercentage;
            });

            // 5. Eksekusi Pagination data riwayat untuk tabel utama
            $earnings = $query->latest()->paginate(10)->withQueryString();

            // 6. Pengembalian View Berdasarkan Peran Akses Spatie
            if ($user->hasRole('admin')) {
                return view('admin.earning', compact('earnings', 'totalPending', 'totalPaid', 'teacherPercentage'));
            } elseif ($user->hasRole('guru')) {
                return view('guru.pendapatan', compact('earnings', 'totalPending', 'totalPaid', 'teacherPercentage'));
            }

            // Fallback jika user tidak memiliki salah satu dari kedua role di atas
            abort(403, 'Anda tidak memiliki hak akses untuk halaman pendapatan ini.');

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
        // Validasi input dengan custom pesan Bahasa Indonesia
        $request->validate([
            'status' => 'required|in:paid,pending'
        ], [
            'status.required' => 'Status pencairan wajib ditentukan.',
            'status.in'       => 'Pilihan status pencairan komisi tidak valid.'
        ]);

        try {
            $payment = Payment::findOrFail($id);

            // Perbarui status bagi hasil pengajar
            $payment->update([
                'earning_status' => $request->status
            ]);

            return redirect()->back()->with('success', 'Status pencairan dana bagi hasil guru berhasil diperbarui!');

        } catch (Exception $e) {
            Log::error('Gagal memperbarui status pencairan bagi hasil Payment ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui status pencairan dana karena kendala internal database.');
        }
    }
}