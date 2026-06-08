@extends('layout.template')
@section('title', 'Manage User')
@section('header', 'Manage Role Users')
@section('content')
    <div class="w-full overflow-x-auto rounded-2xl border border-slate-200 shadow-xs bg-white">
        <table class="min-w-full divide-y divide-slate-200 text-sm whitespace-nowrap">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="text-left font-semibold text-slate-700 py-3.5 px-4 tracking-wider">Nama Pengguna
                    </th>
                    <th scope="col" class="text-center font-semibold text-slate-700 py-3.5 px-4 tracking-wider w-32">Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @foreach ($users as $user)
                    <tr class="hover:bg-slate-50/70 transition">
                        {{-- Nama User --}}
                        <td class="py-3.5 px-4 font-medium text-slate-900">
                            <div class="flex items-center gap-3">
                                {{-- Manfaatkan inisial atau avatar kecil jika ada --}}
                                <div
                                    class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs uppercase">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <span>{{ $user->name }}</span>
                            </div>
                        </td>

                        {{-- Kolom Tombol Aksi --}}
                        <td class="py-3.5 px-4 text-center">
                            <a href="{{ route('users-manage.roles', $user->id) }}"
                                class="inline-flex items-center justify-center gap-1.5 bg-white border border-slate-200 text-slate-700 font-bold px-3 py-1.5 rounded-lg hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 shadow-xs transition text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                Kelola Role
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
