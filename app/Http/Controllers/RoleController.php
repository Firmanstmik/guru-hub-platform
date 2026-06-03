<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Exception;

class RoleController extends Controller
{
    /**
     * INDEX (Daftar Peran / Role Sistem)
     */
    public function index()
    {
        try {
            $roles = Role::withCount('permissions')->orderBy('name', 'asc')->get();
            return view('roles.index', compact('roles'));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman index role: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat konfigurasi peran akses.');
        }
    }

    /**
     * CREATE FORM
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * STORE (Tambah Role Baru)
     */
    public function store(Request $request)
    {
        // Validasi dengan custom pesan Bahasa Indonesia
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ], [
            'name.required' => 'Nama peran (role) wajib diisi.',
            'name.max'      => 'Nama peran terlalu panjang, maksimal 255 karakter.',
            'name.unique'   => 'Nama peran ini sudah terdaftar di dalam sistem.',
        ]);

        try {
            Role::create([
                'name'       => $request->name,
                'guard_name' => 'web' // Memastikan keselarasan guard Spatie
            ]);

            return redirect()->route('roles.index')->with('success', 'Peran (Role) baru berhasil ditambahkan.');

        } catch (Exception $e) {
            Log::error('Gagal menambahkan role baru: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan peran baru karena kendala internal database.');
        }
    }

    /**
     * DESTROY (Hapus Role)
     */
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);

            // Proteksi: Mencegah penghapusan peran krusial sistem secara tidak sengaja
            if (in_array($role->name, ['admin', 'guru', 'siswa'])) {
                return redirect()->back()->with('error', 'Gagal dihapus! Peran inti sistem tidak diperbolehkan untuk dihapus.');
            }

            // Copot keterikatan dengan semua permission terlebih dahulu (Spatie clean up)
            $role->syncPermissions([]);
            $role->delete();

            return redirect()->route('roles.index')->with('success', 'Peran berhasil dihapus dari sistem secara permanen.');

        } catch (Exception $e) {
            Log::error('Gagal menghapus data role ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus peran karena kendala database atau data tidak ditemukan.');
        }
    }

    /**
     * EDIT PERMISSIONS FORM
     */
    public function editPermissions($id)
    {
        try {
            $role = Role::findOrFail($id);
            $permissions = Permission::orderBy('name', 'asc')->get();
            return view('roles.edit-permissions', compact('role', 'permissions'));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman edit permission untuk role ID ' . $id . ': ' . $e->getMessage());
            return redirect()->route('roles.index')->with('error', 'Data peran tidak ditemukan.');
        }
    }

    /**
     * UPDATE PERMISSIONS (Sinkronisasi Hak Akses Ke Role)
     */
    public function updatePermissions(Request $request, $id)
    {
        try {
            $role = Role::findOrFail($id);
            
            // Sinkronisasi permission (jika tidak ada yang dicentang, otomatis mengosongkan hak akses)
            $role->syncPermissions($request->permissions ?? []);

            return redirect()->back()->with('success', "Hak akses (Permissions) untuk peran '{$role->name}' berhasil diperbarui.");

        } catch (Exception $e) {
            Log::error('Gagal memperbarui matriks permission pada role ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui hak akses karena kendala internal sistem.');
        }
    }

    /**
     * VIEW EDIT ROLE FORM
     */
    public function viewEditRoles($id)
    {
        try {
            $role = Role::findOrFail($id);
            return view('roles.edit-roles', compact('role'));
        } catch (Exception $e) {
            Log::error('Gagal memuat formulir edit nama role ID ' . $id . ': ' . $e->getMessage());
            return redirect()->route('roles.index')->with('error', 'Data peran tidak ditemukan.');
        }
    }

    /**
     * POST UPDATE ROLE (Perbarui Nama Role)
     */
    public function PostUpdateRoles(Request $request, $id)
    {
        // Validasi perbaruan dengan custom pesan Bahasa Indonesia
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ], [
            'name.required' => 'Nama peran tidak boleh dikosongkan.',
            'name.max'      => 'Nama peran terlalu panjang, maksimal 255 karakter.',
            'name.unique'   => 'Nama peran sudah digunakan oleh data lain.'
        ]);

        try {
            $role = Role::findOrFail($id);

            // Proteksi: Jangan biarkan nama role bawaan dirubah demi menjaga integritas middleware
            if (in_array($role->name, ['admin', 'guru', 'siswa'])) {
                return redirect()->route('roles.index')->with('error', 'Gagal memperbarui! Nama peran inti aplikasi tidak boleh diubah.');
            }

            $role->name = $request->name;
            $role->save();

            return redirect()->route('roles.index')->with('success', 'Nama peran berhasil diperbarui.');

        } catch (Exception $e) {
            Log::error('Gagal memperbarui nama role ID ' . $id . ': ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui peran karena terjadi kendala pada sistem.');
        }
    }
}