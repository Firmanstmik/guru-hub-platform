<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\CourseMaterial;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseMaterialController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); 

        // Eager loading data kelas/kursus terkait beserta gurunya
        $query = CourseMaterial::with(['course.teacher']);

        // PERBAIKAN UTAMA: Saring materi berdasarkan Hak Akses Akun Guru
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
        
        // PERBAIKAN DROPDOWN MODAL TAMBAH: 
        // Guru tidak boleh memasukkan materi ke kelas milik Guru lain.
        if ($user->hasRole('admin')) {
            $courses = Course::where('status', 'published')->orderBy('title')->get();
        } else {
            $courses = Course::where('status', 'published')
                             ->where('teacher_id', $user->id)
                             ->orderBy('title')
                             ->get();
        }

        return view('admin.course-materials', compact('materials', 'courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title'     => 'required|string|max:255',
            'file'      => 'required|mimes:pdf,doc,docx,ppt,pptx,zip|max:5120', // Maksimal berkas 5MB
        ]);

        if ($request->hasFile('file')) {
            // Berkas diunggah ke folder penyimpanan private/public terproteksi
            $path = $request->file('file')->store('courses/materials', 'public');
            $validated['file_path'] = $path;
        }

        CourseMaterial::create($validated);

        return redirect()->back()->with('success', 'Materi pembelajaran baru berhasil diunggah!');
    }

    public function update(Request $request, CourseMaterial $material)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title'     => 'required|string|max:255',
            'file'      => 'nullable|mimes:pdf,doc,docx,ppt,pptx,zip|max:5120',
        ]);

        if ($request->hasFile('file')) {
            // Hapus dokumen lama dari storage server jika diganti berkas baru
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('courses/materials', 'public');
        }

        $material->update($validated);

        return redirect()->back()->with('success', 'Data materi belajar berhasil diperbarui!');
    }

    public function destroy(CourseMaterial $material)
    {
        // Hapus berkas fisik dari storage sebelum menghapus baris rekam database
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()->back()->with('success', 'Berkas materi berhasil dihapus dari sistem!');
    }
}
