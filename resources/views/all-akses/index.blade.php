@extends('layout.template')

@section('title', 'Hak Akses Pengguna')
@section('header', 'Daftar Hak Akses Pengguna')

@section('content')
<div class="space-y-8">

    {{-- USERS AND ROLES --}}
    <div class="bg-white shadow rounded p-6">
        <h2 class="text-xl font-semibold mb-4">Pengguna & Role</h2>
        <table class="min-w-full bg-white border">
            <thead class="bg-slate-100">
                <tr>
                    <th class="text-left py-2 px-4 border border-slate-200">Nama</th>
                    <th class="text-left py-2 px-4 border border-slate-200">Email</th>
                    <th class="text-left py-2 px-4 border border-slate-200">Roles</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-t">
                    <td class="py-2 px-4 border border-slate-200">{{ $user->name }}</td>
                    <td class="py-2 px-4 border border-slate-200">{{ $user->email }}</td>
                    <td class="py-2 px-4 border border-slate-200">
                        @foreach($user->roles as $role)
                            <span class="inline-block bg-slate-100 text-slate-900 text-xs px-2 py-1 rounded">{{ $role->name }}</span>
                        @endforeach
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- ROLES AND PERMISSIONS --}}
    <div class="bg-white shadow rounded p-6">
        <h2 class="text-xl font-semibold mb-4">Role & Permission</h2>
        <table class="min-w-full bg-white border">
            <thead class="bg-slate-100">
                <tr>
                    <th class="text-left py-2 px-4 border border-slate-200">Role</th>
                    <th class="text-left py-2 px-4 border border-slate-200">Permissions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr class="border-t">
                    <td class="py-2 px-4 border border-slate-200">{{ $role->name }}</td>
                    <td class="py-2 px-4 border border-slate-200">
                        @foreach($role->permissions as $permission)
                            <span class="inline-block bg-slate-100 text-slate-900 text-xs px-2 py-1 rounded">{{ $permission->name }}</span>
                        @endforeach
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PERMISSION LIST --}}
    <div class="bg-white shadow rounded p-6">
        <h2 class="text-xl font-semibold mb-4">Daftar Semua Permission</h2>
        <ul class="list-disc list-inside text-gray-700">
            @foreach($permissions as $permission)
                <li>{{ $permission->name }}</li>
            @endforeach
        </ul>
    </div>

</div>
@endsection
