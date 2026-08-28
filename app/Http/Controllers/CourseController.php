<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesCourseOwnership;
use App\Http\Controllers\Controller;
use App\Models\Categori;
use App\Models\Course;
use App\Models\User;
use App\Support\CourseCatalog;
use App\Support\UploadedImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class CourseController extends Controller
{
    use AuthorizesCourseOwnership;

    /**
     * INDEX (Admin & Guru)
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user(); 

            // Eager loading relasi utama dan menghitung total siswa terdaftar
            $query = Course::with(['teacher', 'category', 'subject', 'educationLevel'])->withCount('students');

            // Spatie: Jika bukan admin (berarti guru), batasi data hanya miliknya sendiri
            if (!$user->hasRole('admin')) {
                $query->where('teacher_id', $user->id);
            }

            // Filter berdasarkan Kategori
            if ($request->has('category_id') && $request->category_id != '') {
                $query->where('category_id', $request->category_id);
            }

            if ($request->has('subject_id') && $request->subject_id != '') {
                $query->where('subject_id', $request->subject_id);
            }

            // Filter berdasarkan Status Kelas
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            $courses = $query->latest()->paginate(10)->withQueryString();

            // Data pendukung untuk modal Tambah/Edit Kelas
            $categories = Categori::orderBy('name')->get();
            $subjects = CourseCatalog::subjectsFor($user);
            $canCreateCourse = $subjects->isNotEmpty();

            // Spatie: Efisiensi dropdown select guru di modal
            if ($user->hasRole('admin')) {
                $teachers = User::role('guru')->where('is_active', true)->orderBy('name')->get();
            } else {
                $teachers = User::where('id', $user->id)->get();
            }

            if ($user->hasRole('admin')) {
                return view('admin.courses', compact('courses', 'categories', 'teachers', 'subjects', 'canCreateCourse'));
            } elseif ($user->hasRole('guru')) {
                return view('guru.courses', compact('courses', 'categories', 'teachers', 'subjects', 'canCreateCourse'));
            } else {
                abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
            }

        } catch (Exception $e) {
            Log::error('Gagal memuat halaman kelas: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data kelas.');
        }
    }

    /**
     * STORE (Tambah Kelas Baru)
     */
    public function store(Request $request)
    {
        if ($uploadError = UploadedImage::failedUploadMessage($request, 'cover_image')) {
            return redirect()->back()->withInput()->withErrors(['cover_image' => $uploadError]);
        }

        // Validasi dengan custom pesan Bahasa Indonesia
        $validated = $request->validate([
            'teacher_id'  => 'required|exists:users,id',
            'subject_id'  => 'required|exists:subjects,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:draft,published,archived',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:'.UploadedImage::MAX_KB,
        ], array_merge([
            'teacher_id.required'  => 'Pengajar wajib dipilih.',
            'teacher_id.exists'    => 'Pengajar tidak valid atau tidak terdaftar di sistem.',
            'subject_id.required' => 'Mata pelajaran & jenjang wajib dipilih.',
            'subject_id.exists'   => 'Mata pelajaran yang dipilih tidak valid.',
            'title.required'       => 'Judul kelas tidak boleh dikosongkan.',
            'title.max'            => 'Judul kelas terlalu panjang, maksimal 255 karakter.',
            'description.required' => 'Deskripsi kelas wajib diisi.',
            'price.required'       => 'Harga kelas wajib ditentukan.',
            'price.numeric'        => 'Harga kelas harus berupa angka.',
            'price.min'            => 'Harga kelas tidak boleh kurang dari 0.',
            'status.required'      => 'Status publikasi kelas wajib dipilih.',
            'status.in'            => 'Status yang dipilih tidak sesuai ketentuan.',
        ], UploadedImage::validationMessages()));

        $uploadedPath = null;

        try {
            $actor = Auth::user();

            if ($actor->hasRole('guru') && ! $actor->hasRole('admin')) {
                $validated['teacher_id'] = $actor->id;
            }

            if ($request->hasFile('cover_image')) {
                $uploadedPath = UploadedImage::storeOnPublicDisk(
                    $request->file('cover_image'),
                    'courses/covers'
                );
                $validated['cover_image'] = $uploadedPath;
            }

            $subject = CourseCatalog::assertTeacherMayUseSubject($actor, (int) $validated['subject_id']);
            $validated['category_id'] = $subject->category_id;
            $validated['education_level_id'] = $subject->education_level_id;

            Course::create($validated);

            return redirect()->back()->with('success', 'Kelas baru berhasil ditambahkan oleh sistem!');

        } catch (Exception $e) {
            // Rollback gambar fisik jika kueri ke database gagal
            if ($uploadedPath && Storage::disk('public')->exists($uploadedPath)) {
                Storage::disk('public')->delete($uploadedPath);
            }

            Log::error('Gagal menambahkan kelas baru: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan kelas baru karena kendala internal sistem.');
        }
    }

    /**
     * UPDATE (Perbarui Kelas)
     */
    public function update(Request $request, Course $course)
    {
        if ($uploadError = UploadedImage::failedUploadMessage($request, 'cover_image')) {
            return redirect()->back()->withInput()->withErrors(['cover_image' => $uploadError]);
        }

        // Validasi dengan custom pesan Bahasa Indonesia
        $validated = $request->validate([
            'teacher_id'  => 'required|exists:users,id',
            'subject_id'  => 'required|exists:subjects,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:draft,published,archived',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:'.UploadedImage::MAX_KB,
        ], array_merge([
            'teacher_id.required'  => 'Pengajar wajib ditentukan.',
            'subject_id.required' => 'Mata pelajaran & jenjang wajib ditentukan.',
            'title.required'       => 'Judul kelas tidak boleh kosong.',
            'description.required' => 'Deskripsi kelas tidak boleh kosong.',
            'price.required'       => 'Harga kelas wajib diisi.',
            'price.numeric'        => 'Harga harus berupa nominal angka.',
            'price.min'            => 'Harga kelas tidak boleh minus.',
            'status.required'      => 'Status kelas tidak boleh kosong.',
        ], UploadedImage::validationMessages('gambar sampul baru')));

        $newUploadedPath = null;
        $oldFilePath = $course->cover_image;

        $this->authorizeOwnsCourse($course);

        try {
            $actor = Auth::user();

            if ($actor->hasRole('guru') && ! $actor->hasRole('admin')) {
                $validated['teacher_id'] = $course->teacher_id;
            }

            if ($request->hasFile('cover_image')) {
                $newUploadedPath = UploadedImage::storeOnPublicDisk(
                    $request->file('cover_image'),
                    'courses/covers'
                );
                $validated['cover_image'] = $newUploadedPath;
            }

            $subject = CourseCatalog::assertTeacherMayUseSubject($actor, (int) $validated['subject_id']);
            $validated['category_id'] = $subject->category_id;
            $validated['education_level_id'] = $subject->education_level_id;

            $course->update($validated);

            // Jika update database sukses dan ada berkas baru, barulah hapus berkas lama dari server
            if ($request->hasFile('cover_image') && $oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
                Storage::disk('public')->delete($oldFilePath);
            }

            return redirect()->back()->with('success', 'Informasi kelas berhasil diperbarui!');

        } catch (Exception $e) {
            // Rollback gambar baru jika update rekam database gagal
            if ($newUploadedPath && Storage::disk('public')->exists($newUploadedPath)) {
                Storage::disk('public')->delete($newUploadedPath);
            }

            Log::error('Gagal memperbarui kelas ID ' . $course->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui informasi kelas karena kendala sistem.');
        }
    }

    /**
     * DESTROY (Hapus Kelas)
     */
    public function destroy(Course $course)
    {
        $this->authorizeOwnsCourse($course);

        try {
            // Proteksi: Jika sudah ada siswa aktif di dalam kelas ini, batalkan proses hapus demi integritas data
            if ($course->students()->count() > 0) {
                return redirect()->back()->with('error', 'Gagal menghapus! Kelas ini sudah memiliki murid terdaftar.');
            }

            $filePath = $course->cover_image;

            // Hapus rekam data di database terlebih dahulu
            $course->delete();

            // Jika hapus data sukses, barulah hapus fisik gambar dari storage server
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()->back()->with('success', 'Kelas berhasil dihapus permanen dari sistem!');

        } catch (Exception $e) {
            Log::error('Gagal menghapus kelas ID ' . $course->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus kelas dari sistem karena kendala database.');
        }
    }

    /**
     * Publikasikan kelas draft (admin dashboard).
     */
    public function publish(Course $course)
    {
        try {
            if ($course->status === 'published') {
                return redirect()->back()->with('success', 'Kelas sudah berstatus published.');
            }

            $course->update(['status' => 'published']);

            return redirect()->back()->with('success', 'Kelas berhasil dipublikasikan!');
        } catch (Exception $e) {
            Log::error('Gagal mempublikasikan kelas ID ' . $course->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mempublikasikan kelas.');
        }
    }
}