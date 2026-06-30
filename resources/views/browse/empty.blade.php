@extends('layout.master')
@section('title', ($title ?? 'Belajar') . ' | GuruHub')
@section('flush', true)

@section('content')
<div class="gh-landing-page gh-browse-page min-h-screen">
    <section class="gh-ref-section gh-browse-hero">
        <div class="gh-ref-container relative">
            <x-browse.breadcrumb :steps="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => $title ?? 'Belajar'],
            ]" />

            <div class="gh-browse-empty mt-10">
                <p class="text-[1rem] font-semibold text-[#0A1A4F]">{{ $title ?? 'Segera hadir' }}</p>
                <p class="gh-ref-muted mt-2 text-[0.875rem]">{{ $message }}</p>
                <div class="mt-5 flex flex-col gap-2 sm:flex-row sm:justify-center">
                    <a href="{{ url('register/student') }}" class="gh-cat-sheet-cta inline-flex h-10 items-center justify-center px-5 text-[0.8125rem]">Daftar Gratis</a>
                    <a href="{{ url('/') }}" class="gh-cat-sheet-link inline-flex h-10 items-center justify-center px-5 text-[0.8125rem]">Kembali ke beranda</a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
