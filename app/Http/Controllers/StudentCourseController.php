<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Categori;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class StudentCourseController extends Controller
{
    /**
     * KATALOG KELAS (Siswa)
     */
    public function index(Request $request)
    {
        try {
            // 1. Eager loading data guru pengajar dan kategori, serta menghitung jumlah siswa dan materi
            $query = Course::with(['teacher', 'category'])
                ->withCount(['students', 'materials'])
                ->where('status', 'published');

            // 2. Filter INPUT PENCARIAN (Bisa mencari Judul Kelas ATAU Nama Guru)
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhereHas('teacher', function ($teacherQuery) use ($search) {
                            $teacherQuery->where('name', 'like', "%{$search}%");
                        });
                });
            }

            // 3. Filter SELECT DROPDOWN GURU (Spesifik berdasarkan pilihan Guru tertentu)
            if ($request->has('teacher_id') && $request->teacher_id != '') {
                $query->where('teacher_id', $request->teacher_id);
            }

            // [TAMBAHAN] Filter SELECT DROPDOWN KATEGORI
            if ($request->has('category_id') && $request->category_id != '') {
                $query->where('category_id', $request->category_id);
            }

            // 4. Eksekusi query dengan pagination agar halaman tidak berat
            $courses = $query->latest()->paginate(9)->withQueryString();

            // 5. Data pendukung untuk mengisi opsi list dropdown filter Guru (Spatie Peran)
            $teachers = User::role('guru')->where('is_active', true)->orderBy('name')->get();

            // [TAMBAHAN] Ambil data seluruh kategori untuk dropdown filter di Blade
            $categories = Categori::orderBy('name')->get();

            // Tambahkan $categories ke dalam compact()
            return view('student.catalog', compact('courses', 'teachers', 'categories'));
        } catch (Exception $e) {
            Log::error('Gagal memuat katalog kelas siswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat katalog kelas.');
        }
    }

    /**
     * KELAS SAYA (Siswa)
     */
    public function myCourse()
    {
        try {
            $student = Auth::user();

            // 1. Ambil Kelas yang sedang AKTIF + Hitung Progres Belajar secara realtime
            $activeCourses = Booking::with(['course.teacher', 'course.category'])
                ->where('student_id', $student->id)
                ->where('status', 'success')
                ->latest()
                ->get()
                ->map(function ($booking) use ($student) {
                    $course = $booking->course;

                    if (!$course) {
                        $booking->progress_percentage = 0;
                        return $booking;
                    }

                    // Hitung total materi yang memiliki kuis di kelas ini
                    $totalQuizzes = $course->quizzes()->count();

                    if ($totalQuizzes > 0) {
                        // Hitung berapa banyak kuis unik dari kelas ini yang sudah dijawab oleh siswa
                        $completedQuizzes = DB::table('student_answers')
                            ->join('questions', 'student_answers.question_id', '=', 'questions.id')
                            ->join('quizzes', 'questions.quiz_id', '=', 'quizzes.id')
                            ->join('course_materials', 'quizzes.material_id', '=', 'course_materials.id')
                            ->where('student_answers.user_id', $student->id)
                            ->where('course_materials.course_id', $course->id)
                            ->distinct('quizzes.id')
                            ->count('quizzes.id');

                        // Rumus Progres: (Kuis Selesai / Total Kuis) * 100
                        $booking->progress_percentage = min(100, round(($completedQuizzes / $totalQuizzes) * 100));
                    } else {
                        // Jika kelas belum memiliki kuis/materi sama sekali, set ke 0 atau 100 tergantung kebijakan
                        $booking->progress_percentage = 0;
                    }

                    return $booking;
                });

            // 2. Ambil Kelas yang sudah selesai / lulus
            $completedCourses = Booking::with(['course.teacher', 'course.category'])
                ->where('student_id', $student->id)
                ->where('status', 'completed')
                ->latest()
                ->get();

            return view('student.my-courses', compact('activeCourses', 'completedCourses'));
        } catch (Exception $e) {
            Log::error('Gagal memuat kelas saya (myCourse): ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat daftar kelas Anda.');
        }
    }

    /**
     * RUANG BELAJAR (Siswa)
     */
    // public function roomLearn(Request $request, $course_id)
    // {
    //     try {
    //         $userId = Auth::id();

    //         // 1. GATEKEEPER: Pastikan siswa terdaftar secara sah
    //         $hasAccess = Booking::where('student_id', $userId)
    //             ->where('course_id', $course_id)
    //             ->whereIn('status', ['success', 'completed'])
    //             ->exists();

    //         if (!$hasAccess) {
    //             return redirect()->route('student.courses') // Menyesuaikan nama rute katalog Anda
    //                 ->with('error', 'Akses ditolak. Anda belum terdaftar atau belum menyelesaikan pembayaran di kelas premium ini.');
    //         }

    //         // --- LOGIKA: Cek Status Kelulusan Murid dari tabel course_students ---
    //         $studentCourse = DB::table('course_students')
    //             ->where('student_id', $userId)
    //             ->where('course_id', $course_id)
    //             ->first();

    //         // Nilai boolean true jika baris data ditemukan dan status bernilai 'completed'
    //         $isCompleted = $studentCourse && $studentCourse->status === 'completed';

    //         // --- AMBIL DATA SERTIFIKAT: Diambil jika siswa sudah lulus ---
    //         $cert = null;
    //         if ($isCompleted) {
    //             $cert = Certificate::where('student_id', $userId)
    //                 ->where('course_id', $course_id)
    //                 ->first();
    //         }

    //         // 2. Ambil data Course beserta Video, Materi, DAN Jadwal Meeting (schedules)
    //         $course = Course::with(['videos', 'materials', 'schedules' => function ($query) {
    //             $query->orderBy('start_time', 'asc');
    //         }])->findOrFail($course_id);

    //         // 3. Ambil data booking siswa untuk melihat jika ada schedule_id spesifik
    //         $userBooking = Booking::with('schedule')
    //             ->where('student_id', $userId)
    //             ->where('course_id', $course_id)
    //             ->first();

    //         // 4. Logika penentuan item aktif (Video / Materi)
    //         $activeType = $request->query('type', 'video');
    //         $activeId = $request->query('id');
    //         $activeItem = null;

    //         if ($activeType === 'material') {
    //             $activeItem = $course->materials->firstWhere('id', $activeId) ?? $course->materials->first();
    //         } else {
    //             $activeItem = $course->videos->firstWhere('id', $activeId) ?? $course->videos->first();
    //             $activeType = 'video';
    //         }

    //         return view('student.room-learn', compact('course', 'activeItem', 'activeType', 'userBooking', 'isCompleted', 'cert'));

    //     } catch (Exception $e) {
    //         Log::error('Gagal mengakses ruang belajar kelas ID ' . $course_id . ': ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Gagal memuat ruang belajar karena terjadi kendala internal pada sistem.');
    //     }
    // }
    public function roomLearn(Request $request, $course_id)
    {
        try {
            $userId = Auth::id();

            // 1. GATEKEEPER: Pastikan siswa terdaftar secara sah
            $hasAccess = Booking::where('student_id', $userId)
                ->where('course_id', $course_id)
                ->whereIn('status', ['success', 'completed'])
                ->exists();

            if (!$hasAccess) {
                return redirect()->route('student.courses')
                    ->with('error', 'Akses ditolak. Anda belum terdaftar atau belum menyelesaikan pembayaran di kelas premium ini.');
            }

            // --- LOGIKA: Cek Status Kelulusan Murid dari tabel course_students ---
            $studentCourse = DB::table('course_students')
                ->where('student_id', $userId)
                ->where('course_id', $course_id)
                ->first();

            $isCompleted = $studentCourse && $studentCourse->status === 'completed';

            // --- AMBIL DATA SERTIFIKAT ---
            $cert = null;
            if ($isCompleted) {
                $cert = Certificate::where('student_id', $userId)
                    ->where('course_id', $course_id)
                    ->first();
            }

            // 2. Ambil data Course beserta Video, Materi, DAN Jadwal Meeting
            $course = Course::with(['videos', 'materials', 'schedules' => function ($query) {
                $query->orderBy('start_time', 'asc');
            }])->findOrFail($course_id);

            // ================== LOGIKA BARU: MAPPING CHECKBOX PROGRESS ==================
            // Ambil semua daftar ID materi yang sudah diselesaikan oleh user aktif saat ini
            $completedVideoIds = DB::table('user_progress')
                ->where('user_id', $userId)
                ->where('progressable_type', 'App\Models\Video')
                ->pluck('progressable_id')
                ->toArray();

            $completedMaterialIds = DB::table('user_progress')
                ->where('user_id', $userId)
                ->where('progressable_type', 'App\Models\Material')
                ->pluck('progressable_id')
                ->toArray();

            // Set properti dinamis 'is_completed' ke masing-masing item silabus
            $course->videos->each(function ($video) use ($completedVideoIds) {
                $video->is_completed = in_array($video->id, $completedVideoIds);
            });

            $course->materials->each(function ($material) use ($completedMaterialIds) {
                $material->is_completed = in_array($material->id, $completedMaterialIds);
            });
            // ============================================================================

            // 3. Ambil data booking siswa untuk melihat jika ada schedule_id spesifik
            $userBooking = Booking::with('schedule')
                ->where('student_id', $userId)
                ->where('course_id', $course_id)
                ->first();

            // 4. Logika penentuan item aktif (Video / Materi)
            $activeType = $request->query('type', 'video');
            $activeId = $request->query('id');
            $activeItem = null;

            if ($activeType === 'material') {
                $activeItem = $course->materials->firstWhere('id', $activeId) ?? $course->materials->first();
            } else {
                $activeItem = $course->videos->firstWhere('id', $activeId) ?? $course->videos->first();
                $activeType = 'video';
            }

            return view('student.room-learn', compact('course', 'activeItem', 'activeType', 'userBooking', 'isCompleted', 'cert'));
        } catch (Exception $e) {
            Log::error('Gagal mengakses ruang belajar kelas ID ' . $course_id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat ruang belajar karena terjadi kendala internal pada sistem.');
        }
    }
}
