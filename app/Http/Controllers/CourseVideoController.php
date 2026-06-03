<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CourseVideo;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class CourseVideoController extends Controller
{
    /**
     * INDEX (Admin & Guru)
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            // Eager loading kelas dan pengajarnya
            $query = CourseVideo::with(['course.teacher']);

            // Spatie: Saring video berdasarkan peran Akun Guru yang masuk
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
            
            // Spatie: Guru hanya diperbolehkan menyematkan video ke kelas asuhannya sendiri.
            if ($user->hasRole('admin')) {
                $courses = Course::where('status', 'published')->orderBy('title')->get();
            } else {
                $courses = Course::where('status', 'published')
                                 ->where('teacher_id', $user->id)
                                 ->orderBy('title')
                                 ->get();
            }

            if ($user->hasRole('admin')) {
                return view('admin.course-video', compact('videos', 'courses'));
            } elseif ($user->hasRole('guru')) {
                return view('guru.videos', compact('videos', 'courses'));
            } else {
                abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
            }

        } catch (Exception $e) {
            Log::error('Gagal memuat halaman video pembelajaran: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data video.');
        }
    }

    /**
     * STORE (Sematkan Video Baru)
     */
    public function store(Request $request)
    {
        // Validasi dengan custom pesan Bahasa Indonesia
        $validated = $request->validate([
            'course_id'  => 'required|exists:courses,id',
            'title'      => 'required|string|max:255',
            'video_type' => 'required|in:material,recording', 
            'video_url'  => 'required|url',
        ], [
            'course_id.required'  => 'Kelas/Kursus wajib dipilih.',
            'course_id.exists'    => 'Kelas yang dipilih tidak valid atau tidak ditemukan.',
            'title.required'       => 'Judul video pembelajaran tidak boleh kosong.',
            'title.max'            => 'Judul video terlalu panjang, maksimal 255 karakter.',
            'video_type.required'  => 'Tipe video wajib ditentukan.',
            'video_type.in'        => 'Tipe video harus berupa Material atau Recording.',
            'video_url.required'   => 'Tautan (URL) video wajib diisi.',
            'video_url.url'        => 'Format tautan video tidak valid. Pastikan menyertakan http:// atau https://.'
        ]);

        try {
            CourseVideo::create($validated);

            return redirect()->back()->with('success', 'Video pembelajaran baru berhasil disematkan!');

        } catch (Exception $e) {
            Log::error('Gagal menyematkan video baru: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyematkan video baru karena kendala internal sistem.');
        }
    }

    /**
     * UPDATE (Perbarui Data Video)
     */
    public function update(Request $request, CourseVideo $video)
    {
        // Validasi dengan custom pesan Bahasa Indonesia
        $validated = $request->validate([
            'course_id'  => 'required|exists:courses,id',
            'title'      => 'required|string|max:255',
            'video_type' => 'required|in:material,recording', 
            'video_url'  => 'required|url',
        ], [
            'course_id.required'  => 'Asosiasi kelas wajib ditentukan.',
            'course_id.exists'    => 'Kelas tidak terdaftar di dalam sistem.',
            'title.required'       => 'Judul video tidak boleh dikosongkan.',
            'title.max'            => 'Judul video maksimal berisi 255 karakter.',
            'video_type.required'  => 'Tipe video tidak boleh kosong.',
            'video_type.in'        => 'Tipe video tidak sesuai dengan opsi ketentuan.',
            'video_url.required'   => 'Tautan (URL) video wajib ditentukan.',
            'video_url.url'        => 'Format tautan video baru tidak valid.'
        ]);

        try {
            $video->update($validated);

            return redirect()->back()->with('success', 'Data video pembelajaran berhasil diperbarui!');

        } catch (Exception $e) {
            Log::error('Gagal memperbarui video ID ' . $video->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data video karena kendala sistem.');
        }
    }

    /**
     * DESTROY (Hapus Tautan Video)
     */
    public function destroy(CourseVideo $video)
    {
        try {
            $video->delete();

            return redirect()->back()->with('success', 'Tautan video berhasil dihapus dari sistem!');

        } catch (Exception $e) {
            Log::error('Gagal menghapus video ID ' . $video->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus tautan video dari sistem.');
        }
    }
}