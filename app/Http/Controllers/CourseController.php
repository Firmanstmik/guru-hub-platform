<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesCourseOwnership;
use App\Http\Controllers\Controller;
use App\Models\Categori; // Tetap menggunakan model Categori sesuai dengan relasi index Anda
use App\Models\Course;
use App\Models\User;
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
            $query = Course::with(['teacher', 'category'])->withCount('students');

            // Spatie: Jika bukan admin (berarti guru), batasi data hanya miliknya sendiri
            if (!$user->hasRole('admin')) {
                $query->where('teacher_id', $user->id);
            }

            // Filter berdasarkan Kategori
            if ($request->has('category_id') && $request->category_id != '') {
                $query->where('category_id', $request->category_id);
            }

            // Filter berdasarkan Status Kelas
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            $courses = $query->latest()->paginate(10)->withQueryString();

            // Data pendukung untuk modal Tambah/Edit Kelas
            $categories = Categori::orderBy('name')->get();

            // Spatie: Efisiensi dropdown select guru di modal
            if ($user->hasRole('admin')) {
                $teachers = User::role('guru')->where('is_active', true)->orderBy('name')->get();
            } else {
                $teachers = User::where('id', $user->id)->get();
            }

            if ($user->hasRole('admin')) {
                return view('admin.courses', compact('courses', 'categories', 'teachers'));
            } elseif ($user->hasRole('guru')) {
                return view('guru.courses', compact('courses', 'categories', 'teachers'));
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
        // Validasi dengan custom pesan Bahasa Indonesia
        $validated = $request->validate([
            'teacher_id'  => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:draft,published,archived',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Batas aman 5MB sesuai konfigurasi Docker
        ], [
            'teacher_id.required'  => 'Pengajar wajib dipilih.',
            'teacher_id.exists'    => 'Pengajar tidak valid atau tidak terdaftar di sistem.',
            'category_id.required' => 'Kategori kelas wajib dipilih.',
            'category_id.exists'   => 'Kategori yang dipilih tidak valid.',
            'title.required'       => 'Judul kelas tidak boleh dikosongkan.',
            'title.max'            => 'Judul kelas terlalu panjang, maksimal 255 karakter.',
            'description.required' => 'Deskripsi kelas wajib diisi.',
            'price.required'       => 'Harga kelas wajib ditentukan.',
            'price.numeric'        => 'Harga kelas harus berupa angka.',
            'price.min'            => 'Harga kelas tidak boleh kurang dari 0.',
            'status.required'      => 'Status publikasi kelas wajib dipilih.',
            'status.in'            => 'Status yang dipilih tidak sesuai ketentuan.',
            'cover_image.image'    => 'Berkas sampul harus berupa gambar.',
            'cover_image.mimes'    => 'Format gambar sampul harus berupa jpeg, png, jpg, atau webp.',
            'cover_image.max'      => 'Gagal mengunggah! Ukuran gambar sampul terlalu besar (Maksimal 5 MB).'
        ]);

        $uploadedPath = null;

        try {
            if (Auth::user()->hasRole('guru') && !Auth::user()->hasRole('admin')) {
                $validated['teacher_id'] = Auth::id();
            }

            if ($request->hasFile('cover_image')) {
                $uploadedPath = $request->file('cover_image')->store('courses/covers', 'public');
                $validated['cover_image'] = $uploadedPath;
            }

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
        // Validasi dengan custom pesan Bahasa Indonesia
        $validated = $request->validate([
            'teacher_id'  => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:draft,published,archived',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Batas aman 5MB sesuai konfigurasi Docker
        ], [
            'teacher_id.required'  => 'Pengajar wajib ditentukan.',
            'category_id.required' => 'Kategori kelas wajib ditentukan.',
            'title.required'       => 'Judul kelas tidak boleh kosong.',
            'description.required' => 'Deskripsi kelas tidak boleh kosong.',
            'price.required'       => 'Harga kelas wajib diisi.',
            'price.numeric'        => 'Harga harus berupa nominal angka.',
            'price.min'            => 'Harga kelas tidak boleh minus.',
            'status.required'      => 'Status kelas tidak boleh kosong.',
            'cover_image.image'    => 'Berkas harus berupa gambar valid.',
            'cover_image.mimes'    => 'Format gambar baru harus berupa jpeg, png, jpg, atau webp.',
            'cover_image.max'      => 'Gagal memperbarui! Ukuran gambar sampul baru terlalu besar (Maksimal 5 MB).'
        ]);

        $newUploadedPath = null;
        $oldFilePath = $course->cover_image;

        $this->authorizeOwnsCourse($course);

        try {
            if ($request->hasFile('cover_image')) {
                // Simpan berkas baru terlebih dahulu
                $newUploadedPath = $request->file('cover_image')->store('courses/covers', 'public');
                $validated['cover_image'] = $newUploadedPath;
            }

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
}