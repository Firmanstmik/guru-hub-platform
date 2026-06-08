<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Exception;

class UserController extends Controller
{
    /**
     * INDEX (Daftar Pengguna Sistem)
     */
    public function index(Request $request)
    {
        try {
            // Memuat user beserta roles (Spatie) dan profil terkait untuk optimalisasi query (Eager Loading)
            $query = User::with(['roles', 'teacherProfile', 'teacherCourses', 'enrolledCourses']);

            // Filter berdasarkan Role Pengguna
            if ($request->has('role') && $request->role != '') {
                $query->role($request->role);
            }

            // Filter berdasarkan Status Akun (Aktif=1 / Suspended=0)
            if ($request->has('status') && $request->status != '') {
                $status = $request->status === 'active' ? 1 : 0;
                $query->where('is_active', $status);
            }

            // Fitur Pencarian berdasarkan nama, email, atau nomor telepon
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                });
            }

            $users = $query->latest()->paginate(10)->withQueryString();
            $roles = Role::orderBy('name', 'asc')->get(); // Diurutkan agar rapi pada opsi dropdown

            return view('admin.users.index', compact('users', 'roles'));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman manajemen user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data pengguna.');
        }
    }

    /**
     * CREATE FORM
     */
    public function create()
    {
        try {
            $roles = Role::orderBy('name', 'asc')->get();
            return view('admin.users.create', compact('roles'));
        } catch (Exception $e) {
            Log::error('Gagal memuat formulir tambah user: ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Gagal memuat halaman formulir pembuatan pengguna baru.');
        }
    }

    /**
     * STORE (Simpan Pengguna Baru)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:6',
            'phone_number' => 'nullable|string|max:20',
        ], [
            'name.required'         => 'Nama lengkap wajib diisi.',
            'email.required'        => 'Alamat email wajib diisi.',
            'email.email'           => 'Format alamat email tidak valid.',
            'email.unique'          => 'Alamat email ini sudah terdaftar di sistem.',
            'password.required'     => 'Kata sandi (password) wajib ditentukan.',
            'password.min'          => 'Kata sandi minimal harus terdiri dari 6 karakter.',
            'phone_number.max'      => 'Nomor telepon terlalu panjang, maksimal 20 karakter.',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name'         => $validated['name'],
                'email'        => $validated['email'],
                'password'     => Hash::make($validated['password']), // Menggunakan Hash manual jika cast model dinonaktifkan
                'phone_number' => $validated['phone_number'] ?? null,
                'is_active'    => true,
            ]);

            DB::commit();
            return redirect('/users')->with('success', "Akun pengguna {$user->name} berhasil ditambahkan.");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan user baru ke sistem: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memproses pendaftaran pengguna baru karena kendala internal basis data.');
        }
    }

    /**
     * EDIT FORM
     */
    public function edit(User $user)
    {
        try {
            $roles = Role::orderBy('name', 'asc')->get();
            return view('admin.users.edit', compact('user', 'roles'));
        } catch (Exception $e) {
            Log::error('Gagal memuat formulir edit user ID ' . $user->id . ': ' . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Data pengguna tidak dapat ditemukan.');
        }
    }

    /**
     * UPDATE (Perbarui Informasi Pengguna)
     */
    public function update(Request $request, User $user)
    {
        // Validasi pembaruan data dengan custom pesan Bahasa Indonesia
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => "required|email|unique:users,email,{$user->id}",
            'password'     => 'nullable|string|min:6',
            'phone_number' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Nama lengkap pengguna wajib diisi.',
            'email.required' => 'Alamat email tidak boleh dikosongkan.',
            'email.unique'  => 'Alamat email sudah digunakan oleh pengguna lain.',
            'password.min'  => 'Kata sandi baru minimal harus terdiri dari 6 karakter.',
        ]);

        // Proteksi keamanan: Mencegah admin mengubah role-nya sendiri di luar prosedur internal
        if (Auth::id() === $user->id && $user->roles->first()?->name !== $validated['role']) {
            return redirect()->back()->withInput()->with('error', 'Anda tidak diperbolehkan mengubah peran akun Anda sendiri demi alasan keamanan.');
        }

        DB::beginTransaction();
        try {
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->phone_number = $validated['phone_number'] ?? null;

            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            // Sinkronisasi peran Spatie Permissions
            $user->syncRoles([$validated['role']]);

            DB::commit();
            return redirect()->intended('/users')->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui informasi user ID ' . $user->id . ': ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data pengguna karena kendala internal server.');
        }
    }

    /**
     * TOGGLE STATUS (Aktifkan / Tangguhkan Pengguna)
     */
    public function toggleStatus(User $user)
    {
        // Mencegah admin menonaktifkan akunnya sendiri demi keamanan akses
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak diperbolehkan untuk menonaktifkan akun Anda sendiri.');
        }

        try {
            $user->update([
                'is_active' => !$user->is_active
            ]);

            $statusMessage = $user->is_active ? 'diaktifkan kembali' : 'ditangguhkan (suspend)';

            return redirect()->back()->with('success', "Akun pengguna {$user->name} berhasil {$statusMessage}.");
        } catch (Exception $e) {
            Log::error('Gagal melakukan toggle status user ID ' . $user->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal merubah status akun pengguna karena kendala sistem database.');
        }
    }

    /**
     * UPDATE ROLE (Aksi Cepat)
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ], [
            'role.required' => 'Peran akses baru wajib ditentukan.',
            'role.exists'   => 'Peran akses yang dipilih tidak sah.'
        ]);

        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak diperbolehkan mengubah peran akses akun Anda sendiri.');
        }

        try {
            $user->syncRoles([$request->role]);

            return redirect()->back()->with('success', "Peran akses {$user->name} berhasil diperbarui menjadi " . strtoupper($request->role));
        } catch (Exception $e) {
            Log::error('Gagal mengubah hak akses cepat untuk user ID ' . $user->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui peran akses karena kendala internal sistem.');
        }
    }

    /**
     * DESTROY (Hapus Pengguna)
     */
    public function destroy(User $user)
    {
        // Mencegah admin menghapus dirinya sendiri secara tidak sengaja
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak diperbolehkan menghapus akun Anda sendiri dari sistem.');
        }

        DB::beginTransaction();
        try {
            $userName = $user->name;

            // Copot seluruh relasi peran Spatie terlebih dahulu agar tabel pivot bersih
            $user->syncRoles([]);

            $user->delete();

            DB::commit();
            return redirect()->back()->with('success', "Akun pengguna {$userName} telah berhasil dihapus permanen dari sistem.");
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus entitas user ID ' . $user->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus pengguna karena adanya keterikatan relasi data transaksional di dalam database.');
        }
    }
}
