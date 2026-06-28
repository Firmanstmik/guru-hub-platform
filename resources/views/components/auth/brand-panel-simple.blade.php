@php
    $fallbackStudents = 1200;
    $fallbackTeachers = 85;
    $totalStudents = $fallbackStudents;
    $totalTeachers = $fallbackTeachers;

    try {
        $totalStudents = \App\Models\User::role('siswa')->count() ?: $fallbackStudents;
        $totalTeachers = \App\Models\User::role('guru')->count() ?: $fallbackTeachers;
    } catch (\Throwable $e) {}

    $formatStat = fn (int $n) => $n >= 1000 ? number_format($n / 1000, 1) . 'k+' : $n . '+';
@endphp

@props([
    'headline' => 'Bergabung dengan komunitas belajar GuruHub.',
    'subline' => 'Daftar sekali, langsung akses katalog kursus dan materi interaktif.',
])

<aside class="gh-auth-brand gh-auth-brand--simple" aria-hidden="true">
    <div class="gh-auth-brand-bg"></div>
    <div class="gh-auth-brand-grid"></div>

    <div class="gh-auth-brand-inner gh-auth-brand-inner--simple">
        <div class="gh-auth-simple-content">
            <a href="{{ url('/') }}" class="inline-flex transition-opacity hover:opacity-90" aria-label="Guru Hub">
                <span class="gh-ref-brand-logo gh-ref-brand-logo-lg">
                    <img src="{{ asset('assets/logo-app/guru_hub_logo.jpeg') }}" alt="Guru Hub">
                </span>
            </a>

            <p class="gh-auth-hero-eyebrow gh-auth-hero-eyebrow--simple">
                <span class="h-1.5 w-1.5 rounded-full"></span>
                GuruHub
            </p>

            <h1 class="gh-auth-headline gh-auth-headline--simple">{{ $headline }}</h1>
            <p class="gh-auth-subline gh-auth-subline--simple">{{ $subline }}</p>

            <p class="gh-auth-simple-metrics" aria-label="Statistik platform">
                <span>{{ $formatStat($totalStudents) }} siswa</span>
                <span aria-hidden="true">·</span>
                <span>{{ $formatStat($totalTeachers) }} pengajar</span>
            </p>
        </div>
    </div>
</aside>
