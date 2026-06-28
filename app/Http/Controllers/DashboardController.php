<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Payment;
use App\Models\Review;
use App\Models\StudentAnswer;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{

    public function dashboardAdmin()
    {
        try {
            $totalTransactions = Payment::where('status', 'approved')->sum('amount');
            $totalStudents = User::role('siswa')->count();
            $totalTeachers = User::role('guru')->count();
            $totalCourses = Course::where('status', 'published')->count();
            $latestPayments = Payment::with('student')
                ->latest()
                ->take(5)
                ->get();
            $pendingTeachers = DB::table('teacher_profiles')
                ->join('users', 'teacher_profiles.user_id', '=', 'users.id')
                ->where('teacher_profiles.verification_status', 'pending')
                ->select(
                    'teacher_profiles.id as profile_id',
                    'users.name as teacher_name',
                    'teacher_profiles.skills_tags'
                )
                ->latest('teacher_profiles.created_at')
                ->get();
            $pendingCourses = Course::with('teacher')
                ->where('status', 'pending')
                ->latest()
                ->get();

            $pendingStudents = DB::table('student_biodatas')
                ->join('users', 'student_biodatas.user_id', '=', 'users.id')
                ->where('student_biodatas.status', 'pending')
                ->select(
                    'student_biodatas.id as biodata_id',
                    'users.name as student_name',
                    'student_biodatas.institution_name',
                    'student_biodatas.nisn'
                )
                ->latest('student_biodatas.created_at')
                ->get();

            return view('admin.dashboard', compact(
                'totalTransactions',
                'totalStudents',
                'totalTeachers',
                'totalCourses',
                'pendingTeachers',
                'pendingStudents',
                'pendingCourses',
                'latestPayments'
            ));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman Dashboard Admin: ' . $e->getMessage());
            return redirect('/login')->with('error', 'Terjadi kesalahan sistem saat memuat dashboard admin.');
        }
    }

    public function dashboardGuru()
    {
        try {
            $teacherId = Auth::id();

            // 1. STATISTIK UTAMA (Sesuai Skema DB Anda)

            // Ambil semua ID kursus yang dibuat oleh guru ini
            $teacherCourseIds = Course::where('teacher_id', $teacherId)->pluck('id');

            // A. Total Pendapatan Bersih Guru dari tabel teacher_earnings
            $monthlyEarnings = DB::table('teacher_earnings')
                ->where('teacher_id', $teacherId)
                ->sum('amount_earned');

            // B. Total Siswa Aktif Unik dari tabel course_students menggunakan 'student_id'
            $activeStudentsCount = CourseStudent::whereIn('course_id', $teacherCourseIds)
                ->distinct('student_id')
                ->count('student_id');

            // C. Jumlah Kursus yang Telah Dirilis
            $releasedCoursesCount = $teacherCourseIds->count();

            // D. Rating Penilaian Rata-rata (Fallback ke 5.0 jika belum ada kolom rating)
            $averageRating = Review::where('teacher_id', $teacherId)->avg('rating') ?? 5.0;


            // 2. PERFORMA KELAS ANDA
            $myCourses = Course::where('teacher_id', $teacherId) // <-- Memastikan menggunakan mentor_id sesuai skema database Anda
                ->withCount('students') // <-- Biarkan Laravel mengelola join tabel pivot course_students secara otomatis tanpa closure tambahan
                ->with(['category'])
                ->take(4)
                ->get()
                ->map(function ($course) {
                    // Hitung omset khusus kelas ini dari tabel payments yang sukses/approved
                    $course->revenue = DB::table('payments')
                        ->where('course_id', $course->id)
                        ->where('status', 'approved')
                        ->sum('amount') ?? 0;

                    // Kode singkatan otomatis (contoh: Advanced Mobile Programming -> ADV)
                    $course->short_name = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $course->title), 0, 3));
                    return $course;
                });


            // 3. JADWAL LIVE MENTORING TERDEKAT
            // Jika model ClassSchedule belum ada, kueri ini aman dari crash dengan try-catch internal / fallback collect
            $liveSchedules = collect();
            if (class_exists('App\Models\ClassSchedule')) {
                $liveSchedules = ClassSchedule::whereIn('course_id', $teacherCourseIds)
                    ->where('start_time', '>=', now()->startOfDay())
                    ->orderBy('end_time', 'asc')
                    ->take(2)
                    ->get();
            }


            // 4. RIWAYAT AKTIVITAS SISWA (Dinamis gabungan dari Join & Tugas Baru)
            $activities = collect();

            // A. Ambil Data Siswa yang Baru Join (Menggunakan kolom student_id hasil migrasi)
            $newJoins = DB::table('course_students')
                ->join('users', 'course_students.student_id', '=', 'users.id')
                ->join('courses', 'course_students.course_id', '=', 'courses.id')
                ->whereIn('course_students.course_id', $teacherCourseIds)
                ->select('users.name', 'courses.title as course_title', 'course_students.created_at')
                ->orderBy('course_students.created_at', 'desc')
                ->take(3)
                ->get();

            foreach ($newJoins as $join) {
                $activities->push([
                    'type' => 'join',
                    'user_name' => $join->name,
                    'initials' => strtoupper(substr($join->name, 0, 2)),
                    'description' => 'Membeli Kelas: ' . $join->course_title,
                    'raw_time' => Carbon::parse($join->created_at),
                    'badge' => 'Join • Baru'
                ]);
            }

            // B. Ambil Data Siswa Mengumpulkan Tugas (Mencegah crash jika model belum lengkap)
            if (class_exists('App\Models\StudentAnswer')) {
                $newSubmissions = StudentAnswer::whereHas('question.quiz.material.course', function ($q) use ($teacherId) {
                    $q->where('teacher_id', $teacherId);
                })
                    ->with(['student'])
                    ->orderBy('created_at', 'desc')
                    ->take(3)
                    ->get();

                foreach ($newSubmissions as $sub) {
                    if ($sub->user) {
                        $activities->push([
                            'type' => 'tugas',
                            'user_name' => $sub->user->name,
                            'initials' => strtoupper(substr($sub->user->name, 0, 2)),
                            'description' => 'Mengumpulkan Tugas di Modul Kelas',
                            'raw_time' => Carbon::parse($sub->created_at),
                            'badge' => 'Tugas • Baru'
                        ]);
                    }
                }
            }

            // Urutkan aktivitas gabungan secara descending berdasarkan waktu asli carbon
            $activities = $activities->sortByDesc('raw_time')->map(function ($act) {
                $act['time'] = $act['raw_time']->diffForHumans();
                return $act;
            })->values();


            return view('guru.dashboard', compact(
                'monthlyEarnings',
                'activeStudentsCount',
                'releasedCoursesCount',
                'averageRating',
                'myCourses',
                'liveSchedules',
                'activities'
            ));
        } catch (\Exception $e) {
            Log::error('Gagal memuat halaman Dashboard Guru: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kegagalan sistem saat memuat data dashboard guru.');
        }
    }

    public function dashboardSiswa()
    {
        try {
            $userId = Auth::id();

            // 1. Hitung statistik utama
            $enrolledCoursesCount = Course::whereHas('students', function ($q) use ($userId) {
                $q->where('student_id', $userId);
            })->count();

            // Ambil jumlah kelas yang progress pivot-nya >= 100
            $completedCoursesCount = Course::whereHas('students', function ($q) use ($userId) {
                //  PERBAIKAN: Berikan prefix nama tabel 'course_students.' pada student_id dan progress
                $q->where('course_students.student_id', $userId)
                    ->where('course_students.status', '>=', 100);
            })->count();

            $certificatesCount = $completedCoursesCount;

            // 2. Ambil daftar Kursus yang sedang berjalan
            $activeCourses = Course::whereHas('students', function ($q) use ($userId) {
                $q->where('student_id', $userId);
            })
                ->with(['teacher', 'category']) // Menggunakan 'teacher' sesuai dengan model Course Anda
                ->get()
                ->map(function ($course) use ($userId) {

                    // A. Hitung total kuis yang tersedia di kelas ini
                    $totalQuizzes = $course->materials()
                        ->whereHas('quiz')
                        ->count();

                    if ($totalQuizzes > 0) {
                        // B. Hitung berapa kuis unik di kelas ini yang sudah dijawab oleh siswa
                        $completedQuizzes = DB::table('student_answers')
                            ->join('questions', 'student_answers.question_id', '=', 'questions.id')
                            ->join('quizzes', 'questions.quiz_id', '=', 'quizzes.id')
                            ->join('course_materials', 'quizzes.material_id', '=', 'course_materials.id')
                            ->where('student_answers.user_id', $userId)
                            ->where('course_materials.course_id', $course->id)
                            ->distinct('quizzes.id')
                            ->count('quizzes.id');

                        // C. Hitung presentasi progres belajar
                        $course->student_progress = min(100, round(($completedQuizzes / $totalQuizzes) * 100));
                    } else {
                        // Default 0 jika instruktur belum membuat kuis sama sekali
                        $course->student_progress = 0;
                    }

                    // Singkatan nama otomatis (Contoh: Advanced Mobile Programming -> ADV)
                    // Menghapus spasi/karakter non-alphanumeric, lalu ambil 3 huruf pertama
                    $cleanTitle = preg_replace('/[^A-Za-z0-9]/', '', $course->title);
                    $course->short_name = strtoupper(substr($cleanTitle, 0, 3));

                    return $course;
                })
                // Mengurutkan dari progress terkecil agar siswa termotivasi melanjutkan kelas yang tertinggal
                ->sortBy('student_progress')
                ->take(3);

            // 3. Mengambil Jadwal Class & Tugas Terdekat
            $liveClasses = ClassSchedule::where('start_time', '>=', now())
                ->whereHas('course.students', function ($q) use ($userId) {
                    $q->where('student_id', $userId);
                })
                ->orderBy('end_time', 'asc')
                ->take(2)
                ->get();

            $pendingQuizzes = Course::whereHas('students', function ($q) use ($userId) {
                $q->where('student_id', $userId);
            })
                ->whereHas('materials.quiz')
                ->with('materials.quiz')
                ->get()
                ->flatMap(function ($course) use ($userId) {
                    return $course->materials->map(function ($mat) use ($userId) {
                        if (!$mat->quiz) return null;

                        $taken = StudentAnswer::where('user_id', $userId)
                            ->whereHas('question', function ($q) use ($mat) {
                                $q->where('quiz_id', $mat->quiz->id);
                            })->exists();

                        return $taken ? null : $mat->quiz;
                    });
                })
                ->filter()
                ->take(2);

            return view('student.dashboard', compact(
                'enrolledCoursesCount',
                'completedCoursesCount',
                'certificatesCount',
                'activeCourses',
                'liveClasses',
                'pendingQuizzes'
            ));
        } catch (\Exception $e) {
            Log::error('Gagal memuat halaman Dashboard Siswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kegagalan sistem saat memuat data dashboard siswa.');
        }
    }
}
