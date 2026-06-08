@extends('layout.template')
@section('title', 'Roles')
@section('header', 'Daftar Role')
@section('content')
    {{-- Tombol Tambah Role Modern --}}
    <a href="{{ route('roles.create') }}"
        class="mb-5 inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-4 py-2.5 rounded-xl shadow-md shadow-indigo-600/10 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Tambah Role
    </a>

    {{-- Kontainer Tabel Responsive --}}
    <div class="w-full overflow-x-auto rounded-2xl border border-slate-200 shadow-xs bg-white">
        <table class="min-w-full divide-y divide-slate-200 text-sm whitespace-nowrap">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="text-left font-semibold text-slate-700 py-3.5 px-4 tracking-wider">Nama Role
                    </th>
                    <th scope="col" class="text-center font-semibold text-slate-700 py-3.5 px-4 tracking-wider w-64">Aksi
                        Konfigurasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @foreach ($roles as $role)
                    <tr class="hover:bg-slate-50/70 transition">
                        {{-- Nama Role --}}
                        <td class="py-3.5 px-4 font-medium text-slate-900">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2 h-2 rounded-full bg-indigo-500 shadow-xs shadow-indigo-500/50"></span>
                                <span
                                    class="bg-slate-100 text-slate-800 px-3 py-1 rounded-lg text-xs font-bold tracking-wide">
                                    {{ $role->name }}
                                </span>
                            </div>
                        </td>

                        {{-- Kolom Kelompok Tombol Aksi --}}
                        <td class="py-3.5 px-4 text-center text-xs space-x-1.5">
                            {{-- Tombol Kelola Hak Akses / Permissions --}}
                            <a href="{{ route('roles.permissions', $role->id) }}"
                                class="inline-flex items-center justify-center gap-1 bg-indigo-50 text-indigo-700 font-bold px-3 py-1.5 rounded-lg hover:bg-indigo-100 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.286Z" />
                                </svg>
                                Permissions
                            </a>

                            {{-- Tombol Edit --}}
                            <a href="{{ route('roles.edit', $role->id) }}"
                                class="inline-flex items-center justify-center bg-white border border-slate-200 text-slate-700 font-semibold px-2.5 py-1.5 rounded-lg hover:bg-slate-50 shadow-xs transition">
                                Edit
                            </a>

                            {{-- Tombol Hapus dengan Konfirmasi --}}
                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus role \'{{ $role->name }}\' ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center justify-center bg-rose-50 border border-rose-100 text-rose-600 font-semibold px-2.5 py-1.5 rounded-lg hover:bg-rose-100/70 transition">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
