@php
    $fallbackStudents = 1200;
    $fallbackTeachers = 85;
    $fallbackCourses = 48;
    $fallbackActive = 340;
    $fallbackCertificates = 520;

    $featuredCourse = null;
    $totalStudents = $fallbackStudents;
    $totalTeachers = $fallbackTeachers;
    $totalCourses = $fallbackCourses;
    $activeLearners = $fallbackActive;
    $certificatesIssued = $fallbackCertificates;

    try {
        $totalStudents = \App\Models\User::role('siswa')->count() ?: $fallbackStudents;
        $totalTeachers = \App\Models\User::role('guru')->count() ?: $fallbackTeachers;
        $totalCourses = \App\Models\Course::count() ?: $fallbackCourses;
        $activeLearners = \App\Models\CourseStudent::where('status', 'active')->count() ?: $fallbackActive;
        $certificatesIssued = \App\Models\Certificate::count() ?: $fallbackCertificates;

        $featuredCourse = \App\Models\Course::query()
            ->where('status', 'published')
            ->with(['teacher:id,name', 'category:id,name'])
            ->withCount('students')
            ->latest()
            ->first();
    } catch (\Throwable $e) {
        // fallbacks already set
    }

    $formatStat = fn (int $n) => $n >= 1000 ? number_format($n / 1000, 1) . 'k+' : $n . '+';

    $featuredTitle = $featuredCourse?->title ?? 'Kelas Intensif Berstandar Industri';
    $featuredTeacher = $featuredCourse?->teacher?->name ?? 'Tim Pengajar Guru Hub';
    $featuredCategory = $featuredCourse?->category?->name ?? 'Pembelajaran Terstruktur';
    $featuredStudents = $featuredCourse?->students_count ?? 128;
    $featuredCover = $featuredCourse?->cover_image ?? null;
    $featuredProgress = min(94, max(62, ($featuredStudents % 40) + 58));
@endphp

@props([
    'headline' => 'Belajar dari para ahli. Mengajar dengan dampak nyata.',
    'subline' => 'Platform pembelajaran premium untuk siswa, pengajar, dan institusi — dari kurikulum hingga sertifikasi.',
    'quote' => 'Guru Hub membantu saya menyelesaikan kurikulum intensif dengan materi yang jelas dan progress yang terukur.',
    'author' => 'Rina Wijaya',
    'authorRole' => 'Siswa — Kelas Digital Marketing',
])

<aside class="gh-auth-brand" aria-hidden="true">
    <div class="gh-auth-brand-bg"></div>
    <div class="gh-auth-brand-grid"></div>
    <div class="gh-auth-brand-shape -right-20 top-20 h-72 w-72"></div>
    <div class="gh-auth-brand-shape bottom-24 left-4 h-48 w-48"></div>
    <div class="gh-auth-brand-shape right-1/4 top-1/2 h-32 w-32 opacity-50"></div>

    <div class="gh-auth-brand-inner">
        <div>
            <a href="{{ url('/') }}" class="inline-flex transition-opacity hover:opacity-90" aria-label="Guru Hub">
                <span class="gh-ref-brand-logo gh-ref-brand-logo-lg">
                    <img src="{{ asset('assets/logo-app/guru_hub_logo.jpeg') }}" alt="Guru Hub">
                </span>
            </a>

            <p class="gh-auth-hero-eyebrow">
                <span class="h-1.5 w-1.5 rounded-full"></span>
                Platform pembelajaran untuk Indonesia
            </p>

            <h1 class="gh-auth-headline">{{ $headline }}</h1>
            <p class="gh-auth-subline">{{ $subline }}</p>

            <div class="gh-auth-visual-stage">
                {{-- Floating achievement --}}
                <div class="gh-auth-float gh-auth-float-achievement gh-auth-animate-float-delayed">
                    <div class="gh-auth-float-icon">
                        <x-ui.lucide name="award" class="h-5 w-5" />
                    </div>
                    <div>
                        <p class="gh-auth-float-value">{{ $formatStat($certificatesIssued) }}</p>
                        <p class="gh-auth-float-label">Sertifikat diterbitkan</p>
                    </div>
                </div>

                {{-- Featured learning card --}}
                <div class="gh-auth-featured-card">
                    <div class="gh-auth-featured-thumb">
                        <span class="gh-auth-featured-badge">Kursus unggulan</span>
                        @if ($featuredCover)
                            <img src="{{ asset('storage/' . $featuredCover) }}" alt="{{ $featuredTitle }}"
                                class="h-full w-full object-cover">
                        @else
                            <div class="gh-auth-featured-thumb-placeholder">
                                <x-ui.lucide name="book-open" class="h-8 w-8 text-accent-400/80" />
                                <span class="text-[11px] font-medium text-brand-400">Materi · Video · Kuis</span>
                            </div>
                        @endif
                    </div>
                    <h3 class="gh-auth-featured-title">{{ $featuredTitle }}</h3>
                    <p class="gh-auth-featured-meta">
                        {{ $featuredTeacher }}
                        <span class="text-brand-500">·</span>
                        {{ $featuredCategory }}
                    </p>
                    <div class="mt-2 flex items-center justify-between text-[11px] text-brand-400">
                        <span>{{ $featuredStudents }}+ siswa terdaftar</span>
                        <span class="text-accent-300">{{ $featuredProgress }}% materi</span>
                    </div>
                    <div class="gh-auth-featured-progress">
                        <div class="gh-auth-featured-progress-bar" style="width: {{ $featuredProgress }}%"></div>
                    </div>
                </div>

                {{-- Floating active students --}}
                <div class="gh-auth-float gh-auth-float-active gh-auth-animate-float">
                    <span class="gh-auth-pulse-dot" aria-hidden="true"></span>
                    <div>
                        <p class="gh-auth-float-value">{{ $formatStat($activeLearners) }}</p>
                        <p class="gh-auth-float-label">Siswa aktif belajar</p>
                    </div>
                </div>
            </div>

            <div class="gh-auth-stats">
                <div class="gh-auth-stat">
                    <p class="gh-auth-stat-value">{{ $formatStat($totalStudents) }}</p>
                    <p class="gh-auth-stat-label">Siswa</p>
                </div>
                <div class="gh-auth-stat">
                    <p class="gh-auth-stat-value">{{ $formatStat($totalTeachers) }}</p>
                    <p class="gh-auth-stat-label">Pengajar</p>
                </div>
                <div class="gh-auth-stat">
                    <p class="gh-auth-stat-value">{{ $formatStat($totalCourses) }}</p>
                    <p class="gh-auth-stat-label">Kursus</p>
                </div>
            </div>
        </div>

        <blockquote class="gh-auth-testimonial">
            <p class="gh-auth-testimonial-quote">"{{ $quote }}"</p>
            <footer class="gh-auth-testimonial-author">{{ $author }}</footer>
            <p class="gh-auth-testimonial-role">{{ $authorRole }}</p>
        </blockquote>
    </div>
</aside>
