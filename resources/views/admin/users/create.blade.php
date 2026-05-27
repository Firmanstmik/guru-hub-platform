@extends('layout.template')
@section('title', 'Tambah Pengguna')
@section('header', 'Form Tambah Pengguna')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-6">
        <a href="{{ url('/users') }}" class="text-sm font-medium text-indigo-600 hover:underline flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Pengguna
        </a>
        <h3 class="text-gray-700 text-3xl font-semibold mt-2">Tambah Pengguna Baru</h3>
    </div>

    <div class="bg-white rounded-xl shadow border border-gray-100 max-w-2xl overflow-hidden">
        <form action="{{ url('/users') }}" method="POST" class="p-6 space-y-5">
            @csrf
            
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: John Doe" class="w-full border border-gray-300 rounded-lg text-sm p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('name') border-rose-500 @enderror">
                @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="johndoe@example.com" class="w-full border border-gray-300 rounded-lg text-sm p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('email') border-rose-500 @enderror">
                    @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nomor Telepon (Opsional)</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="Contoh: 08123456789" class="w-full border border-gray-300 rounded-lg text-sm p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('phone_number') border-rose-500 @enderror">
                    @error('phone_number') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full border border-gray-300 rounded-lg text-sm p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('password') border-rose-500 @enderror">
                    @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
{{-- 
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Hak Akses (Role)</label>
                    <select name="role" required class="w-full border border-gray-300 rounded-lg text-sm p-2.5 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('role') border-rose-500 @enderror">
                        <option value="">-- Pilih Hak Akses --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    @error('role') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div> --}}
            </div>

            <div class="bg-gray-50 -mx-6 -mb-6 px-6 py-4 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ url('/users') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition">Batal</a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-5 py-2 rounded-lg text-sm shadow-xs transition">Simpan Pengguna</button>
            </div>
        </form>
    </div>
</div>
@endsection
