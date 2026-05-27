<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CourseVideo;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseVideoController extends Controller
{
    public function index(Request $request)
    {
        // Eager loading kelas dan pengajarnya
        $query = CourseVideo::with('course.teacher');

        // Filter berdasarkan kelas tertentu
        if ($request->has('course_id') && $request->course_id != '') {
            $query->where('course_id', $request->course_id);
        }

        // Filter berdasarkan tipe video (youtube, vimeo, dll)
        if ($request->has('video_type') && $request->video_type != '') {
            $query->where('video_type', $request->video_type);
        }

        $videos = $query->latest()->paginate(10)->withQueryString();
        $courses = Course::where('status', 'published')->orderBy('title')->get();

        return view('admin.course-video', compact('videos', 'courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id'  => 'required|exists:courses,id',
            'title'      => 'required|string|max:255',
            'video_type' => 'required|in:youtube,vimeo,google_drive',
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
            'video_type' => 'required|in:youtube,vimeo,google_drive',
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