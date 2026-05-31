<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(Request $request)
    {
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
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:booked,attended,absent,cancelled',
        ]);

        $booking->update($validated);

        // Opsional: Integrasi notifikasi WhatsApp ke Siswa/Guru bisa dipicu di sini

        return redirect()->back()->with('success', 'Status booking berhasil diperbarui!');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->back()->with('success', 'Data booking berhasil dihapus dari sistem!');
    }

    // untuk siswa
    public function create(Request $request)
    {
        // Ambil data siswa yang sedang login
        $student = Auth::user();

        // Ambil semua daftar kelas aktif untuk opsi dropdown manual
        $courses = Course::with('teacher')
            ->where('status', 'published')
            ->orderBy('title')
            ->get();

        return view('student.booking-form', compact('courses', 'student'));
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $student */
        $student = Auth::user();

        // 1. Validasi input dari formulir
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'note'      => 'nullable|string|max:500',
        ], [
            'course_id.required' => 'Anda wajib memilih program kelas.',
            'course_id.exists'   => 'Kelas yang Anda pilih tidak valid atau sudah tidak aktif.',
            'note.max'           => 'Catatan tambahan tidak boleh lebih dari 500 karakter.',
        ]);

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
        try {
            $booking = Booking::create([
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
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Mengembalikan pesan error database asli (SQL Error) jika penyimpanan gagal agar mudah di-debug
            return back()->withInput()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }
    public function showHistory()
    {
        $student = Auth::user();

        // Ambil riwayat transaksi milik siswa yang sedang login beserta relasi data kelas & metornya
        $bookings = Booking::with(['course.teacher', 'course.category'])
            ->where('student_id', $student->id)
            ->latest() // Mengurutkan dari transaksi terbaru (created_at)
            ->paginate(10); // Menggunakan pagination jika data sudah banyak

        return view('student.history-bookings', compact('bookings'));
    }
}
