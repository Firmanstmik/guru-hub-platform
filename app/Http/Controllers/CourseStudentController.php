<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CourseStudent;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class CourseStudentController extends Controller
{
    /**
     * INDEX (Admin & Guru)
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            // Eager loading untuk optimasi database query
            $query = CourseStudent::with(['course.teacher', 'student']);

            // Spatie: Jika bukan admin (berarti guru), batasi data siswa hanya yang mengikuti kelas milik guru tersebut
            if (!$user->hasRole('admin')) {
                $query->whereHas('course', function ($q) use ($user) {
                    $q->where('teacher_id', $user->id);
                });
            }

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
            
            // Spatie: Filter dropdown kelas untuk modal Tambah/Edit
            if ($user->hasRole('admin')) {
                $courses = Course::orderBy('title')->get();
            } else {
                $courses = Course::where('teacher_id', $user->id)->orderBy('title')->get();
            }

            // Spatie: Hanya mengambil user dengan peran murid/siswa
            $students = User::role('siswa')->orderBy('name')->get(); 

            if ($user->hasRole('admin')) {
                return view('admin.course-student', compact('enrollments', 'courses', 'students'));
            } elseif ($user->hasRole('guru')) {
                return view('guru.students', compact('enrollments', 'courses', 'students'));
            } else {
                abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
            }

        } catch (Exception $e) {
            Log::error('Gagal memuat halaman partisipasi siswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data partisipasi siswa.');
        }
    }

    /**
     * Mendaftarkan murid ke kelas secara manual via Modal
     */
    public function store(Request $request)
    {
        // Validasi dengan custom pesan Bahasa Indonesia
        $validated = $request->validate([
            'course_id'  => 'required|exists:courses,id',
            'student_id' => 'required|exists:users,id',
            'status'     => 'required|in:active,completed',
        ], [
            'course_id.required'  => 'Kelas/Kursus wajib dipilih.',
            'course_id.exists'    => 'Kelas yang dipilih tidak valid atau tidak ditemukan.',
            'student_id.required' => 'Siswa wajib ditentukan.',
            'student_id.exists'   => 'Data siswa tidak valid atau tidak terdaftar di sistem.',
            'status.required'     => 'Status belajar wajib ditentukan.',
            'status.in'           => 'Status belajar harus berupa Active atau Completed.'
        ]);

        try {
            // Cek apakah murid sudah terdaftar di kelas tersebut sebelumnya
            $exists = CourseStudent::where('course_id', $validated['course_id'])
                                   ->where('student_id', $validated['student_id'])
                                   ->exists();

            if ($exists) {
                return redirect()->back()->with('error', 'Gagal mendaftarkan! Siswa tersebut sudah terdaftar di kelas ini.');
            }

            CourseStudent::create($validated);

            return redirect()->back()->with('success', 'Siswa berhasil didaftarkan ke kelas secara manual.');

        } catch (Exception $e) {
            Log::error('Gagal mendaftarkan siswa secara manual: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mendaftarkan siswa karena kendala internal database.');
        }
    }

    /**
     * Memperbarui status belajar murid via Modal Edit
     */
    public function update(Request $request, CourseStudent $courseStudent)
    {
        // Validasi dengan custom pesan Bahasa Indonesia
        $validated = $request->validate([
            'status' => 'required|in:active,completed',
        ], [
            'status.required' => 'Status partisipasi belajar wajib ditentukan.',
            'status.in'       => 'Status partisipasi harus berupa opsi Active atau Completed.'
        ]);

        try {
            $courseStudent->update($validated);

            return redirect()->back()->with('success', 'Status partisipasi belajar siswa berhasil diperbarui.');

        } catch (Exception $e) {
            Log::error('Gagal memperbarui status belajar siswa ID ' . $courseStudent->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui status belajar karena kendala sistem.');
        }
    }

    /**
     * Mengeluarkan murid dari kelas (Pemberhentian Hak Akses)
     */
    public function destroy(CourseStudent $courseStudent)
    {
        try {
            $courseStudent->delete();

            return redirect()->back()->with('success', 'Siswa telah berhasil dikeluarkan dari kelas terkait.');

        } catch (Exception $e) {
            Log::error('Gagal mengeluarkan siswa ID ' . $courseStudent->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses pengeluaran siswa dari kelas.');
        }
    }
}