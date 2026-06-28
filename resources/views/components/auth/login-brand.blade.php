@php
    $fallbackStudents = 1200;
    $fallbackTeachers = 85;
    $fallbackCourses = 48;

    try {
        $totalStudents = \App\Models\User::role('siswa')->count() ?: $fallbackStudents;
        $totalTeachers = \App\Models\User::role('guru')->count() ?: $fallbackTeachers;
        $totalCourses = \App\Models\Course::count() ?: $fallbackCourses;
    } catch (\Throwable $e) {
        $totalStudents = $fallbackStudents;
        $totalTeachers = $fallbackTeachers;
        $totalCourses = $fallbackCourses;
    }

    $fmt = fn (int $n) => $n >= 1000 ? number_format($n / 1000, 1) . 'k' : (string) $n;
@endphp

<aside class="gh-login-brand">
    <div>
        <a href="{{ url('/') }}" class="inline-flex transition-opacity hover:opacity-90" aria-label="Guru Hub">
            <span class="gh-ref-brand-logo">
                <img src="{{ asset('assets/logo-app/guru_hub_logo.jpeg') }}" alt="Guru Hub">
            </span>
        </a>

        <h1 class="gh-login-headline">Belajar dari para ahli.<br>Mengajar dengan dampak nyata.</h1>
        <p class="gh-login-tagline">Kurikulum terstruktur, progress terukur, dan sertifikasi yang diakui — dalam satu platform.</p>

        <p class="gh-login-metrics" aria-label="Statistik platform">
            <span><strong>{{ $fmt($totalStudents) }}</strong> siswa</span>
            <span class="gh-login-metrics-sep" aria-hidden="true">·</span>
            <span><strong>{{ $fmt($totalTeachers) }}</strong> pengajar</span>
            <span class="gh-login-metrics-sep" aria-hidden="true">·</span>
            <span><strong>{{ $fmt($totalCourses) }}</strong> kursus</span>
        </p>
    </div>

    <p class="gh-login-brand-footer">
        Dipercaya oleh institusi pendidikan dan profesional di seluruh Indonesia.
    </p>
</aside>
