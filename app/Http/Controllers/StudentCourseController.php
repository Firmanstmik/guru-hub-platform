<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentCourseController extends Controller
{
    public function index(Request $request)
    {
        // 1. Eager loading data guru pengajar dan kategori, serta menghitung jumlah siswa dan materi untuk setiap kelas
        {
            $query = Course::with(['teacher', 'category'])
                ->withCount(['students', 'materials'])
                ->where('status', 'published');

            // 2. Filter INPUT PENCARIAN (Bisa mencari Judul Kelas ATAU Nama Guru)
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    // Cari berdasarkan judul kelas
                    $q->where('title', 'like', "%{$search}%")
                        // ATAU cari berdasarkan nama guru pengajarnya
                        ->orWhereHas('teacher', function ($teacherQuery) use ($search) {
                            $teacherQuery->where('name', 'like', "%{$search}%");
                        });
                });
            }

            // 3. Filter SELECT DROPDOWN GURU (Spesifik berdasarkan pilihan Guru tertentu)
            if ($request->has('teacher_id') && $request->teacher_id != '') {
                $query->where('teacher_id', $request->teacher_id);
            }

            // 4. Eksekusi query dengan pagination agar halaman tidak berat
            $courses = $query->latest()->paginate(9)->withQueryString();

            // 5. Data pendukung untuk mengisi opsi list dropdown filter Guru
            $teachers = User::role('guru')->where('is_active', true)->orderBy('name')->get();

            return view('student.catalog', compact('courses', 'teachers'));
        }
    }

    public function myCourse()
    {
        $student = Auth::user();

        // 1. Ambil Kelas yang sedang AKTIF (Sudah dibayar dan siap dipelajari)
        $activeCourses = Booking::with(['course.teacher', 'course.category'])
            ->where('student_id', $student->id)
            ->where('status', 'success') // Menandakan pembayaran valid & kelas aktif
            ->latest()
            ->get();

        $completedCourses = Booking::with(['course.teacher', 'course.category'])
            ->where('student_id', $student->id)
            ->where('status', 'completed') // Misal: Status dirubah admin menjadi completed jika siswa lulus
            ->latest()
            ->get();

        return view('student.my-courses', compact('activeCourses', 'completedCourses'));
    }

    // public function roomLearn(Request $request, $course_id)
    // {
    //     $userId = Auth::id();

    //     // 1. GATEKEEPER: Pastikan siswa terdaftar secara sah dan status pembelian sudah aktif
    //     $hasAccess = Booking::where('student_id', $userId)
    //         ->where('course_id', $course_id)
    //         ->whereIn('status', ['success', 'completed'])
    //         ->exists();

    //     if (!$hasAccess) {
    //         return redirect()->route('student.courses')
    //             ->with('error', 'Akses ditolak. Anda belum terdaftar di kelas premium ini.');
    //     }

    //     // 2. Ambil data Course beserta semua Video dan Materi pendukungnya
    //     $course = Course::with(['videos', 'materials'])->findOrFail($course_id);

    //     // 3. Logika penentuan item aktif yang sedang diputar/dilihat oleh siswa
    //     $activeType = $request->query('type', 'video'); // default: video
    //     $activeId = $request->query('id');

    //     $activeItem = null;

    //     if ($activeType === 'material') {
    //         // Jika memilih materi PDF
    //         $activeItem = $course->materials->firstWhere('id', $activeId) ?? $course->materials->first();
    //     } else {
    //         // Jika memilih video (atau default saat pertama kali buka halaman)
    //         $activeItem = $course->videos->firstWhere('id', $activeId) ?? $course->videos->first();
    //         $activeType = 'video'; // Sinkronisasi tipe jika fallback ke video pertama
    //     }

    //     return view('student.room-learn', compact('course', 'activeItem', 'activeType'));
    // }

    public function roomLearn(Request $request, $course_id)
    {
        $userId = Auth::id();

        // 1. GATEKEEPER: Pastikan siswa terdaftar secara sah
        $hasAccess = Booking::where('student_id', $userId)
            ->where('course_id', $course_id)
            ->whereIn('status', ['success', 'completed'])
            ->exists();

        if (!$hasAccess) {
            return redirect()->route('student.courses')
                ->with('error', 'Akses ditolak. Anda belum terdaftar di kelas premium ini.');
        }

        // 2. Ambil data Course beserta Video, Materi, DAN Jadwal Meeting (schedules)
        // Pastikan model Course memiliki relasi 'schedules'
        $course = Course::with(['videos', 'materials', 'schedules' => function ($query) {
            $query->orderBy('start_time', 'asc');
        }])->findOrFail($course_id);

        // 3. Ambil data booking siswa untuk melihat jika ada schedule_id spesifik (opsional)
        $userBooking = Booking::with('schedule')
            ->where('student_id', $userId)
            ->where('course_id', $course_id)
            ->first();

        // 4. Logika penentuan item aktif
        $activeType = $request->query('type', 'video');
        $activeId = $request->query('id');
        $activeItem = null;

        if ($activeType === 'material') {
            $activeItem = $course->materials->firstWhere('id', $activeId) ?? $course->materials->first();
        } else {
            $activeItem = $course->videos->firstWhere('id', $activeId) ?? $course->videos->first();
            $activeType = 'video';
        }

        return view('student.room-learn', compact('course', 'activeItem', 'activeType', 'userBooking'));
    }
}
