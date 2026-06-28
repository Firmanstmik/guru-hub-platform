@extends('layout.auth')
@section('title', 'Reset Kata Sandi — Guru Hub')

@section('brand-headline', 'Amankan akun Anda dengan kata sandi baru.')
@section('brand-subline', 'Pilih kata sandi yang kuat untuk melindungi progress belajar dan data profil Anda.')
@section('brand-quote', 'Setelah reset, saya langsung bisa melanjutkan kelas tanpa hambatan.')
@section('brand-author', 'Fajar Nugroho')
@section('brand-author-role', 'Pengajar — Kelas Mobile Development')

@section('auth-header')
    <h2 class="gh-auth-card-title">Atur kata sandi baru</h2>
    <p class="gh-auth-card-subtitle">Masukkan kata sandi baru untuk akun Anda.</p>
@endsection

@section('content')
    <form action="{{ url('/reset-password') }}" method="POST" class="gh-form-stack">
        @csrf

        <input type="hidden" name="token" value="{{ $token ?? request()->route('token') ?? old('token') }}">

        <div class="gh-field-group">
            <label for="email" class="gh-label gh-label-required">Alamat email</label>
            <input type="email" name="email" id="email" required placeholder="nama@email.com"
                value="{{ old('email', request('email')) }}" class="gh-input @error('email') gh-input-error @enderror" autocomplete="email">
            @error('email')
                <p class="gh-field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="gh-field-group">
            <label for="password" class="gh-label gh-label-required">Kata sandi baru</label>
            <input type="password" name="password" id="password" required placeholder="Minimal 8 karakter"
                class="gh-input @error('password') gh-input-error @enderror" autocomplete="new-password">
            @error('password')
                <p class="gh-field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="gh-field-group">
            <label for="password_confirmation" class="gh-label gh-label-required">Konfirmasi kata sandi baru</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                placeholder="Ketik ulang kata sandi baru" class="gh-input" autocomplete="new-password">
        </div>

        <button type="submit" class="gh-btn-primary w-full py-3">
            Simpan kata sandi baru
        </button>
    </form>
@endsection

@section('auth-footer')
    <a href="{{ url('/login') }}" class="gh-auth-link">Kembali ke masuk</a>
@endsection
