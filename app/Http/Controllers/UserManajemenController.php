<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserManajemenController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('users_manajemen.index', compact('users'));
    }

    public function editRoles($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('users_manajemen.edit-roles', compact('user', 'roles'));
    }

    public function updateRoles(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->syncRoles($request->roles ?? []);
        return back()->with('success', 'Role user diperbarui.');
    }
}
