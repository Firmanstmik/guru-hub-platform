@extends('layout.master')
@section('title', $teacher->name . ' — ' . $subject->name . ' | GuruHub')
@section('flush', true)

@php
    $meta = \App\Support\CategoryIcons::meta($category->slug);
    $profile = $teacher->teacherProfile;
    $totalStudents = (int) $courses->sum('students_count');
    $avgRating = round((float) ($courses->avg('reviews_avg_rating') ?: ($profile?->average_rating ?? 5)), 1);
    $skills = [];
    if ($profile?->skills_tags) {
        $decoded = json_decode($profile->skills_tags, true);
        $skills = is_array($decoded) ? $decoded : array_filter(array_map('trim', explode(',', $profile->skills_tags)));
    }
@endphp

@section('content')
<div class="gh-landing-page gh-browse-page gh-browse-shell">
    <div class="gh-browse-shell-grid" aria-hidden="true"></div>

    <section class="gh-ref-section gh-browse-hero">
        <div class="gh-ref-container relative max-w-3xl">
            <x-browse.breadcrumb :steps="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => $category->name, 'url' => route('browse.category', $category->slug)],
                ['label' => $level->name, 'url' => route('browse.subjects', ['category' => $category->slug, 'level' => $level->slug])],
                ['label' => $subject->name, 'url' => route('browse.teachers', ['category' => $category->slug, 'level' => $level->slug, 'subject' => $subject->slug])],
                ['label' => $teacher->name],
            ]" />

            <div class="gh-browse-profile">
                <div class="gh-browse-profile-avatar">
                    <x-app.user-avatar :user="$teacher" size="2xl" :ring="true" />
                </div>

                <div class="relative mt-4">
                    @if (($profile?->verification_status ?? '') === 'approved')
                        <span class="gh-browse-verified">
                            <x-ui.lucide name="badge-check" class="h-3.5 w-3.5" />
                            Pengajar terverifikasi
                        </span>
                    @endif

                    <h1 class="gh-ref-display mt-3 text-[1.625rem] sm:text-[2rem]">{{ $teacher->name }}</h1>

                    @if ($profile?->title)
                        <p class="mt-1 text-[0.875rem] font-medium text-[#0E7490]">{{ $profile->title }}</p>
                    @endif

                    <div class="mt-3 flex flex-wrap items-center justify-center gap-2">
                        <span class="gh-browse-subject-pill">{{ $subject->name }}</span>
                        <span class="gh-browse-subject-pill">{{ $level->name }}</span>
                    </div>

                    @if ($profile?->bio)
                        <p class="gh-browse-bio">{{ $profile->bio }}</p>
                    @endif

                    @if (count($skills))
                        <div class="gh-browse-skill-tags">
                            @foreach (array_slice($skills, 0, 6) as $skill)
                                <span class="gh-browse-skill-tag">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="gh-browse-stats">
                    <div class="gh-browse-stat">
                        <strong>{{ $courses->count() }}</strong>
                        <span>Kursus</span>
                    </div>
                    <div class="gh-browse-stat">
                        <strong>{{ number_format($totalStudents) }}</strong>
                        <span>Siswa</span>
                    </div>
                    <div class="gh-browse-stat">
                        <strong>{{ number_format($avgRating, 1) }} ★</strong>
                        <span>Rating</span>
                    </div>
                </div>
            </div>

            <div class="mt-10">
                <div class="gh-browse-section-title">
                    <h2>Kursus tersedia</h2>
                    <span>{{ $courses->count() }} pilihan</span>
                </div>

                <div class="space-y-3">
                    @foreach ($courses as $course)
                        <article class="gh-browse-course-card">
                            <div class="gh-browse-course-body">
                                <div class="gh-browse-course-main">
                                    <span class="gh-browse-course-icon">
                                        <x-ui.lucide name="book-open" class="h-5 w-5" />
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="gh-browse-course-title">{{ $course->title }}</h3>
                                        <div class="gh-browse-course-meta">
                                            <span>{{ $course->materials_count }} materi</span>
                                            <span class="gh-browse-course-meta-dot">{{ number_format($course->students_count) }} siswa</span>
                                            @if ($course->reviews_avg_rating)
                                                <span class="gh-browse-course-meta-dot">{{ number_format($course->reviews_avg_rating, 1) }} ★</span>
                                            @endif
                                        </div>
                                        @if ($course->description)
                                            <p class="gh-browse-course-desc">{{ $course->description }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="gh-browse-course-action">
                                    @if ((float) $course->price > 0)
                                        <p class="gh-browse-course-price">Rp {{ number_format($course->price, 0, ',', '.') }}</p>
                                    @else
                                        <p class="gh-browse-course-price">Gratis</p>
                                    @endif

                                    @auth
                                        @if (auth()->user()->hasRole('siswa'))
                                            <a href="{{ url('student/dashboard') }}" class="gh-browse-btn">Mulai belajar</a>
                                        @else
                                            <a href="{{ url('login') }}" class="gh-browse-btn gh-browse-btn--outline">Masuk</a>
                                        @endif
                                    @else
                                        <a href="{{ url('register/student') }}" class="gh-browse-btn">Daftar gratis</a>
                                    @endauth
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
