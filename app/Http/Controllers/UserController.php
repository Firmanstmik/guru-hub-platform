<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // tampil data user
    public function index(Request $request)
    {
        // Memuat user beserta roles (Spatie) dan profil guru untuk optimalisasi query (Eager Loading)
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
        $roles = Role::all(); // Diperlukan untuk pilihan dropdown filter & form modal

        return view('admin.users.index', compact('users', 'roles'));
    }

    // tampil form create user
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    // simpan data user baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:6',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'password'     => Hash::make($validated['password']),
            'phone_number' => $validated['phone_number'] ?? null,
            'is_active'    => true,
        ]);

        return redirect()->intended('/users')->with('success', 'User berhasil ditambahkan.');
    }

    // tampil form edit user
    public function edit(User $user)
    {
        // Menggunakan Route Model Binding (User $user) agar lebih aman dan clean dibanding $id
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // update data user
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => "required|email|unique:users,email,{$user->id}",
            'password'     => 'nullable|string|min:6',
            'phone_number' => 'nullable|string|max:20',
            'role'         => 'required|exists:roles,name',
        ]);

        // Proteksi kemanan: Mencegah admin mengubah role-nya sendiri di luar prosedur internal
        if (Auth::user()->id === $user->id && $user->roles->first()?->name !== $validated['role']) {
            return redirect()->back()->with('error', 'Anda tidak diperbolehkan mengubah peran akun Anda sendiri demi alasan keamanan.');
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone_number = $validated['phone_number'] ?? null;

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Sinkronisasi role
        $user->syncRoles([$validated['role']]);

        return redirect()->intended('/users')->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    // Mengubah status aktif/blokir akun pengguna (Toggle Suspend).
    public function toggleStatus(User $user)
    {
        // Mencegah admin menonaktifkan akunnya sendiri demi keamanan akses
        if (Auth::user()->id === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $statusMessage = $user->is_active ? 'diaktifkan kembali' : 'ditangguhkan (suspend)';
        
        return redirect()->back()->with('success', "Akun pengguna {$user->name} berhasil {$statusMessage}.");
    }

    // update role user
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        if (Auth::user()->id === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah peran akun Anda sendiri.');
        }

        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', "Peran akses {$user->name} berhasil diperbarui menjadi " . strtoupper($request->role));
    }

    // hapus user
    public function destroy(User $user)
    {
        // Mencegah admin menghapus dirinya sendiri secara tidak sengaja
        if (Auth::user()->id === $user->id) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->back()->with('success', "Akun pengguna {$userName} telah berhasil dihapus permanen dari sistem.");
    }
}