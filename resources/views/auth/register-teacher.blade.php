@extends('layout.auth')
@section('title', 'Daftar Pengajar — Guru Hub')
@section('auth-simple', true)

@section('brand-headline', 'Bagikan ilmu, berdampak lebih luas.')
@section('brand-subline', 'Studio pengajar untuk merilis kursus, materi, dan memantau siswa dalam satu tempat.')

@section('auth-header')
    <x-auth.role-switcher active="teacher" />
    <h2 class="gh-auth-card-title gh-auth-card-title--register">Daftar sebagai pengajar</h2>
    <p class="gh-auth-card-subtitle gh-auth-card-subtitle--register">Lengkapi profil untuk mulai mengajar di GuruHub.</p>
@endsection

@section('content')
    <form action="/register/teacher" method="POST" class="gh-form-stack gh-auth-form-register">
        @csrf

        <div class="gh-field-group">
            <label for="name" class="gh-label gh-label-required">Nama lengkap & gelar</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                placeholder="Contoh: Jhon Doe, S.Pd."
                class="gh-input gh-input--register @error('name') gh-input-error @enderror">
            @error('name')
                <p class="gh-field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="gh-field-group">
            <label for="phone_number" class="gh-label gh-label-required">WhatsApp</label>
            <input id="phone_number" name="phone_number" type="text" value="{{ old('phone_number') }}" required
                placeholder="08xxxxxxxxxx"
                class="gh-input gh-input--register @error('phone_number') gh-input-error @enderror">
            @error('phone_number')
                <p class="gh-field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="gh-field-group">
            <label for="email" class="gh-label gh-label-required">Email profesional</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                placeholder="nama@email.com"
                class="gh-input gh-input--register @error('email') gh-input-error @enderror">
            @error('email')
                <p class="gh-field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="gh-field-group">
            <label for="password" class="gh-label gh-label-required">Kata sandi</label>
            <input id="password" name="password" type="password" required placeholder="Minimal 8 karakter"
                class="gh-input gh-input--register @error('password') gh-input-error @enderror">
            @error('password')
                <p class="gh-field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="gh-field-group">
            <label for="password_confirmation" class="gh-label gh-label-required">Ulangi kata sandi</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                placeholder="Ketik ulang kata sandi" class="gh-input gh-input--register">
        </div>

        <button type="submit" class="gh-ref-btn-submit">Daftar sebagai pengajar</button>

        <p class="gh-auth-register-foot">
            Sudah punya akun?
            <a href="{{ url('/login') }}" class="gh-auth-link">Masuk</a>
        </p>
    </form>
@endsection
