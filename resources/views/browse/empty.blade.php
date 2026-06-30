@extends('layout.master')
@section('title', ($title ?? 'Belajar') . ' | GuruHub')
@section('flush', true)

@section('content')
<div class="gh-landing-page gh-browse-page gh-browse-shell">
    <div class="gh-browse-shell-grid" aria-hidden="true"></div>

    <section class="gh-ref-section gh-browse-hero">
        <div class="gh-ref-container relative max-w-xl">
            <x-browse.breadcrumb :steps="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => $title ?? 'Belajar'],
            ]" />

            <div class="gh-browse-empty mt-10">
                <div class="gh-browse-empty-icon">
                    <x-ui.lucide name="sparkles" class="h-6 w-6" />
                </div>
                <p class="text-[1.0625rem] font-bold text-[#0A1A4F]">{{ $title ?? 'Segera hadir' }}</p>
                <p class="gh-ref-muted mt-2 text-[0.875rem] leading-relaxed">{{ $message }}</p>
                <div class="mt-6 flex flex-col gap-2 sm:flex-row sm:justify-center">
                    <a href="{{ url('register/student') }}" class="gh-browse-btn inline-flex">Daftar gratis</a>
                    <a href="{{ url('/') }}" class="gh-browse-btn gh-browse-btn--outline inline-flex">Kembali ke beranda</a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
