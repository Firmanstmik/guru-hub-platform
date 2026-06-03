<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class ReviewController extends Controller
{
    /**
     * INDEX (Admin / Moderasi Ulasan)
     */
    public function index(Request $request)
    {
        try {
            // Eager loading relasi siswa, kelas, dan guru pengajar terkait
            $query = Review::with(['student', 'course', 'teacher']);

            // Filter berdasarkan batasan nilai rating (bintang 1 - 5)
            if ($request->has('rating') && $request->rating != '') {
                $query->where('rating', $request->rating);
            }

            // Filter berdasarkan program kelas spesifik
            if ($request->has('course_id') && $request->course_id != '') {
                $query->where('course_id', $request->course_id);
            }

            // Fitur Pencarian teks ulasan atau nama siswa
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where('comment', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            }

            $reviews = $query->latest()->paginate(10)->withQueryString();

            // Mengambil daftar kelas aktif untuk pilihan filter
            $courses = Course::where('status', 'published')->orderBy('title')->get();

            return view('admin.review', compact('reviews', 'courses'));
        } catch (Exception $e) {
            Log::error('Gagal memuat index ulasan/review: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data ulasan.');
        }
    }

    /**
     * DESTROY (Moderasi / Hapus Ulasan)
     */
    public function destroy(Review $review)
    {
        try {
            $review->delete();

            return redirect()->back()->with('success', 'Ulasan berhasil dimoderasi (dihapus dari sistem) karena melanggar kebijakan platform.');
        } catch (Exception $e) {
            Log::error('Gagal memoderasi ulasan ID ' . $review->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus ulasan karena terjadi kendala pada database.');
        }
    }

    /**
     * CREATE FORM (Siswa)
     */
    public function create($course_id)
    {
        try {
            $userId = Auth::id();

            // VALIDASI PROTEKSI: Pastikan siswa benar-benar mengambil kelas ini dan statusnya sudah sukses/selesai
            $booking = Booking::with(['course.teacher'])
                ->where('student_id', $userId)
                ->where('course_id', $course_id)
                ->whereIn('status', ['success', 'completed'])
                ->first();

            if (!$booking) {
                return redirect()->back()->with('error', 'Akses ditolak. Anda belum terdaftar atau belum menyelesaikan kelas ini.');
            }

            // CEK DUPLIKASI: Jika siswa sudah pernah memberi ulasan di kelas ini, cegah pengisian ulang
            $alreadyReviewed = Review::where('student_id', $userId)
                ->where('course_id', $course_id)
                ->exists();

            if ($alreadyReviewed) {
                return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk program kelas ini sebelumnya.');
            }

            $course = $booking->course;

            return view('student.review-form', compact('course'));
        } catch (Exception $e) {
            Log::error('Gagal memuat formulir ulasan siswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat menyiapkan halaman ulasan.');
        }
    }

    /**
     * STORE (Simpan Ulasan Siswa)
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        // 1. Validasi input form dengan custom pesan Bahasa Indonesia
        $request->validate([
            'course_id'  => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:users,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'required|string|min:10|max:1000',
        ], [
            'course_id.required'  => 'ID kelas tidak valid.',
            'course_id.exists'    => 'Kelas tidak terdaftar di sistem.',
            'teacher_id.required' => 'ID pengajar tidak valid.',
            'teacher_id.exists'   => 'Pengajar tidak terdaftar di sistem.',
            'rating.required'     => 'Anda wajib memilih bintang penilaian.',
            'rating.integer'      => 'Format penilaian harus berupa angka.',
            'rating.min'          => 'Penilaian minimal adalah 1 bintang.',
            'rating.max'          => 'Penilaian maksimal adalah 5 bintang.',
            'comment.required'    => 'Kotak ulasan atau komentar wajib diisi.',
            'comment.min'         => 'Berikan ulasan yang edukatif dan membangun, minimal 10 karakter.',
            'comment.max'         => 'Ulasan terlalu panjang, maksimal 1000 karakter.',
        ]);

        try {
            // 2. PROTEKSI BACKEND: Cek bypass via tools external (Postman/Inspect)
            $hasAccess = Booking::where('student_id', $userId)
                ->where('course_id', $request->course_id)
                ->whereIn('status', ['success', 'completed'])
                ->exists();

            $alreadyReviewed = Review::where('student_id', $userId)
                ->where('course_id', $request->course_id)
                ->exists();

            if (!$hasAccess || $alreadyReviewed) {
                return redirect()->back()->withInput()->with('error', 'Tindakan tidak valid. Hak akses pengiriman ulasan ditolak.');
            }

            // 3. Simpan review ke database sesuai struktur Fillable Model Anda
            Review::create([
                'student_id' => $userId,
                'teacher_id' => $request->teacher_id,
                'course_id'  => $request->course_id,
                'rating'     => $request->rating,
                'comment'    => $request->comment,
            ]);

            return redirect()->intended('/my-courses')
                ->with('success', 'Terima kasih! Ulasan dan testimoni Anda berhasil disimpan.');
        } catch (Exception $e) {
            Log::error('Gagal menyimpan ulasan baru dari siswa: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal mengirim ulasan karena terjadi kendala internal pada sistem.');
        }
    }
}
