@extends('layout.master')
@section('title', $teacher->name . ' — ' . $subject->name . ' | GuruHub')
@section('flush', true)

@section('content')
<div class="gh-landing-page gh-browse-page min-h-screen">
    <section class="gh-ref-section gh-browse-hero">
        <div class="gh-ref-container relative max-w-2xl">
            <x-browse.breadcrumb :steps="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => $category->name, 'url' => route('browse.category', $category->slug)],
                ['label' => $level->name, 'url' => route('browse.subjects', ['category' => $category->slug, 'level' => $level->slug])],
                ['label' => $subject->name, 'url' => route('browse.teachers', ['category' => $category->slug, 'level' => $level->slug, 'subject' => $subject->slug])],
                ['label' => $teacher->name],
            ]" />

            <div class="gh-browse-head mt-6 text-center">
                <span class="gh-browse-teacher-avatar mx-auto h-16 w-16 text-xl">{{ strtoupper(substr($teacher->name, 0, 1)) }}</span>
                <h1 class="gh-ref-display mt-4 text-[1.75rem] sm:text-3xl">{{ $teacher->name }}</h1>
                <p class="gh-ref-muted mt-2 text-[0.9375rem]">{{ $subject->name }} · {{ $level->name }}</p>
                @if ($teacher->teacherProfile?->bio)
                    <p class="mt-3 text-[0.875rem] leading-relaxed text-[#0A1A4F]/70">{{ $teacher->teacherProfile->bio }}</p>
                @endif
            </div>

            <div class="mt-8 space-y-3">
                <p class="text-[11px] font-semibold tracking-[0.14em] text-[#0A1A4F]/45 uppercase">Kursus tersedia</p>
                @foreach ($courses as $course)
                    <div class="gh-browse-teacher-card">
                        <span class="min-w-0 flex-1 text-left">
                            <span class="block text-[0.9375rem] font-bold text-[#0A1A4F]">{{ $course->title }}</span>
                            <span class="mt-0.5 block text-[0.75rem] text-[#0A1A4F]/55">
                                {{ $course->materials_count }} materi · {{ number_format($course->students_count) }} siswa
                                @if ($course->reviews_avg_rating)
                                    · {{ number_format($course->reviews_avg_rating, 1) }} ★
                                @endif
                            </span>
                        </span>
                        @auth
                            @if (auth()->user()->hasRole('siswa'))
                                <a href="{{ url('login') }}" class="gh-cat-sheet-cta inline-flex h-9 shrink-0 items-center justify-center px-4 text-[0.75rem]">Lihat</a>
                            @endif
                        @else
                            <a href="{{ url('register/student') }}" class="gh-cat-sheet-cta inline-flex h-9 shrink-0 items-center justify-center px-4 text-[0.75rem]">Daftar</a>
                        @endauth
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
