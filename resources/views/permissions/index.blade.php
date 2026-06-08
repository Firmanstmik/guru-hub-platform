@extends('layout.template')
@section('title', 'Permissions')
@section('header', 'Daftar Permission')
@section('content')
    <a href="{{ route('permissions.create') }}"
        class="mb-5 inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-4 py-2.5 rounded-xl shadow-md shadow-indigo-600/10 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Tambah Permission
    </a>
    <div class="w-full overflow-x-auto rounded-2xl border border-slate-200 shadow-xs bg-white">
        <table class="min-w-full divide-y divide-slate-200 text-sm whitespace-nowrap">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="text-left font-semibold text-slate-700 py-3.5 px-4 tracking-wider">Nama
                        Permission</th>
                    <th scope="col" class="text-left font-semibold text-slate-700 py-3.5 px-4 tracking-wider">Nama
                        Controller</th>
                    <th scope="col" class="text-left font-semibold text-slate-700 py-3.5 px-4 tracking-wider">URL</th>
                    <th scope="col" class="text-center font-semibold text-slate-700 py-3.5 px-4 tracking-wider">Method
                    </th>
                    <th scope="col" class="text-left font-semibold text-slate-700 py-3.5 px-4 tracking-wider">Function
                    </th>
                    <th scope="col" class="text-center font-semibold text-slate-700 py-3.5 px-4 tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 bg-white">
                @foreach ($permissions as $permission)
                    <tr class="hover:bg-slate-50/70 transition">
                        {{-- Nama Permission --}}
                        <td class="py-3 px-4 font-medium text-slate-900">
                            <span class="bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-lg text-xs font-semibold">
                                {{ $permission->name }}
                            </span>
                        </td>

                        {{-- Nama Controller --}}
                        <td class="py-3 px-4 text-slate-600">
                            {{ $permission->controller }}
                        </td>

                        {{-- URL URI --}}
                        <td class="py-3 px-4 font-mono text-xs text-slate-500 tracking-wide">
                            /{{ ltrim($permission->uri, '/') }}
                        </td>

                        {{-- Method Badge Dinamis --}}
                        <td class="py-3 px-4 text-center">
                            @php
                                $method = strtoupper($permission->method);
                                $colorMap = [
                                    'GET' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'POST' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'PUT' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'DELETE' => 'bg-rose-50 text-rose-700 border-rose-200',
                                ];
                                $badgeStyle = $colorMap[$method] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                            @endphp
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold border {{ $badgeStyle }}">
                                {{ $method }}
                            </span>
                        </td>

                        {{-- Function Action --}}
                        <td class="py-3 px-4 text-slate-600 font-mono text-xs">
                            {{ $permission->action }}()
                        </td>

                        {{-- Kolom Tombol Aksi --}}
                        <td class="py-3 px-4 text-center text-xs space-x-2">
                            {{-- Button Update --}}
                            <a href="{{ route('permissions.edit', $permission->id) }}"
                                class="inline-flex items-center justify-center bg-white border border-slate-200 text-slate-700 font-semibold px-3 py-1.5 rounded-lg hover:bg-slate-50 shadow-xs transition">
                                Edit
                            </a>

                            {{-- Button Hapus --}}
                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus permission ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center justify-center bg-rose-50 border border-rose-100 text-rose-600 font-semibold px-3 py-1.5 rounded-lg hover:bg-rose-100/70 transition">
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
