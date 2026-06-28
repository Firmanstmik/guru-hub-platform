<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesCourseOwnership;
use App\Http\Controllers\Concerns\RethrowsAuthorizationFailures;
use App\Http\Controllers\Controller;
use App\Models\CourseMaterial;
use App\Models\Course;
use App\Models\UserProgress;
use App\Support\ProgressMorphType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseMaterialController extends Controller
{
    use AuthorizesCourseOwnership;
    use RethrowsAuthorizationFailures;

    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            // Eager loading data kelas/kursus terkait beserta gurunya
            $query = CourseMaterial::with(['course.teacher']);

            // Saring materi berdasarkan Hak Akses Akun Guru (Menggunakan Spatie)
            if (!$user->hasRole('admin')) {
                // Hanya ambil materi yang kelasnya dibuat oleh Guru yang sedang login
                $query->whereHas('course', function ($q) use ($user) {
                    $q->where('teacher_id', $user->id);
                });
            }

            // Filter berdasarkan kelas/kursus tertentu
            if ($request->has('course_id') && $request->course_id != '') {
                $query->where('course_id', $request->course_id);
            }

            // Fitur Pencarian berdasarkan judul modul/materi
            if ($request->has('search') && $request->search != '') {
                $query->where('title', 'like', "%{$request->search}%");
            }

            $materials = $query->latest()->paginate(10)->withQueryString();

            // Hitung progress untuk setiap materi yang tampil di halaman ini
            $materialIds = $materials->pluck('id')->toArray();

            $progressCounts = UserProgress::where('progressable_type', ProgressMorphType::MATERIAL)
                ->whereIn('progressable_id', $materialIds)
                ->select('progressable_id', DB::raw('count(*) as total'))
                ->groupBy('progressable_id')
                ->pluck('total', 'progressable_id');

            // Masukkan hasil hitungan ke setiap model material
            $materials->each(function ($mat) use ($progressCounts) {
                $mat->completed_count = $progressCounts[$mat->id] ?? 0;
            });

            // Mengambil daftar kelas untuk dropdown filter/modal berdasarkan Role Spatie
            if ($user->hasRole('admin')) {
                $courses = Course::where('status', 'published')->orderBy('title')->get();
            } else {
                $courses = Course::where('status', 'published')
                    ->where('teacher_id', $user->id)
                    ->orderBy('title')
                    ->get();
            }

            // Return view sesuai role user
            if ($user->hasRole('admin')) {
                return view('admin.course-materials', compact('materials', 'courses'));
            } elseif ($user->hasRole('guru')) {
                return view('guru.materials', compact('materials', 'courses'));
            }

            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        } catch (Exception $e) {
            // Mencatat log error internal untuk kebutuhan debugging sistem
            Log::error('Gagal memuat halaman materi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data materi.');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title'     => 'required|string|max:255',
            'file'      => 'required|mimes:pdf,doc,docx,ppt,pptx,zip|max:5120', // Batas 5MB
        ], [
            'file.required' => 'Berkas materi wajib diunggah.',
            'file.mimes'    => 'Format berkas harus berupa pdf, doc, docx, ppt, pptx, atau zip.',
            'file.max'      => 'Ukuran berkas terlalu besar! Maksimal ukuran yang diperbolehkan adalah 5 MB.',
            'course_id.required' => 'Kelas/Kursus wajib dipilih.'
        ]);

        $this->authorizeOwnsCourseId((int) $validated['course_id']);

        $uploadedPath = null;
        try {
            if ($request->hasFile('file')) {
                $uploadedPath = $request->file('file')->store('courses/materials', 'public');
                $validated['file_path'] = $uploadedPath;
            }

            CourseMaterial::create($validated);
            return redirect()->back()->with('success', 'Materi pembelajaran baru berhasil diunggah!');
        } catch (Exception $e) {
            $this->rethrowAuthorizationFailures($e);
            if ($uploadedPath && Storage::disk('public')->exists($uploadedPath)) {
                Storage::disk('public')->delete($uploadedPath);
            }
            Log::error('Gagal mengunggah materi baru: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengunggah materi pembelajaran karena masalah sistem.');
        }
    }

    public function update(Request $request, CourseMaterial $material)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title'     => 'required|string|max:255',
            // PERBAIKAN: Naikkan batasan berkas menjadi 5MB (5120) demi kelancaran update file besar
            'file'      => 'nullable|mimes:pdf,doc,docx,ppt,pptx,zip|max:5120',
        ], [
            'file.mimes'    => 'Format dokumen baru tidak didukung. Gunakan format pdf, doc, docx, ppt, pptx, atau zip.',
            'file.max'      => 'Gagal memperbarui! Ukuran dokumen baru terlalu besar (Maksimal 5 MB).',
            'title.required' => 'Judul materi tidak boleh dikosongkan.',
            'course_id.required' => 'Asosiasi kelas/kursus wajib ditentukan.'
        ]);

        $newUploadedPath = null;
        $oldFilePath = $material->file_path;

        $material->loadMissing('course');
        $this->authorizeOwnsCourse($material->course);
        $this->authorizeOwnsCourseId((int) $validated['course_id']);

        try {
            if ($request->hasFile('file')) {
                // Upload berkas baru terlebih dahulu
                $newUploadedPath = $request->file('file')->store('courses/materials', 'public');
                $validated['file_path'] = $newUploadedPath;
            }

            $material->update($validated);

            // Jika update database sukses dan ada berkas baru, barulah hapus berkas lama dari server
            if ($request->hasFile('file') && $oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
                Storage::disk('public')->delete($oldFilePath);
            }

            return redirect()->back()->with('success', 'Data materi belajar berhasil diperbarui!');
        } catch (Exception $e) {
            $this->rethrowAuthorizationFailures($e);
            if ($newUploadedPath && Storage::disk('public')->exists($newUploadedPath)) {
                Storage::disk('public')->delete($newUploadedPath);
            }

            Log::error('Gagal memperbarui materi ID ' . $material->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data materi pembelajaran karena kendala sistem.');
        }
    }

    public function destroy(CourseMaterial $material)
    {
        $material->loadMissing('course');
        $this->authorizeOwnsCourse($material->course);

        try {
            $filePath = $material->file_path;

            // Hapus rekam data di database terlebih dahulu
            $material->delete();

            // Jika hapus data sukses, barulah hapus fisik berkas dari storage server
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()->back()->with('success', 'Berkas materi berhasil dihapus dari sistem!');
        } catch (Exception $e) {
            $this->rethrowAuthorizationFailures($e);
            Log::error('Gagal menghapus materi ID ' . $material->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus berkas materi dari sistem.');
        }
    }

    public function show($id)
    {
        $material = CourseMaterial::with([
            'course',
            'quiz' => function ($query) {
                $query->withCount('questions');
            },
        ])->findOrFail($id);

        if (!$material->course) {
            abort(404, 'Kelas untuk materi ini tidak ditemukan.');
        }

        $this->authorizeOwnsCourse($material->course);

        return view('guru.material-show', compact('material'));
    }
}
