@extends('layout.template')
@section('title', 'Daftar Pengguna')
@section('header', 'Akun Pengguna')
@section('content')
    <div class="max-w-[1400px] mx-auto space-y-6">
        {{-- Header --}}
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h3 class="text-2xl font-bold tracking-tight text-gray-900">Manajemen Pengguna</h3>
                <p class="mt-1 text-sm text-gray-500 max-w-xl">
                    Kelola hak akses, status keaktifan, dan tindakan pembekuan akun pelanggar.
                </p>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:items-center">
                <form action="{{ url('/users') }}" method="GET" class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, email, telepon…"
                        class="w-full sm:w-52 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">

                    <select name="role" onchange="this.form.submit()"
                        class="w-full sm:w-36 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none">
                        <option value="">Semua Peran</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>

                    <select name="status" onchange="this.form.submit()"
                        class="w-full sm:w-36 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:border-indigo-500 focus:outline-none">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </form>

                <a href="{{ url('/users/create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pengguna
                </a>
            </div>
        </div>

        {{-- Summary chips --}}
        <div class="flex flex-wrap gap-2 text-xs">
            <span class="rounded-full bg-white border border-gray-200 px-3 py-1 text-gray-600">
                Total halaman ini: <strong class="text-gray-900">{{ $users->count() }}</strong>
            </span>
            @if (request('search') || request('role') || request('status'))
                <a href="{{ url('/users') }}" class="rounded-full bg-indigo-50 border border-indigo-100 px-3 py-1 font-medium text-indigo-700 hover:bg-indigo-100">
                    Reset filter ×
                </a>
            @endif
        </div>

        @forelse($users as $user)
            @php
                $roleName = $user->roles->first()?->name ?? '—';
                $isGuru = $user->hasRole('guru');
                $activityCount = $isGuru ? $user->teacherCourses->count() : $user->enrolledCourses->count();
                $activityLabel = $isGuru ? 'Kelas dibuat' : 'Kelas diikuti';
            @endphp

            {{-- Mobile / tablet card --}}
            <article class="lg:hidden rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex gap-3">
                    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
                        class="h-11 w-11 shrink-0 rounded-full object-cover ring-2 ring-gray-100">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <div class="min-w-0">
                                <h4 class="truncate font-semibold text-gray-900">{{ $user->name }}</h4>
                                <p class="truncate text-sm text-gray-600">{{ $user->email }}</p>
                                <p class="text-xs text-gray-400">ID #{{ $user->id }} · {{ $user->phone_number ?? '—' }}</p>
                            </div>
                            @if ($user->is_active)
                                <span class="shrink-0 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Aktif</span>
                            @else
                                <span class="shrink-0 rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-800">Suspended</span>
                            @endif
                        </div>

                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <form action="{{ url('/users/' . $user->id . '/update-role') }}" method="POST">
                                @csrf
                                <select name="role" onchange="this.form.submit()"
                                    class="rounded-lg border border-gray-200 bg-gray-50 px-2.5 py-1.5 text-xs font-medium text-gray-700 focus:border-indigo-500 focus:bg-white focus:outline-none"
                                    {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                            <span class="rounded-lg bg-slate-50 px-2.5 py-1 text-xs text-gray-600">
                                <strong class="{{ $isGuru ? 'text-indigo-600' : 'text-emerald-600' }}">{{ $activityCount }}</strong>
                                {{ $activityLabel }}
                            </span>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2 border-t border-gray-100 pt-3">
                            <a href="{{ url('/users/' . $user->id . '/edit') }}"
                                class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-800 hover:bg-amber-100">
                                Edit
                            </a>
                            @if (auth()->id() !== $user->id)
                                <form action="{{ url('/users/toggle', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="rounded-lg border px-3 py-1.5 text-xs font-semibold {{ $user->is_active ? 'border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100' : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                        {{ $user->is_active ? 'Suspend' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form action="{{ url('/users/' . $user->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus pengguna ini secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-600 hover:border-rose-200 hover:bg-rose-50 hover:text-rose-700">
                                        Hapus
                                    </button>
                                </form>
                            @else
                                <span class="self-center text-xs italic text-gray-400">Akun Anda</span>
                            @endif
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="lg:hidden rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-12 text-center text-sm text-gray-500">
                Data pengguna tidak ditemukan atau filter tidak cocok.
            </div>
        @endforelse

        {{-- Desktop table --}}
        <div class="hidden lg:block overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Pengguna</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Kontak</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Peran</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Aktivitas</th>
                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                            <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($users as $user)
                            @php
                                $isGuru = $user->hasRole('guru');
                                $activityCount = $isGuru ? $user->teacherCourses->count() : $user->enrolledCourses->count();
                                $activityLabel = $isGuru ? 'Kelas dibuat' : 'Kelas diikuti';
                            @endphp
                            <tr class="transition hover:bg-gray-50/80">
                                <td class="px-5 py-4 align-top">
                                    <div class="flex items-center gap-3 min-w-[200px]">
                                        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
                                            class="h-10 w-10 shrink-0 rounded-full object-cover ring-2 ring-gray-100">
                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 truncate max-w-[220px]" title="{{ $user->name }}">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-400">ID #{{ $user->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 align-top min-w-[200px]">
                                    <p class="text-gray-800 truncate max-w-[240px]" title="{{ $user->email }}">{{ $user->email }}</p>
                                    <p class="text-xs text-gray-500 font-mono mt-0.5">{{ $user->phone_number ?? '—' }}</p>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <form action="{{ url('/users/' . $user->id . '/update-role') }}" method="POST">
                                        @csrf
                                        <select name="role" onchange="this.form.submit()"
                                            class="rounded-lg border border-gray-200 bg-gray-50 px-2.5 py-1.5 text-xs font-medium text-gray-700 focus:border-indigo-500 focus:bg-white focus:outline-none"
                                            {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                    {{ ucfirst($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td class="px-5 py-4 align-top whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-slate-50 px-2.5 py-1 text-xs text-gray-600">
                                        <strong class="{{ $isGuru ? 'text-indigo-600' : 'text-emerald-600' }}">{{ $activityCount }}</strong>
                                        {{ $activityLabel }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 align-top whitespace-nowrap">
                                    @if ($user->is_active)
                                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Aktif</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-semibold text-rose-800">Suspended</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 align-top text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ url('/users/' . $user->id . '/edit') }}"
                                            class="rounded-md px-2.5 py-1.5 text-xs font-semibold text-amber-700 hover:bg-amber-50">
                                            Edit
                                        </a>
                                        @if (auth()->id() !== $user->id)
                                            <form action="{{ url('/users/toggle', $user->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="rounded-md px-2.5 py-1.5 text-xs font-semibold {{ $user->is_active ? 'text-rose-600 hover:bg-rose-50' : 'text-emerald-600 hover:bg-emerald-50' }}">
                                                    {{ $user->is_active ? 'Suspend' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                            <form action="{{ url('/users/' . $user->id) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Hapus pengguna ini secara permanen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="rounded-md px-2.5 py-1.5 text-xs font-semibold text-gray-500 hover:bg-gray-100 hover:text-rose-600">
                                                    Hapus
                                                </button>
                                            </form>
                                        @else
                                            <span class="px-2 text-xs italic text-gray-400">Akun Anda</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center text-gray-500">
                                    Data pengguna tidak ditemukan atau filter tidak cocok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($users->hasPages())
            <div class="rounded-xl border border-gray-200 bg-white px-4 py-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
