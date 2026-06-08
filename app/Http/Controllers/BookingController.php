<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CompanyAccount;
use App\Models\Course;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class BookingController extends Controller
{
    /**
     * INDEX (Admin)
     */
    public function index(Request $request)
    {
        try {
            // Query dasar dengan Eager Loading bertingkat agar hemat database query
            $query = Booking::with([
                'student',
                'schedule.course.teacher'
            ]);

            // Fitur Filter Status (Jika admin ingin menyaring data tertentu)
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            $bookings = $query->latest()->paginate(10)->withQueryString();

            return view('admin.bookings', compact('bookings'));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman index booking: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data booking.');
        }
    }

    /**
     * UPDATE STATUS (Admin)
     */
    public function update(Request $request, Booking $booking)
    {
        // Validasi dengan custom pesan Bahasa Indonesia
        $validated = $request->validate([
            'status' => 'required|in:pending,success,failed,expired,cancelled',
        ], [
            'status.required' => 'Status booking wajib ditentukan.',
            'status.in'       => 'Pilihan status booking tidak sesuai dengan ketentuan sistem.'
        ]);

        try {
            $booking->update($validated);

            // Opsional: Integrasi notifikasi WhatsApp ke Siswa/Guru bisa dipicu di sini

            return redirect()->back()->with('success', 'Status booking berhasil diperbarui!');
        } catch (Exception $e) {
            Log::error('Gagal memperbarui status booking ID ' . $booking->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui status booking karena kendala sistem.');
        }
    }

    /**
     * DESTROY (Admin)
     */
    public function destroy(Booking $booking)
    {
        try {
            $booking->delete();
            return redirect()->back()->with('success', 'Data booking berhasil dihapus dari sistem!');
        } catch (Exception $e) {
            Log::error('Gagal menghapus data booking ID ' . $booking->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data booking karena kendala database.');
        }
    }

    /**
     * CREATE FORM (Siswa)
     */
    public function create(Request $request)
    {
        try {
            // Ambil data siswa yang sedang login
            $student = Auth::user();

            // Ambil semua daftar kelas aktif untuk opsi dropdown manual
            $courses = Course::with('teacher')
                ->where('status', 'published')
                ->orderBy('title')
                ->get();
            $banks = CompanyAccount::where('is_active', true)->get();

            return view('student.booking-form', compact('courses', 'student', 'banks'));
        } catch (Exception $e) {
            Log::error('Gagal memuat formulir booking siswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat menyiapkan formulir pendaftaran.');
        }
    }

    /**
     * STORE TRANSAKSI (Siswa)
     */
    public function store(Request $request)
    {
        $student = Auth::user();

        // 1. Validasi input dari formulir dengan custom pesan Bahasa Indonesia
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'note'      => 'nullable|string|max:500',
        ], [
            'course_id.required' => 'Anda wajib memilih program kelas.',
            'course_id.exists'   => 'Kelas yang Anda pilih tidak valid atau sudah tidak aktif.',
            'note.max'           => 'Catatan tambahan tidak boleh lebih dari 500 karakter.',
        ]);

        try {
            // 2. Ambil data kelas
            $course = Course::findOrFail($request->course_id);

            // 3. PROTEKSI: Cek tagihan tertunda
            $isAlreadyBooked = Booking::where('student_id', $student->id)
                ->where('course_id', $course->id)
                ->where('status', 'pending')
                ->exists();

            if ($isAlreadyBooked) {
                return back()->withInput()->with('error', 'Anda memiliki tagihan aktif untuk kelas ini yang belum dibayar. Silakan cek riwayat transaksi Anda.');
            }

            // 4. GENERATE NOMOR TRANSAKSI UNIK
            $transactionCode = 'BKG-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            // 5. Simpan ke Database dengan Database Transaction
            DB::beginTransaction();

            Booking::create([
                'transaction_code' => $transactionCode,
                'student_id'       => $student->id,
                'course_id'        => $course->id,
                'total_amount'     => $course->price,
                'status'           => 'pending',
                'note'             => $request->note,
            ]);

            DB::commit();

            // 6. REDIRECT: Menuju halaman daftar riwayat pesanan siswa
            return redirect()->intended('/history-bookings')
                ->with('success', 'Booking berhasil dibuat dengan kode ' . $transactionCode . '. Silakan cek detail atau konfirmasi pembayaran Anda.');
        } catch (Exception $e) {
            DB::rollBack();

            // Amankan dari Information Disclosure dengan mencatat pesan error SQL ke log internal
            Log::error('Gagal memproses pendaftaran kelas oleh siswa: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Gagal memproses pendaftaran kelas karena terjadi kendala internal pada sistem.');
        }
    }

    /**
     * RIWAYAT BOOKING (Siswa)
     */
    public function showHistory()
    {
        try {
            $bookings = Booking::with([
                'course.category',
                'course.teacher',
                'payment' => function ($query) {
                    $query->where('student_id', Auth::id());
                }
            ])
                ->where('student_id', Auth::id())
                ->latest()
                ->paginate(10); // jika data sudah banyak
            // dd($bookings);
            return view('student.history-bookings', compact('bookings')); // Variabel $payments sudah tidak diperlukan lagi
        } catch (Exception $e) {
            Log::error('Gagal memuat riwayat booking siswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat halaman riwayat pendaftaran.');
        }
    }
}
