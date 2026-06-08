@extends('layout.template')

@section('title', 'Hak Akses Pengguna')
@section('header', 'Daftar Hak Akses Pengguna')

@section('content')
    <div class="space-y-8 max-w-7xl mx-auto py-2 px-1">

        {{-- SECTION 1: USERS AND ROLES --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            {{-- Card Header --}}
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <span class="w-2 h-5 bg-indigo-600 rounded-full"></span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Pemetaan Pengguna & Peran (Role)</h2>
            </div>

            {{-- Table Responsive Wrapper --}}
            <div class="w-full overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm whitespace-nowrap">
                    <thead class="bg-slate-50/70">
                        <tr>
                            <th scope="col" class="text-left font-semibold text-slate-700 py-3.5 px-6 tracking-wider">Nama
                            </th>
                            <th scope="col" class="text-left font-semibold text-slate-700 py-3.5 px-6 tracking-wider">
                                Email Akun</th>
                            <th scope="col" class="text-left font-semibold text-slate-700 py-3.5 px-6 tracking-wider">
                                Roles Aktif</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($users as $user)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-6 font-medium text-slate-900">{{ $user->name }}</td>
                                <td class="py-3.5 px-6 text-slate-500 font-mono text-xs">{{ $user->email }}</td>
                                <td class="py-3.5 px-6">
                                    <div class="flex flex-wrap gap-1.5">
                                        @forelse($user->roles as $role)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100/50">
                                                {{ $role->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-slate-400 italic">Tidak ada role</span>
                                        @endforelse
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- SECTION 2: ROLES AND PERMISSIONS --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            {{-- Card Header --}}
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <span class="w-2 h-5 bg-amber-500 rounded-full"></span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Matriks Peran (Role) & Hak Akses</h2>
            </div>

            {{-- Table Responsive Wrapper --}}
            <div class="w-full overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm whitespace-nowrap">
                    <thead class="bg-slate-50/70">
                        <tr>
                            <th scope="col"
                                class="text-left font-semibold text-slate-700 py-3.5 px-6 tracking-wider w-48">Role</th>
                            <th scope="col" class="text-left font-semibold text-slate-700 py-3.5 px-6 tracking-wider">
                                Daftar Capabilities / Permissions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @foreach ($roles as $role)
                            <tr class="hover:bg-slate-50/50 transition items-start">
                                <td class="py-4 px-6 font-bold text-slate-800">
                                    <span
                                        class="inline-block px-3 py-1 bg-slate-100 text-slate-800 rounded-lg text-xs tracking-wide">
                                        {{ $role->name }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 whitespace-normal"> {{-- Mengizinkan badge membungkus turun ke bawah jika penuh --}}
                                    <div class="flex flex-wrap gap-1.5 max-w-4xl">
                                        @forelse($role->permissions as $permission)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-mono bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                {{ $permission->name }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-slate-400 italic">Belum ada hak akses terikat</span>
                                        @endforelse
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- SECTION 3: ALL PERMISSION LIST (GRID DESIGN) --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            {{-- Card Header --}}
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <span class="w-2 h-5 bg-emerald-500 rounded-full"></span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Katalog Semua Hak Akses Sistem</h2>
            </div>

            {{-- Body Content Wrapper --}}
            <div class="p-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                    @foreach ($permissions as $permission)
                        <div
                            class="bg-slate-50/80 border border-slate-100 rounded-xl p-3 flex items-center gap-2 hover:bg-slate-50 hover:border-slate-200 transition">
                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                            <span class="font-mono text-xs text-slate-600 font-medium truncate"
                                title="{{ $permission->name }}">
                                {{ $permission->name }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
@endsection
