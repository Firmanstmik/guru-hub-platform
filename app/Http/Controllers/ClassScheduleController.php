<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class ClassScheduleController extends Controller
{
    /**
     * INDEX (Admin & Guru)
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            // Eager loading data kursus dan guru pengajarnya
            $query = ClassSchedule::with(['course.teacher']);

            // Spatie: Saring jadwal berdasarkan peran Akun Guru yang masuk
            if (!$user->hasRole('admin')) {
                // Hanya ambil jadwal yang kelasnya milik Guru yang sedang login
                $query->whereHas('course', function ($q) use ($user) {
                    $q->where('teacher_id', $user->id);
                });
            }

            // Filter berdasarkan Platform (Zoom / Google Meet)
            if ($request->has('platform') && $request->platform != '') {
                $query->where('platform', $request->platform);
            }

            // Filter berdasarkan status waktu (Mendatang / Selesai)
            if ($request->has('time_status') && $request->time_status != '') {
                if ($request->time_status === 'upcoming') {
                    $query->where('start_time', '>=', now());
                } elseif ($request->time_status === 'past') {
                    $query->where('end_time', '<', now());
                }
            }

            // Jadwal terdekat tampil paling atas
            $schedules = $query->orderBy('start_time', 'asc')->paginate(10)->withQueryString();
            
            // Spatie: Guru hanya boleh memilih kelas miliknya sendiri untuk dropdown modal
            if ($user->hasRole('admin')) {
                $courses = Course::where('status', 'published')->orderBy('title')->get();
            } else {
                $courses = Course::where('status', 'published')
                                 ->where('teacher_id', $user->id)
                                 ->orderBy('title')
                                 ->get();
            }

            if ($user->hasRole('admin')) {
                return view('admin.schedules', compact('schedules', 'courses'));
            } elseif ($user->hasRole('guru')) {
                return view('guru.schedules', compact('schedules', 'courses'));
            } else {
                abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
            }

        } catch (Exception $e) {
            Log::error('Gagal memuat halaman jadwal live class: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data jadwal sesi.');
        }
    }

    /**
     * STORE (Tambah Jadwal Live Class Baru)
     */
    public function store(Request $request)
    {
        // Validasi dengan custom pesan Bahasa Indonesia
        $validated = $request->validate([
            'course_id'        => 'required|exists:courses,id',
            'topic'            => 'required|string|max:255',
            'start_time'       => 'required|date|after_or_equal:now',
            'end_time'         => 'required|date|after:start_time',
            'platform'         => 'required|in:zoom,google_meet',
            'meeting_link'     => 'nullable|url',
            'meeting_id'       => 'nullable|string|max:100',
            'meeting_password' => 'nullable|string|max:100',
        ], [
            'course_id.required'        => 'Kelas/Kursus wajib dipilih.',
            'course_id.exists'          => 'Kelas yang dipilih tidak valid atau tidak ditemukan.',
            'topic.required'            => 'Topik atau pembahasan sesi live wajib diisi.',
            'topic.max'                 => 'Topik pembahasan terlalu panjang, maksimal 255 karakter.',
            'start_time.required'       => 'Waktu mulai sesi wajib ditentukan.',
            'start_time.date'           => 'Format tanggal dan waktu mulai tidak valid.',
            'start_time.after_or_equal' => 'Waktu mulai tidak boleh tanggal atau jam yang sudah lewat.',
            'end_time.required'         => 'Waktu selesai sesi wajib ditentukan.',
            'end_time.date'             => 'Format tanggal dan waktu selesai tidak valid.',
            'end_time.after'            => 'Waktu selesai harus lebih lambat dari waktu mulai sesi.',
            'platform.required'         => 'Platform pertemuan (Zoom/Google Meet) wajib dipilih.',
            'platform.in'               => 'Platform yang dipilih tidak sesuai ketentuan.',
            'meeting_link.url'          => 'Format tautan (URL) meeting tidak valid. Pastikan menyertakan http:// atau https://.',
            'meeting_id.max'            => 'Meeting ID terlalu panjang, maksimal 100 karakter.',
            'meeting_password.max'      => 'Password meeting terlalu panjang, maksimal 100 karakter.',
        ]);

        try {
            ClassSchedule::create($validated);

            return redirect()->back()->with('success', 'Jadwal sesi live class baru berhasil ditambahkan!');

        } catch (Exception $e) {
            Log::error('Gagal menambahkan jadwal baru: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan jadwal sesi baru karena kendala internal sistem.');
        }
    }

    /**
     * UPDATE (Perbarui Jadwal Live Class)
     */
    public function update(Request $request, ClassSchedule $schedule)
    {
        // Validasi dengan custom pesan Bahasa Indonesia
        $validated = $request->validate([
            'course_id'        => 'required|exists:courses,id',
            'topic'            => 'required|string|max:255',
            'start_time'       => 'required|date',
            'end_time'         => 'required|date|after:start_time',
            'platform'         => 'required|in:zoom,google_meet',
            'meeting_link'     => 'nullable|url',
            'meeting_id'       => 'nullable|string|max:100',
            'meeting_password' => 'nullable|string|max:100',
        ], [
            'course_id.required'   => 'Asosiasi kelas wajib ditentukan.',
            'course_id.exists'     => 'Kelas tidak terdaftar di dalam sistem.',
            'topic.required'       => 'Topik pembahasan tidak boleh kosong.',
            'start_time.required'  => 'Waktu mulai tidak boleh dikosongkan.',
            'end_time.required'    => 'Waktu selesai wajib ditentukan.',
            'end_time.after'       => 'Waktu selesai harus lebih lambat dari waktu mulai sesi.',
            'platform.required'    => 'Platform wajib ditentukan.',
            'meeting_link.url'     => 'Format tautan (URL) meeting baru tidak valid.'
        ]);

        try {
            $schedule->update($validated);

            return redirect()->back()->with('success', 'Detail jadwal sesi berhasil diperbarui!');

        } catch (Exception $e) {
            Log::error('Gagal memperbarui jadwal ID ' . $schedule->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui detail jadwal karena kendala sistem.');
        }
    }

    /**
     * DESTROY (Hapus Jadwal)
     */
    public function destroy(ClassSchedule $schedule)
    {
        try {
            // Proteksi: Jika sudah ada siswa yang melakukan booking sesi ini, cegah penghapusan sembarangan
            if ($schedule->bookings()->count() > 0) {
                return redirect()->back()->with('error', 'Gagal menghapus! Sudah ada murid yang melakukan booking pada jadwal sesi ini.');
            }

            $schedule->delete();

            return redirect()->back()->with('success', 'Jadwal sesi berhasil dihapus dari sistem!');

        } catch (Exception $e) {
            Log::error('Gagal menghapus jadwal ID ' . $schedule->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus jadwal sesi karena kendala database.');
        }
    }
}