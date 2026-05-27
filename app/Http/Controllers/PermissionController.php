<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'controller' => 'required|string',
            'uri' => 'required|string',
            'method' => 'required|in:get,post,put,patch,delete',
            'action' => 'required|string',
        ]);

        $validated['guard_name'] = 'web';

        Permission::create($validated);

        return redirect()->route('permissions.index')->with('success', 'Permission ditambahkan.');
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
            'controller' => 'nullable|string',
            'uri' => 'nullable|string',
            'method' => 'nullable|in:get,post,put,patch,delete',
            'action' => 'nullable|string',
        ]);

        $permission->update($validated);

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Permission::findOrFail($id)->delete();
        return back()->with('success', 'Permission dihapus.');
    }
}
