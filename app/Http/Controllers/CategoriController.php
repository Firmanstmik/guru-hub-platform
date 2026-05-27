<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriController extends Controller
{
    public function index()
    {
        // Mengambil data kategori beserta jumlah kelas di dalamnya (eager loading)
        $categories = Categori::withCount('courses')->latest()->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Categori::create($validated);

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, Categori $Categori)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $Categori->id,
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $Categori->update($validated);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Categori $Categori)
    {
        // Proteksi: Jangan hapus jika kategori masih memiliki kelas terikat
        if ($Categori->courses()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal dihapus! Kategori ini masih digunakan oleh beberapa kelas.');
        }

        $Categori->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }
}