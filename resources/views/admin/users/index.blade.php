@extends('layout.template')
@section('title', 'Daftar Pengguna')
@section('header', 'Akun Pengguna')
@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
        <div>
            <h3 class="text-gray-700 text-3xl font-semibold">Manajemen Pengguna</h3>
            <p class="text-sm text-gray-500 mt-1">Kelola hak akses pengguna, monitor status keaktifan, dan lakukan tindakan pembekuan akun pelanggar.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
            <form action="{{ url('/users') }}" method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, email, atau telepon..." class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                
                <select name="role" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500">
                    <option value="">Semua Peran</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>

                <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </form>

            <a href="{{ url('/users/create') }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm shadow-xs transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Pengguna</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Kontak</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Hak Akses (Role)</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Aktivitas</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status Akun</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatar ? asset('assets/avatar/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" class="w-9 h-9 rounded-full object-cover border border-gray-200" alt="Avatar">
                                <div>
                                    <div class="font-bold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xxs text-gray-400">ID Pengguna: #{{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-gray-800 font-medium">{{ $user->email }}</div>
                            <div class="text-xs text-gray-500 font-mono">{{ $user->phone_number ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form action="{{ url('/users/' . $user->id . '/update-role') }}" method="POST" class="flex items-center gap-1.5">
                                @csrf
                                <select name="role" onchange="this.form.submit()" class="text-xs bg-gray-50 border border-gray-200 rounded px-2 py-1 font-medium text-gray-700 focus:border-indigo-500 focus:bg-white" {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                            @if($user->hasRole('teacher'))
                                <span class="font-semibold text-indigo-600">{{ $user->teacherCourses->count() }}</span> Kelas Dibuat
                            @else
                                <span class="font-semibold text-emerald-600">{{ $user->enrolledCourses->count() }}</span> Kelas Diikuti
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->is_active)
                                <span class="px-2.5 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-800 rounded-full">Aktif</span>
                            @else
                                <span class="px-2.5 py-0.5 text-xs font-semibold bg-rose-100 text-rose-800 rounded-full">Suspended</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                            <div class="flex justify-end items-center gap-3">
                                <a href="{{ url('/users/' . $user->id . '/edit') }}" class="text-amber-600 hover:text-amber-900 font-semibold">Edit</a>
                                
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ url('/users/toggle', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @if($user->is_active)
                                            <button type="submit" class="text-rose-600 hover:text-rose-900 font-semibold">Suspend</button>
                                        @else
                                            <button type="submit" class="text-emerald-600 hover:text-emerald-900 font-semibold">Aktifkan</button>
                                        @endif
                                    </form>

                                    <form action="{{ url('/users/'. $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-rose-600 font-semibold">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-xxs text-gray-400 italic">Akun Anda</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">Data pengguna tidak ditemukan atau filter tidak cocok.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection