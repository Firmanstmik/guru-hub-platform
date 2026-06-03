<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Exception;

class AksesController extends Controller
{
    /**
     * INDEX (Manajemen Hak Akses & Matriks Peran)
     */
    public function index()
    {
        try {
            // Eager loading relasi peran pada user untuk optimasi kueri
            $users = User::with('roles')->latest()->get();
            
            // Eager loading relasi permission pada setiap role agar hemat kueri (bebas dari masalah N+1)
            $roles = Role::with('permissions')->orderBy('name')->get();
            
            // Mengambil semua daftar permission untuk kebutuhan matriks hak akses
            $permissions = Permission::orderBy('name')->get();

            return view('all-akses.index', compact('users', 'roles', 'permissions'));

        } catch (Exception $e) {
            // Catat galat sistem secara detail pada log internal server
            Log::error('Gagal memuat halaman manajemen hak akses: ' . $e->getMessage());
            
            // Alihkan user secara aman dengan pemberitahuan session flash bahasa Indonesia
            return redirect()->back()->with('error', 'Terjadi kesalahan internal sistem saat memuat konfigurasi hak akses.');
        }
    }
}