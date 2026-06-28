@php
    $fallbackStudents = 1200;
    $fallbackTeachers = 85;
    $fallbackCourses = 48;

    try {
        $mStudents = \App\Models\User::role('siswa')->count() ?: $fallbackStudents;
        $mTeachers = \App\Models\User::role('guru')->count() ?: $fallbackTeachers;
        $mCourses = \App\Models\Course::count() ?: $fallbackCourses;
    } catch (\Throwable $e) {
        $mStudents = $fallbackStudents;
        $mTeachers = $fallbackTeachers;
        $mCourses = $fallbackCourses;
    }

    $fmt = fn (int $n) => $n >= 1000 ? number_format($n / 1000, 1) . 'k+' : $n . '+';
@endphp

<div class="gh-auth-mobile-trust" aria-label="Statistik platform">
    <div class="gh-auth-mobile-trust-item">
        <p class="gh-auth-mobile-trust-value">{{ $fmt($mStudents) }}</p>
        <p class="gh-auth-mobile-trust-label">Siswa</p>
    </div>
    <div class="h-6 w-px bg-border-subtle"></div>
    <div class="gh-auth-mobile-trust-item">
        <p class="gh-auth-mobile-trust-value">{{ $fmt($mTeachers) }}</p>
        <p class="gh-auth-mobile-trust-label">Pengajar</p>
    </div>
    <div class="h-6 w-px bg-border-subtle"></div>
    <div class="gh-auth-mobile-trust-item">
        <p class="gh-auth-mobile-trust-value">{{ $fmt($mCourses) }}</p>
        <p class="gh-auth-mobile-trust-label">Kursus</p>
    </div>
</div>
