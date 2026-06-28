<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesCourseOwnership;
use App\Http\Controllers\Concerns\RethrowsAuthorizationFailures;
use App\Http\Controllers\Controller;
use App\Models\CourseVideo;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use FFMpeg\Format\Video\WebM;
use Exception;

class CourseVideoController extends Controller
{
    use AuthorizesCourseOwnership;
    use RethrowsAuthorizationFailures;

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

    public function store(Request $request)
    {
        // FIX 1: Hilangkan batas waktu (timeout) PHP & naikkan batas memori untuk kebutuhan render FFmpeg
        set_time_limit(0);
        ini_set('max_execution_time', 3600); // 1 Jam
        ini_set('memory_limit', '1024M');    // 1 GB RAM (Aman untuk memproses video besar)

        // Validasi gabungan antara input Tautan URL dan File Unggahan Lokal
        $request->validate([
            'course_id'  => 'required|exists:courses,id',
            'title'      => 'required|string|max:255',
            'video_type' => 'required|in:material,recording',
            'video_url'  => 'required_without:video_file|nullable|url',
            'video_file' => 'required_without:video_url|nullable|mimes:mp4,mov,avi|max:102400', // Maksimal 100MB
        ], [
            'course_id.required'         => 'Kelas/Kursus wajib dipilih.',
            'course_id.exists'           => 'Kelas yang dipilih tidak valid atau tidak ditemukan.',
            'title.required'             => 'Judul video pembelajaran tidak boleh kosong.',
            'title.max'                  => 'Judul video terlalu panjang, maksimal 255 karakter.',
            'video_type.required'        => 'Tipe video wajib ditentukan.',
            'video_type.in'              => 'Tipe video harus berupa Material atau Recording.',
            'video_url.required_without' => 'Tautan (URL) video wajib diisi jika Anda tidak mengunggah file video lokal.',
            'video_url.url'              => 'Format tautan video tidak valid.',
            'video_file.required_without' => 'Silakan unggah berkas video jika kotak tautan URL dikosongkan.',
            'video_file.mimes'           => 'Format berkas video harus berupa MP4, MOV, atau AVI.',
            'video_file.max'             => 'Ukuran video unggahan terlalu besar, maksimal adalah 100 MB.',
        ]);

        $originalPath = null;
        $webmPath = null;

        $this->authorizeOwnsCourseId((int) $request->course_id);

        DB::beginTransaction();
        try {
            $finalVideoUrl = $request->video_url;

            // Jika admin/guru mengunggah file video fisik mentah
            if ($request->hasFile('video_file')) {
                $uploadedFile = $request->file('video_file');

                // 1. Amankan video mentah ke disk local temporarily
                $originalPath = $uploadedFile->store('videos/raw', 'local');

                // 2. Tentukan nama file WebM baru
                $filenameOnly = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $webmPath = 'videos/converted/' . $filenameOnly . '_' . time() . '.webm';

                // 3. Konfigurasi Transcoding FFmpeg ke WebM (VP8 + Vorbis)
                $format = new WebM();
                $format->setVideoCodec('libvpx')
                    ->setAudioCodec('libvorbis')
                    ->setKiloBitrate(1200)
                    ->setAudioKiloBitrate(128);

                // FIX 2: Tambahkan parameter tambahan (Additional Parameters) untuk mencegah crash engine biner di Linux
                // Menentukan pixel format standard (yuv420p) dan threads agar FFmpeg tidak kehabisan thread di server Linux
                $format->setAdditionalParameters([
                    '-pix_fmt',
                    'yuv420p',
                    '-threads',
                    '4',
                    '-deadline',
                    'realtime' // Mempercepat proses render kompresi
                ]);

                // 4. Eksekusi Render Video
                FFMpeg::fromDisk('local')
                    ->open($originalPath)
                    ->export()
                    ->toDisk('public')
                    ->inFormat($format)
                    ->save($webmPath);

                // Dapatkan full URL file .webm publik untuk disimpan ke database
                $finalVideoUrl = Storage::disk('public')->url($webmPath);
            }

            // Simpan record data ke database
            CourseVideo::create([
                'course_id'  => $request->course_id,
                'title'      => $request->title,
                'video_type' => $request->video_type,
                'video_url'  => $finalVideoUrl,
            ]);

            DB::commit();

            // Hapus file MP4/MOV mentah asli agar storage tidak bengkak
            if ($originalPath) {
                Storage::disk('local')->delete($originalPath);
            }

            return redirect()->back()->with('success', 'Video pembelajaran baru berhasil diproses dan disematkan!');
        } catch (Exception $e) {
            DB::rollBack();
            $this->rethrowAuthorizationFailures($e);

            // Hapus sisa kegagalan berkas fisik di server jika database rollBack
            if ($originalPath) {
                Storage::disk('local')->delete($originalPath);
            }
            if ($webmPath) {
                Storage::disk('public')->delete($webmPath);
            }

            // FIX 3: Cetak error asli sistem secara detail ke log untuk memudahkan tracing jika terjadi kegagalan sistem operasional lanjutan
            Log::error('Gagal memproses unggah dan konversi video: ' . $e->getMessage() . ' | Line: ' . $e->getLine());
            return redirect()->back()->withInput()->with('error', 'Gagal memproses video. Detail Kendala: ' . $e->getMessage());
        }
    }

    public function update(Request $request, CourseVideo $video)
    {
        // Hilangkan batas waktu (timeout) PHP & naikkan batas memori untuk kebutuhan render FFmpeg
        set_time_limit(0);
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '1024M');

        // Validasi gabungan: video_url wajib diisi KECUALI jika ada file video baru yang diunggah
        $request->validate([
            'course_id'  => 'required|exists:courses,id',
            'title'      => 'required|string|max:255',
            'video_type' => 'required|in:material,recording',
            // Jika ada file video baru, video_url boleh kosong di form (karena akan otomatis diisi path baru setelah render)
            'video_url'  => 'required_without:video_file|nullable',
        ], [
            'course_id.required'         => 'Asosiasi kelas wajib ditentukan.',
            'course_id.exists'           => 'Kelas tidak terdaftar di dalam sistem.',
            'title.required'             => 'Judul video tidak boleh dikosongkan.',
            'title.max'                  => 'Judul video maksimal berisi 255 karakter.',
            'video_type.required'        => 'Tipe video tidak boleh kosong.',
            'video_type.in'              => 'Tipe video tidak sesuai dengan opsi ketentuan.',
            'video_url.required_without' => 'Tautan (URL) video wajib diisi jika Anda tidak mengunggah berkas video baru.',
        ]);

        $originalPath = null;
        $webmPath = null;
        $oldUrl = $video->video_url;

        DB::beginTransaction();
        try {
            $video->loadMissing('course');
            $this->authorizeOwnsCourse($video->course);
            $this->authorizeOwnsCourseId((int) $request->course_id);

            // Logika dasar: Ambil nilai URL yang dikirim dari form (bisa link youtube baru, atau link webm lama bawaan form)
            $finalVideoUrl = $request->video_url;

            // JIKA USER MENGUNGGAH FILE FISIK BARU (Menggantikan Video Lama)
            if ($request->hasFile('video_file')) {
                $uploadedFile = $request->file('video_file');

                // 1. Amankan video mentah ke disk local temporarily
                $originalPath = $uploadedFile->store('videos/raw', 'local');

                // 2. Tentukan nama file WebM baru
                $filenameOnly = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $cleanFilename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $filenameOnly); // Bersihkan special character nama file
                $webmPath = 'videos/converted/' . $cleanFilename . '_' . time() . '.webm';

                // 3. Konfigurasi Transcoding FFmpeg ke WebM (VP8 + Vorbis)
                $format = new \FFMpeg\Format\Video\WebM();
                $format->setVideoCodec('libvpx')
                    ->setAudioCodec('libvorbis')
                    ->setKiloBitrate(1200)
                    ->setAudioKiloBitrate(128);

                $format->setAdditionalParameters([
                    '-pix_fmt',
                    'yuv420p',
                    '-threads',
                    '4',
                    '-deadline',
                    'realtime'
                ]);

                // 4. Eksekusi Render Video di dalam Docker Container
                \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::fromDisk('local')
                    ->open($originalPath)
                    ->export()
                    ->toDisk('public')
                    ->inFormat($format)
                    ->save($webmPath);

                // Ambil URL publik penuh untuk file hasil render yang baru
                $finalVideoUrl = Storage::disk('public')->url($webmPath);
            }

            // JIKA USER TIDAK UBAH APA-APA (video_file kosong, dan video_url kosong karena bawaan default browser saat Opsi B diabaikan)
            if (empty($finalVideoUrl) && !$request->hasFile('video_file')) {
                $finalVideoUrl = $oldUrl;
            }

            // Update record ke database
            $video->update([
                'course_id'  => $request->course_id,
                'title'      => $request->title,
                'video_type' => $request->video_type,
                'video_url'  => $finalVideoUrl,
            ]);

            DB::commit();

            // PEMBERSIHAN 1: Selalu bersihkan video mentah (.mp4/mov asal) di disk local agar internal Docker tidak penuh
            if ($originalPath) {
                Storage::disk('local')->delete($originalPath);
            }

            // PEMBERSIHAN 2 (Hanya jika video awal DIGANTI): Hapus berkas .webm lama dari storage jika url lama berubah
            if ($oldUrl !== $finalVideoUrl && str_contains($oldUrl, '/storage/videos/converted/')) {
                // Menghapus base URL domain secara presisi beserta slash awal agar menjadi path relatif utuh
                $relativeStoragePath = str_replace(url('/storage/'), '', $oldUrl);
                $relativeStoragePath = ltrim($relativeStoragePath, '/'); // Memastikan tidak ada karakter '/' di awal path

                if (Storage::disk('public')->exists($relativeStoragePath)) {
                    Storage::disk('public')->delete($relativeStoragePath);
                }
            }

            return redirect()->back()->with('success', 'Data video pembelajaran berhasil diperbarui!');
        } catch (Exception $e) {
            DB::rollBack();
            $this->rethrowAuthorizationFailures($e);

            // Hapus sisa kegagalan transaksi file di server agar tidak menyisakan sampah berkas rusak
            if ($originalPath) {
                Storage::disk('local')->delete($originalPath);
            }
            if ($webmPath) {
                Storage::disk('public')->delete($webmPath);
            }

            Log::error('Gagal memperbarui video ID ' . $video->id . ': ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memproses pembaruan video. Detail: ' . $e->getMessage());
        }
    }
    public function destroy(CourseVideo $video)
    {
        $video->loadMissing('course');
        $this->authorizeOwnsCourse($video->course);

        try {
            $videoUrl = $video->video_url;

            // 1. Eksekusi penghapusan file fisik TERLEBIH DAHULU sebelum record database dihapus
            if (str_contains($videoUrl, 'videos/converted/')) {

                // FIX: Ekstrak path setelah kata 'storage/' secara aman menggunakan explode
                // Cara ini tidak peduli apakah URL menggunakan port 8000, 8002, localhost, atau nama domain produksi
                $urlParts = explode('storage/', $videoUrl);

                if (isset($urlParts[1])) {
                    $relativeStoragePath = ltrim($urlParts[1], '/');

                    // 2. Hapus file dari disk public jika ditemukan
                    if (Storage::disk('public')->exists($relativeStoragePath)) {
                        Storage::disk('public')->delete($relativeStoragePath);
                    } else {
                        // Log alternatif untuk memantau jika path terbaca tapi file tidak ada di folder internal docker
                        Log::warning('File video terdeteksi di database tetapi fisik tidak ditemukan di path: ' . $relativeStoragePath);
                    }
                }
            }

            // 3. Setelah file fisik aman dibersihkan, baru hapus record di database
            $video->delete();

            return redirect()->back()->with('success', 'Data video beserta berkas fisik .webm di server berhasil dihapus selamanya!');
        } catch (Exception $e) {
            $this->rethrowAuthorizationFailures($e);
            Log::error('Gagal menghapus video ID ' . $video->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data video dari sistem.');
        }
    }
}
