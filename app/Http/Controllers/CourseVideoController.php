<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CourseVideo;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseVideoController extends Controller
{
public function index(Request $request)
    {
        $user = Auth::user();

        // Eager loading kelas dan pengajarnya
        $query = CourseVideo::with(['course.teacher']);

        // PERBAIKAN UTAMA: Saring video berdasarkan peran (Role) Akun Guru yang masuk
        if (!$user->hasRole('admin')) {
            // Hanya ambil video yang kelasnya dimiliki oleh Guru yang sedang aktif login
            $query->whereHas('course', function ($q) use ($user) {
                $q->where('teacher_id', $user->id);
            });
        }

        // Filter berdasarkan kelas tertentu
        if ($request->has('course_id') && $request->course_id != '') {
            $query->where('course_id', $request->course_id);
        }

        // Filter berdasarkan tipe video (material atau recording)
        if ($request->has('video_type') && $request->video_type != '') {
            $query->where('video_type', $request->video_type);
        }

        $videos = $query->latest()->paginate(10)->withQueryString();
        
        // PERBAIKAN DROPDOWN MODAL TAMBAH:
        // Guru hanya diperbolehkan menyematkan video ke kelas asuhannya sendiri.
        if ($user->hasRole('admin')) {
            $courses = Course::where('status', 'published')->orderBy('title')->get();
        } else {
            $courses = Course::where('status', 'published')
                             ->where('teacher_id', $user->id)
                             ->orderBy('title')
                             ->get();
        }

        return view('admin.course-video', compact('videos', 'courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id'  => 'required|exists:courses,id',
            'title'      => 'required|string|max:255',
            'video_type' => 'required|in:material,recording', // SINKRONISASI: Mengikuti enum DB
            'video_url'  => 'required|url',
        ]);

        CourseVideo::create($validated);

        return redirect()->back()->with('success', 'Video pembelajaran baru berhasil disematkan!');
    }

    public function update(Request $request, CourseVideo $video)
    {
        $validated = $request->validate([
            'course_id'  => 'required|exists:courses,id',
            'title'      => 'required|string|max:255',
            'video_type' => 'required|in:material,recording', // SINKRONISASI: Mengikuti enum DB
            'video_url'  => 'required|url',
        ]);

        $video->update($validated);

        return redirect()->back()->with('success', 'Data video pembelajaran berhasil diperbarui!');
    }

    public function destroy(CourseVideo $video)
    {
        $video->delete();
        return redirect()->back()->with('success', 'Tautan video berhasil dihapus dari sistem!');
    }
}