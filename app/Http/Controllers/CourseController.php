<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categori;
use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        // Eager loading relasi utama dan menghitung total siswa terdaftar
        $query = Course::with(['teacher', 'category'])->withCount('students');

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
        $teachers = User::role('guru')->where('is_active', true)->orderBy('name')->get();

        return view('admin.courses', compact('courses', 'categories', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id'  => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:draft,published,archived',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('courses/covers', 'public');
        }

        Course::create($validated);

        return redirect()->back()->with('success', 'Kelas baru berhasil ditambahkan oleh sistem!');
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'teacher_id'  => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:draft,published,archived',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            // Hapus cover lama jika ada berkas baru yang diunggah
            if ($course->cover_image && Storage::disk('public')->exists($course->cover_image)) {
                Storage::disk('public')->delete($course->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('courses/covers', 'public');
        }

        $course->update($validated);

        return redirect()->back()->with('success', 'Informasi kelas berhasil diperbarui!');
    }

    public function destroy(Course $course)
    {
        // Proteksi: Jika sudah ada siswa aktif di dalam kelas ini, batalkan proses hapus demi integritas data
        if ($course->students()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal menghapus! Kelas ini sudah memiliki murid terdaftar.');
        }

        // Hapus file gambar cover dari direktori storage
        if ($course->cover_image && Storage::disk('public')->exists($course->cover_image)) {
            Storage::disk('public')->delete($course->cover_image);
        }

        $course->delete();

        return redirect()->back()->with('success', 'Kelas berhasil dihapus permanen dari sistem!');
    }
}
