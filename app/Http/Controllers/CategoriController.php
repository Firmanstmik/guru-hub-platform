<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class CategoriController extends Controller
{
    /**
     * INDEX (Admin & Guru)
     */
    public function index()
    {
        try {
            // Mengambil data kategori beserta jumlah kelas di dalamnya (eager loading)
            $categories = Categori::withCount('courses')->latest()->paginate(10);
            
            if (auth()->user()->hasRole('admin')) {
                return view('admin.categories', compact('categories'));
            } elseif (auth()->user()->hasRole('guru')) {
                return view('guru.class-course', compact('categories'));
            } else {
                abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
            }
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman kategori: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data kategori.');
        }
    }

    /**
     * STORE (Tambah Kategori Baru)
     */
    public function store(Request $request)
    {
        // Validasi dengan custom pesan Bahasa Indonesia
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.string'   => 'Nama kategori harus berupa teks.',
            'name.max'      => 'Nama kategori terlalu panjang, maksimal 255 karakter.',
            'name.unique'   => 'Nama kategori ini sudah terdaftar di sistem, gunakan nama lain.'
        ]);

        try {
            $validated['slug'] = Str::slug($validated['name']);

            Categori::create($validated);

            return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan ke sistem!');
        } catch (Exception $e) {
            Log::error('Gagal menambahkan kategori baru: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan kategori baru karena kendala internal sistem.');
        }
    }

    /**
     * UPDATE (Perbarui Data Kategori)
     */
    public function update(Request $request, Categori $category)
    {
        // Validasi dengan custom pesan Bahasa Indonesia
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama kategori tidak boleh dikosongkan.',
            'name.string'   => 'Nama kategori harus berupa teks.',
            'name.max'      => 'Nama kategori maksimal berisi 255 karakter.',
            'name.unique'   => 'Nama kategori sudah digunakan oleh data lain.'
        ]);

        try {
            $validated['slug'] = Str::slug($validated['name']);

            $category->update($validated);

            return redirect()->back()->with('success', 'Data kategori berhasil diperbarui!');
        } catch (Exception $e) {
            Log::error('Gagal memperbarui kategori ID ' . $category->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data kategori karena kendala sistem.');
        }
    }

    /**
     * DESTROY (Hapus Kategori)
     */
    public function destroy(Categori $category)
    {
        try {
            // Proteksi: Jangan hapus jika kategori masih memiliki kelas terikat
            if ($category->courses()->count() > 0) {
                return redirect()->back()->with('error', 'Gagal dihapus! Kategori ini masih digunakan oleh beberapa kelas aktif.');
            }

            $category->delete();

            return redirect()->back()->with('success', 'Kategori berhasil dihapus permanen dari sistem!');
        } catch (Exception $e) {
            Log::error('Gagal menghapus kategori ID ' . $category->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus kategori karena kendala database.');
        }
    }
}