@extends('layout.template')
@section('title', 'Tambah Permission')
@section('header', 'Tambah Permission Baru')

@section('content')
    <div class="w-full max-w-2xl mx-auto py-4 px-2">
        {{-- Card Wrapper --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

            {{-- Card Header Mini --}}
            <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <span class="w-2 h-5 bg-indigo-600 rounded-full"></span>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Formulir Konfigurasi Rute Dinamis</h3>
            </div>

            {{-- Form Body --}}
            <form action="{{ route('permissions.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                {{-- Grid Wrapper --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Nama Permission --}}
                    <div class="md:col-span-2">
                        <label for="name"
                            class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Permission <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                            class="w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-slate-700"
                            placeholder="Contoh: view-users">
                        <p class="text-[11px] text-slate-400 mt-1">Gunakan format huruf kecil berpenghubung strip.</p>
                    </div>

                    {{-- Nama Controller --}}
                    <div>
                        <label for="controller"
                            class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Controller  <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="controller" id="controller" value="{{ old('controller') }}"
                            class="w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-slate-700"
                            placeholder="Contoh: UserController">
                    </div>

                    {{-- URL URI --}}
                    <div>
                        <label for="uri"
                            class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alamat URL  <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="uri" id="uri" value="{{ old('uri') }}"
                            class="w-full px-4 py-2.5 text-sm font-mono bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-slate-700"
                            placeholder="Contoh: users atau users/{user}">
                    </div>

                    {{-- Method HTTP --}}
                    <div>
                        <label for="method"
                            class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            HTTP Method <span class="text-rose-500">*</span>
                        </label>
                        <select name="method" id="method" required
                            class="w-full px-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-slate-700 appearance-none bg-[url('data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%23475569%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C%2Fpolyline%3E%3C%2Fsvg%3E')] bg-[length:1.25rem] bg-[right_1rem_center] bg-no-repeat">
                            <option value="" disabled selected>Pilih Method</option>
                            <option value="get" {{ old('method') == 'get' ? 'selected' : '' }}>GET</option>
                            <option value="post" {{ old('method') == 'post' ? 'selected' : '' }}>POST</option>
                            <option value="put" {{ old('method') == 'put' ? 'selected' : '' }}>PUT</option>
                            <option value="patch" {{ old('method') == 'patch' ? 'selected' : '' }}>PATCH</option>
                            <option value="delete" {{ old('method') == 'delete' ? 'selected' : '' }}>DELETE</option>
                        </select>
                    </div>

                    {{-- Nama Fungsi Action --}}
                    <div>
                        <label for="action"
                            class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Fungsi / Action  <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="action" id="action" value="{{ old('action') }}"
                            class="w-full px-4 py-2.5 text-sm font-mono bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-slate-700"
                            placeholder="Contoh: index, store, edit">
                    </div>

                </div>

                {{-- Tombol Navigasi Aksi --}}
                <div class="pt-4 border-t border-slate-50 flex items-center justify-between gap-4">
                    <a href="{{ route('permissions.index') }}"
                        class="inline-flex items-center justify-center bg-white border border-slate-200 text-slate-700 font-bold px-5 py-2.5 rounded-xl hover:bg-slate-50 shadow-xs transition text-sm">
                        Kembali
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center bg-indigo-600 text-white font-bold px-6 py-2.5 rounded-xl hover:bg-indigo-700 shadow-md shadow-indigo-600/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition text-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
