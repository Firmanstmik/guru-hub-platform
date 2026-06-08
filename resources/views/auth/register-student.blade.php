@extends('layout.master')

@section('content')
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        {{-- Lebar kontainer dinaikkan ke max-w-xl agar grid 2 kolom password tidak berdesakan --}}
        <div class="sm:mx-auto w-full sm:max-w-xl">
            <h2 class="text-center text-3xl font-extrabold text-gray-900 tracking-tight">
                Mulai Belajar di Guru Hub
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Daftar sebagai Siswa atau
                <a href="{{ url('register/teacher') }}"
                    class="font-semibold text-indigo-600 hover:text-indigo-500 transition">
                    Mendaftar sebagai Guru ➔
                </a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto w-full sm:max-w-xl">
            <div class="bg-white py-8 px-4 shadow-sm border border-gray-100 sm:rounded-2xl sm:px-10">

                {{-- Flash Message Error dari Try-Catch --}}
                @if (session('error'))
                    <div class="mb-4 bg-rose-50 border border-rose-100 p-3 rounded-xl text-xs text-rose-700 leading-normal">
                        <strong>Gagal:</strong> {{ session('error') }}
                    </div>
                @endif

                <form action="/register/student" method="POST" class="space-y-5">
                    @csrf

                    {{-- Input Nama --}}
                    <div>
                        <label for="name"
                            class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                            placeholder="Masukkan nama lengkap Anda"
                            class="w-full px-4 py-2.5 text-sm bg-white border @error('name') border-rose-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-gray-700">
                        @error('name')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Email --}}
                    <div>
                        <label for="email"
                            class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            Alamat Email <span class="text-rose-500">*</span>
                        </label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                            placeholder="contoh@email.com"
                            class="w-full px-4 py-2.5 text-sm bg-white border @error('email') border-rose-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-gray-700">
                        @error('email')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- BARU: Input Nomor Telepon --}}
                    <div>
                        <label for="phone_number"
                            class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            Nomor Telepon / WhatsApp <span class="text-rose-500">*</span>
                        </label>
                        <input id="phone_number" name="phone_number" type="text" value="{{ old('phone_number') }}"
                            required placeholder="Contoh: 08123456789"
                            class="w-full px-4 py-2.5 text-sm bg-white border @error('phone_number') border-rose-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-gray-700">
                        @error('phone_number')
                            <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- IMPLEMENTASI 2 KOLOM: Password & Password Confirmation --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Kolom 1: Password --}}
                        <div>
                            <label for="password"
                                class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                                Kata Sandi <span class="text-rose-500">*</span>
                            </label>
                            <input id="password" name="password" type="password" required placeholder="Minimal 8 karakter"
                                class="w-full px-4 py-2.5 text-sm bg-white border @error('password') border-rose-500 @else border-gray-200 @enderror rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-gray-700">
                            @error('password')
                                <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kolom 2: Password Confirm --}}
                        <div>
                            <label for="password_confirmation"
                                class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                                Ulangi Kata Sandi <span class="text-rose-500">*</span>
                            </label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                placeholder="Ketik ulang kata sandi"
                                class="w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-gray-700">
                        </div>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-xs focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                            Daftar Sebagai Siswa
                        </button>
                    </div>
                </form>

                <div class="mt-6 border-t border-gray-100 pt-4 text-center">
                    <p class="text-xs text-gray-500">
                        Sudah punya akun?
                        <a href="/login" class="font-bold text-indigo-600 hover:underline">Masuk disini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
