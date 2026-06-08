@extends('layout.master')
@section('content')
@section('title','Form Login')
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl shadow-gray-100/70 border border-gray-100">
            <!-- Logo & Header -->
            <div class="text-center mb-8">
                <div class="inline-flex h-12 w-12 rounded-xl bg-indigo-600 items-center justify-center font-black text-white text-2xl shadow-lg shadow-indigo-200 mb-3">
                    G
                </div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Selamat Datang Kembali</h2>
                <p class="text-sm text-gray-500 mt-1">Silakan masuk ke akun Guru Hub Anda</p>
            </div>
    
            <!-- Kartu Form Login -->
            <div>
                
                <!-- Alert Error (Gaya bawaan form Anda, tapi dipoles lebih rapi) -->
                @if(session('error'))
                    <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 p-3 mb-5 rounded-r text-sm flex items-center gap-2" role="alert">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
    
                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf
    
                    <!-- Input Email -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/>
                                </svg>
                            </div>
                            <input type="email" name="email" id="email" required placeholder="nama@email.com"
                                class="block w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 focus:bg-white transition">
                        </div>
                    </div>
    
                    <!-- Input Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-gray-600 uppercase tracking-wider">Password</label>
                            <!-- Opsional: Link lupa password -->
                            <a href="#" class="text-xs font-medium text-indigo-600 hover:text-indigo-500 transition">Lupa Password?</a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input type="password" name="password" id="password" required placeholder="••••••••"
                                class="block w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 focus:bg-white transition">
                        </div>
                    </div>
    
                    <!-- Remember Me / Ingat Saya -->
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" 
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded-md">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-600 select-none">
                            Ingat saya di perangkat ini
                        </label>
                    </div>
    
                    <!-- Tombol Submit -->
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-indigo-200 active:scale-[0.98] transition-all duration-150">
                        Masuk ke Aplikasi
                    </button>
                </form>
            </div>
    
            <!-- Footer / Hak Cipta Singkat -->
            <p class="text-center text-xs text-gray-400 mt-8">&copy; 2026 Guru Hub. All rights reserved.</p>
        </div>
    </div>
@endsection
