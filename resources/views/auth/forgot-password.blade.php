@extends('layout.auth')
@section('title', 'Lupa Kata Sandi — Guru Hub')

@section('brand-headline', 'Kami bantu Anda kembali belajar.')
@section('brand-subline', 'Reset kata sandi dengan aman dan lanjutkan akses ke kursus serta materi Anda.')
@section('brand-quote', 'Proses reset yang sederhana membuat saya bisa fokus kembali ke materi pelajaran.')
@section('brand-author', 'Dewi Lestari')
@section('brand-author-role', 'Siswa — Kelas Bahasa Inggris')

@section('auth-header')
    <h2 class="gh-auth-card-title">Lupa kata sandi?</h2>
    <p class="gh-auth-card-subtitle">Masukkan email terdaftar. Kami akan mengirimkan tautan reset jika akun ditemukan.</p>
@endsection

@section('content')
    <form action="{{ url('/forgot-password') }}" method="POST" class="gh-form-stack">
        @csrf

        <div class="gh-field-group">
            <label for="email" class="gh-label gh-label-required">Alamat email</label>
            <input type="email" name="email" id="email" required placeholder="nama@email.com"
                value="{{ old('email') }}" class="gh-input @error('email') gh-input-error @enderror" autocomplete="email">
            @error('email')
                <p class="gh-field-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="gh-btn-primary w-full py-3">
            Kirim tautan reset
        </button>
    </form>
@endsection

@section('auth-footer')
    Ingat kata sandi Anda?
    <a href="{{ url('/login') }}" class="gh-auth-link">Kembali ke masuk</a>
@endsection
