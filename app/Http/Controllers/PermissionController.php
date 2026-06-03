<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Exception;

class PermissionController extends Controller
{
    /**
     * INDEX (Daftar Hak Akses Sistem)
     */
    public function index()
    {
        try {
            $permissions = Permission::orderBy('name', 'asc')->get();
            return view('permissions.index', compact('permissions'));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman index permission: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat konfigurasi hak akses.');
        }
    }

    /**
     * CREATE FORM
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * STORE (Tambah Data Permission Baru)
     */
    public function store(Request $request)
    {
        // Validasi dengan custom pesan Bahasa Indonesia
        $validated = $request->validate([
            'name'       => 'required|string|max:255|unique:permissions,name',
            'controller' => 'required|string|max:255',
            'uri'        => 'required|string|max:255',
            'method'     => 'required|in:get,post,put,patch,delete',
            'action'     => 'required|string|max:255',
        ], [
            'name.required'       => 'Nama hak akses (permission) wajib diisi.',
            'name.unique'         => 'Nama hak akses ini sudah terdaftar di sistem.',
            'controller.required' => 'Nama Controller wajib ditentukan.',
            'uri.required'        => 'Jalur URI rute wajib diisi.',
            'method.required'     => 'Metode HTTP (Method) wajib dipilih.',
            'method.in'           => 'Metode HTTP yang Anda pilih tidak valid.',
            'action.required'     => 'Aksi fungsi (Action) di dalam controller wajib ditentukan.',
        ]);

        try {
            $validated['guard_name'] = 'web';

            Permission::create($validated);

            return redirect()->route('permissions.index')->with('success', 'Hak akses (Permission) baru berhasil ditambahkan.');

        } catch (Exception $e) {
            Log::error('Gagal menambahkan permission baru: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan hak akses baru karena kendala internal database.');
        }
    }

    /**
     * EDIT FORM
     */
    public function edit($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            return view('permissions.edit', compact('permission'));
        } catch (Exception $e) {
            Log::error('Gagal memuat formulir edit permission ID ' . $id . ': ' . $e->getMessage());
            return redirect()->route('permissions.index')->with('error', 'Data hak akses tidak ditemukan atau telah dihapus.');
        }
    }

    /**
     * UPDATE (Perbarui Data Permission)
     */
    public function update(Request $request, $id)
    {
        // Validasi perbaruan dengan custom pesan Bahasa Indonesia
        $validated = $request->validate([
            'name'       => 'required|string|max:255|unique:permissions,name,' . $id,
            'controller' => 'nullable|string|max:255',
            'uri'        => 'nullable|string|max:255',
            'method'     => 'nullable|in:get,post,put,patch,delete',
            'action'     => 'nullable|string|max:255',
        ], [
            'name.required' => 'Nama hak akses tidak boleh dikosongkan.',
            'name.unique'   => 'Nama hak akses sudah digunakan oleh data lain.',
            'method.in'     => 'Pilihan metode HTTP baru tidak valid.'
        ]);

        try {
            $permission = Permission::findOrFail($id);
            $permission->update($validated);

            return redirect()->route('permissions.index')->with('success', 'Konfigurasi hak akses berhasil diperbarui.');

        } catch (Exception $e) {
            Log::error('Gagal memperbarui data permission ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui hak akses karena terjadi kendala pada sistem.');
        }
    }

    /**
     * DESTROY (Hapus Permission)
     */
    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            
            // Pengaman bawaan Spatie: Pastikan mencopot keterikatan role terlebih dahulu
            $permission->roles()->detach();

            $permission->delete();

            return redirect()->route('permissions.index')->with('success', 'Hak akses berhasil dihapus dari sistem secara permanen.');

        } catch (Exception $e) {
            Log::error('Gagal menghapus data permission ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus hak akses karena kendala database atau masih terikat komponen sistem.');
        }
    }
}