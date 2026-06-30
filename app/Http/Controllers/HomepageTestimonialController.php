<?php

namespace App\Http\Controllers;

use App\Models\HomepageTestimonial;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomepageTestimonialController extends Controller
{
    public function index()
    {
        try {
            $testimonials = HomepageTestimonial::ordered()->paginate(12);

            return view('admin.homepage-testimonials', compact('testimonials'));
        } catch (Exception $e) {
            Log::error('Gagal memuat testimoni beranda: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat testimoni beranda.');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'role_title' => ['required', 'string', 'max:180'],
            'quote' => ['required', 'string', 'max:1000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'gradient_from' => ['nullable', 'string', 'max:20'],
            'gradient_to' => ['nullable', 'string', 'max:20'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'role_title.required' => 'Peran/jabatan wajib diisi.',
            'quote.required' => 'Kutipan testimoni wajib diisi.',
        ]);

        try {
            HomepageTestimonial::create([
                'name' => $validated['name'],
                'role_title' => $validated['role_title'],
                'quote' => $validated['quote'],
                'rating' => $validated['rating'],
                'gradient_from' => $validated['gradient_from'] ?? '#14B8A6',
                'gradient_to' => $validated['gradient_to'] ?? '#0E7490',
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_active' => $request->boolean('is_active', true),
            ]);

            return redirect()->back()->with('success', 'Testimoni beranda berhasil ditambahkan.');
        } catch (Exception $e) {
            Log::error('Gagal menambah testimoni beranda: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan testimoni beranda.');
        }
    }

    public function update(Request $request, HomepageTestimonial $homepageTestimonial)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'role_title' => ['required', 'string', 'max:180'],
            'quote' => ['required', 'string', 'max:1000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'gradient_from' => ['nullable', 'string', 'max:20'],
            'gradient_to' => ['nullable', 'string', 'max:20'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        try {
            $homepageTestimonial->update([
                'name' => $validated['name'],
                'role_title' => $validated['role_title'],
                'quote' => $validated['quote'],
                'rating' => $validated['rating'],
                'gradient_from' => $validated['gradient_from'] ?? '#14B8A6',
                'gradient_to' => $validated['gradient_to'] ?? '#0E7490',
                'sort_order' => $validated['sort_order'] ?? 0,
                'is_active' => $request->boolean('is_active'),
            ]);

            return redirect()->back()->with('success', 'Testimoni beranda berhasil diperbarui.');
        } catch (Exception $e) {
            Log::error('Gagal memperbarui testimoni beranda: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui testimoni beranda.');
        }
    }

    public function destroy(HomepageTestimonial $homepageTestimonial)
    {
        try {
            $homepageTestimonial->delete();

            return redirect()->back()->with('success', 'Testimoni beranda berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('Gagal menghapus testimoni beranda: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus testimoni beranda.');
        }
    }
}
