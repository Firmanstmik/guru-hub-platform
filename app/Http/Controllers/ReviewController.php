<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Course;
use Illuminate\Http\Request;

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

    /**
     * Moderasi Konten: Menghapus ulasan yang melanggar ketentuan/kebijakan platform
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->back()->with('success', 'Ulasan berhasil dimoderasi (dihapus dari sistem) karena melanggar kebijakan platform.');
    }
}