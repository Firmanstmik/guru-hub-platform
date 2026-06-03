<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Exception;

class UserManajemenController extends Controller
{
    /**
     * INDEX (Daftar Manajemen Peran Pengguna)
     */
    public function index()
    {
        try {
            // Eager loading relasi roles agar performa kueri optimal dan ringan
            $users = User::with('roles')->latest()->get();
            return view('users_manajemen.index', compact('users'));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman indeks manajemen peran user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat konfigurasi peran pengguna.');
        }
    }

    /**
     * EDIT ROLES FORM
     */
    public function editRoles($id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);
            $roles = Role::orderBy('name', 'asc')->get();
            
            return view('users_manajemen.edit-roles', compact('user', 'roles'));
        } catch (Exception $e) {
            Log::error('Gagal memuat form edit peran untuk user ID ' . $id . ': ' . $e->getMessage());
            return redirect()->route('users-manajemen.index')->with('error', 'Data pengguna tidak ditemukan.');
        }
    }

    /**
     * UPDATE ROLES (Sinkronisasi Peran User)
     */
    public function updateRoles(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $targetRoles = $request->roles ?? [];

            // Proteksi Keamanan: Mencegah admin yang sedang login mencopot role-nya sendiri
            if (Auth::id() === $user->id && !in_array('admin', $targetRoles) && $user->hasRole('admin')) {
                return redirect()->back()->with('error', 'Anda tidak diperbolehkan mencopot peran Admin dari akun Anda sendiri demi menjaga kestabilan akses.');
            }

            // Sinkronisasi peran menggunakan Spatie Permissions
            $user->syncRoles($targetRoles);

            return redirect()->back()->with('success', "Peran akses untuk pengguna '{$user->name}' berhasil diperbarui.");

        } catch (Exception $e) {
            Log::error('Gagal memperbarui peran akses user ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui peran akses pengguna karena kendala internal basis data.');
        }
    }
}