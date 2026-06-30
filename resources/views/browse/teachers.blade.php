@extends('layout.master')
@section('title', $subject->name . ' — ' . $category->name . ' | GuruHub')
@section('flush', true)

@section('content')
<div class="gh-landing-page gh-browse-page min-h-screen">
    <section class="gh-ref-section gh-browse-hero">
        <div class="gh-ref-container relative">
            <x-browse.breadcrumb :steps="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => $category->name, 'url' => route('browse.category', $category->slug)],
                ['label' => $level->name, 'url' => route('browse.subjects', ['category' => $category->slug, 'level' => $level->slug])],
                ['label' => $subject->name],
            ]" />

            <div class="gh-browse-head mt-6 text-center">
                <p class="gh-ref-eyebrow">Pengajar tersedia</p>
                <h1 class="gh-ref-display mt-3 text-[1.75rem] sm:text-4xl">{{ $subject->name }}</h1>
                <p class="gh-ref-muted mt-3 text-[0.9375rem]">{{ $level->name }} · {{ $category->name }}</p>
            </div>

            <div class="mt-8 space-y-3">
                @forelse ($teachers as $item)
                    <a href="{{ route('browse.teacher', ['category' => $category->slug, 'level' => $level->slug, 'subject' => $subject->slug, 'teacher' => $item->user->id]) }}"
                        class="gh-browse-teacher-card">
                        <span class="gh-browse-teacher-avatar">{{ strtoupper(substr($item->user->name, 0, 1)) }}</span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-[0.9375rem] font-bold text-[#0A1A4F]">{{ $item->user->name }}</span>
                            <span class="mt-0.5 block text-[0.75rem] text-[#0A1A4F]/55">
                                {{ $item->course_count }} kursus · {{ number_format($item->students_total) }} siswa · {{ number_format($item->rating, 1) }} ★
                            </span>
                        </span>
                        <svg class="h-4 w-4 shrink-0 text-[#0E7490]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
                    </a>
                @empty
                    <div class="gh-browse-empty">
                        <p class="text-[0.9375rem] font-semibold text-[#0A1A4F]">Belum ada pengajar untuk mapel ini</p>
                        <p class="gh-ref-muted mt-2 text-[0.8125rem]">Daftar sebagai siswa dan kami akan kabari saat kursus tersedia.</p>
                        <a href="{{ url('register/student') }}" class="gh-ref-btn-cta-primary mt-4 inline-flex h-10 items-center justify-center rounded-xl px-5 text-[0.8125rem]">Daftar Gratis</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection
