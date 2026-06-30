@extends('layout.master')
@section('title', $level->name . ' — ' . $category->name . ' | GuruHub')
@section('flush', true)

@section('content')
<div class="gh-landing-page gh-browse-page min-h-screen">
    <section class="gh-ref-section gh-browse-hero">
        <div class="gh-ref-container relative">
            <x-browse.breadcrumb :steps="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => $category->name, 'url' => route('browse.category', $category->slug)],
                ['label' => $level->name],
            ]" />

            <div class="gh-browse-head mt-6">
                <p class="gh-ref-eyebrow">Pilih mata pelajaran</p>
                <h1 class="gh-ref-display mt-3 text-[1.75rem] sm:text-4xl">
                    {{ $category->icon }} {{ $category->name }}
                </h1>
                <p class="gh-ref-muted mt-3 text-[0.9375rem]">{{ $level->name }} — pilih mapel untuk melihat kursus dan pengajar.</p>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4">
                @forelse ($subjects as $subject)
                    <a href="{{ route('browse.teachers', ['category' => $category->slug, 'level' => $level->slug, 'subject' => $subject->slug]) }}"
                        class="gh-browse-level-card">
                        <span class="text-xl" aria-hidden="true">📖</span>
                        <span class="mt-2 block text-[0.875rem] font-bold leading-snug text-[#0A1A4F]">{{ $subject->name }}</span>
                        @if ($subject->courses_count > 0)
                            <span class="mt-1 block text-[0.6875rem] text-[#0A1A4F]/45">{{ $subject->courses_count }} kursus</span>
                        @endif
                    </a>
                @empty
                    <p class="col-span-2 gh-ref-muted text-[0.9375rem]">Kursus untuk mapel ini segera hadir. Daftar dulu untuk mendapat notifikasi.</p>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection
