<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CourseStudent;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class CourseStudentController extends Controller
{
    public function index(Request $request)
    {
        // Eager loading untuk optimasi database query
        $query = CourseStudent::with(['course', 'student']);

        // Filter berdasarkan Status Belajar (active, completed)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan Kelas Spesifik
        if ($request->has('course_id') && $request->course_id != '') {
            $query->where('course_id', $request->course_id);
        }

        // Pencarian berdasarkan nama murid
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $enrollments = $query->latest()->paginate(10)->withQueryString();
        
        // Data pendukung untuk Dropdown Form di dalam Modal
        $courses = Course::orderBy('title')->get();
        // Hanya mengambil user dengan peran murid/student
        $students = User::role('siswa')->orderBy('name')->get(); 

        return view('admin.course-student', compact('enrollments', 'courses', 'students'));
    }

    /**
     * Mendaftarkan murid ke kelas secara manual via Modal
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id'  => 'required|exists:courses,id',
            'student_id' => 'required|exists:users,id',
            'status'     => 'required|in:active,completed',
        ]);

        // Cek apakah murid sudah terdaftar di kelas tersebut sebelumnya
        $exists = CourseStudent::where('course_id', $validated['course_id'])
                               ->where('student_id', $validated['student_id'])
                               ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Murid tersebut sudah terdaftar di kelas ini.');
        }

        CourseStudent::create($validated);

        return redirect()->back()->with('success', 'Murid berhasil didaftarkan ke kelas secara manual.');
    }

    /**
     * Memperbarui status belajar murid via Modal Edit
     */
    public function update(Request $request, CourseStudent $courseStudent)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,completed',
        ]);

        $courseStudent->update($validated);

        return redirect()->back()->with('success', 'Status partisipasi belajar murid berhasil diperbarui.');
    }

    /**
     * Mengeluarkan murid dari kelas (Pemberhentian Hak Akses)
     */
    public function destroy(CourseStudent $courseStudent)
    {
        $courseStudent->delete();

        return redirect()->back()->with('success', 'Murid telah berhasil dikeluarkan dari kelas terkait.');
    }
}