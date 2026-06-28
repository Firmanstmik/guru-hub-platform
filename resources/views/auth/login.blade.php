@extends('layout.auth-login')
@section('title', 'Masuk — Guru Hub')

@section('content')
    <form action="{{ url('/login') }}" method="POST" class="gh-form-stack">
        @csrf

        <div class="gh-login-field gh-field-group">
            <label for="email" class="gh-label">Email</label>
            <input type="email" name="email" id="email" required placeholder="nama@email.com"
                value="{{ old('email') }}" class="gh-input @error('email') gh-input-error @enderror" autocomplete="email">
            @error('email')
                <p class="gh-field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="gh-login-field gh-field-group">
            <div class="flex items-center justify-between gap-2">
                <label for="password" class="gh-label mb-0">Kata sandi</label>
                <a href="{{ url('/forgot-password') }}" class="gh-login-forgot">Lupa kata sandi?</a>
            </div>
            <input type="password" name="password" id="password" required placeholder="••••••••"
                class="gh-input @error('password') gh-input-error @enderror" autocomplete="current-password">
            @error('password')
                <p class="gh-field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-2.5">
            <input id="remember_me" name="remember" type="checkbox" class="gh-checkbox">
            <label for="remember_me" class="text-sm text-brand-500">Ingat perangkat ini</label>
        </div>

        <div class="gh-login-actions">
        <button type="submit" class="gh-ref-btn-submit gh-login-submit">
            Masuk
        </button>
        </div>
    </form>
@endsection

@section('login-footer')
    Belum punya akun?
    <a href="{{ url('register/student') }}">Daftar siswa</a>
    <span class="text-brand-300">·</span>
    <a href="{{ url('register/teacher') }}">Daftar pengajar</a>
@endsection
