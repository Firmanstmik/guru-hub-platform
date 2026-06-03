<?php

namespace App\Http\Controllers;

use App\Models\Booking;
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

            // 4. Eksekusi query dengan pagination agar halaman tidak berat
            $courses = $query->latest()->paginate(9)->withQueryString();

            // 5. Data pendukung untuk mengisi opsi list dropdown filter Guru (Spatie Peran)
            $teachers = User::role('guru')->where('is_active', true)->orderBy('name')->get();

            return view('student.catalog', compact('courses', 'teachers'));

        } catch (Exception $e) {
            Log::error('Gagal memuat katalog kelas siswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat katalog kelas.');
        }
    }

    /**
     * KELASKU / KELAS SAYA (Siswa)
     */
    public function myCourse()
    {
        try {
            $student = Auth::user();

            // 1. Ambil Kelas yang sedang AKTIF (Sudah dibayar dan siap dipelajari)
            $activeCourses = Booking::with(['course.teacher', 'course.category'])
                ->where('student_id', $student->id)
                ->where('status', 'success')
                ->latest()
                ->get();

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
                return redirect()->route('student.courses') // Menyesuaikan nama rute katalog Anda
                    ->with('error', 'Akses ditolak. Anda belum terdaftar atau belum menyelesaikan pembayaran di kelas premium ini.');
            }

            // --- LOGIKA: Cek Status Kelulusan Murid dari tabel course_students ---
            $studentCourse = DB::table('course_students')
                ->where('student_id', $userId)
                ->where('course_id', $course_id)
                ->first();

            // Nilai boolean true jika baris data ditemukan dan status bernilai 'completed'
            $isCompleted = $studentCourse && $studentCourse->status === 'completed';

            // --- AMBIL DATA SERTIFIKAT: Diambil jika siswa sudah lulus ---
            $cert = null;
            if ($isCompleted) {
                $cert = Certificate::where('student_id', $userId)
                    ->where('course_id', $course_id)
                    ->first();
            }

            // 2. Ambil data Course beserta Video, Materi, DAN Jadwal Meeting (schedules)
            $course = Course::with(['videos', 'materials', 'schedules' => function ($query) {
                $query->orderBy('start_time', 'asc');
            }])->findOrFail($course_id);

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