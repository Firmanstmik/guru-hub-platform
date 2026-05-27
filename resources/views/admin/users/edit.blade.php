@extends('layout.template')
@section('title', 'Edit Pengguna')
@section('header', 'Edit Pengguna')
@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-6">
        <a href="{{ url('/users') }}" class="text-sm font-medium text-indigo-600 hover:underline flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Daftar Pengguna
        </a>
        <h3 class="text-gray-700 text-3xl font-semibold mt-2">Edit Pengguna: {{ $user->name }}</h3>
    </div>

    @if(session('error'))
        <div class="bg-rose-100 border-l-4 border-rose-500 text-rose-700 p-4 mb-6 rounded-r shadow-sm text-sm max-w-2xl">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow border border-gray-100 max-w-2xl overflow-hidden">
        <form action="{{ url('/users', $user->id) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full border border-gray-300 rounded-lg text-sm p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('name') border-rose-500 @enderror">
                @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border border-gray-300 rounded-lg text-sm p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('email') border-rose-500 @enderror">
                    @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nomor Telepon</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="Contoh: 08123456789" class="w-full border border-gray-300 rounded-lg text-sm p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('phone_number') border-rose-500 @enderror">
                    @error('phone_number') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Password Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" placeholder="Isi hanya jika ingin ganti password" class="w-full border border-gray-300 rounded-lg text-sm p-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('password') border-rose-500 @enderror">
                    @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Hak Akses (Role)</label>
                    @if(auth()->id() === $user->id)
                        <input type="hidden" name="role" value="{{ $user->roles->first()?->name }}">
                        <select disabled class="w-full border border-gray-200 rounded-lg text-sm p-2.5 bg-gray-50 text-gray-400 cursor-not-allowed">
                            <option selected>{{ ucfirst($user->roles->first()?->name) }}</option>
                        </select>
                        <p class="text-xxs text-amber-600 mt-1 italic">Demi keamanan, Anda tidak dapat mengubah peran akun Anda sendiri dari panel ini.</p>
                    @else
                        <select name="role" required class="w-full border border-gray-300 rounded-lg text-sm p-2.5 bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('role') border-rose-500 @enderror">
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    @endif
                </div>
            </div>

            <div class="bg-gray-50 -mx-6 -mb-6 px-6 py-4 flex justify-end gap-3 border-t border-gray-100">
                <a href="{{ url('/users') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition">Batal</a>
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-medium px-5 py-2 rounded-lg text-sm shadow-xs transition">Perbarui Pengguna</button>
            </div>
        </form>
    </div>
</div>
@endsection
