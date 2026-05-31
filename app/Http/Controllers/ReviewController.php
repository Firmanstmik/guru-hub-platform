<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
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
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->back()->with('success', 'Ulasan berhasil dimoderasi (dihapus dari sistem) karena melanggar kebijakan platform.');
    }

    public function create($course_id)
    {
        $userId = Auth::id();

        // VALIDASI PROTEKSI: Pastikan siswa benar-benar mengambil kelas ini dan statusnya sudah sukses/selesai
        $booking = Booking::with(['course.teacher'])
            ->where('student_id', $userId)
            ->where('course_id', $course_id)
            ->whereIn('status', ['success', 'completed']) // Menggunakan data status dari ekosistem sebelumnya
            ->firstOrFail();

        // CEK DUPLIKASI: Jika siswa sudah pernah memberi ulasan di kelas ini, cegah pengisian ulang
        $alreadyReviewed = Review::where('student_id', $userId)
            ->where('course_id', $course_id)
            ->exists();

        if ($alreadyReviewed) {
            return redirect()->back()
                ->with('error', 'Anda sudah memberikan ulasan untuk program kelas ini sebelumnya.');
        }

        $course = $booking->course;

        return view('student.review-form', compact('course'));
    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        // 1. Validasi input form
        $request->validate([
            'course_id'  => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:users,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'required|string|min:10|max:1000',
        ], [
            'rating.required'  => 'Anda wajib memilih bintang penilaian.',
            'comment.required' => 'Kotak aspirasi ulasan wajib diisi.',
            'comment.min'      => 'Berikan ulasan yang edukatif, minimal 10 karakter.',
        ]);

        // 2. PROTEKSI BACKEND: Redundant check untuk mencegah bypass via tools postman/inspect
        $hasAccess = Booking::where('student_id', $userId)
            ->where('course_id', $request->course_id)
            ->whereIn('status', ['success', 'completed'])
            ->exists();

        $alreadyReviewed = Review::where('student_id', $userId)
            ->where('course_id', $request->course_id)
            ->exists();

        if (!$hasAccess || $alreadyReviewed) {
            return redirect()->back()
                ->with('error', 'Tindakan tidak valid. Akses ulasan ditolak.');
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
    }
}