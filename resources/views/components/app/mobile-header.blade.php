@props([
    'title' => null,
    'eyebrow' => null,
    'showSearch' => false,
    'searchAction' => null,
    'searchPlaceholder' => 'Cari...',
])

@php
    $user = auth()->user();
    $isGuru = $user->hasRole('guru');
    $firstName = explode(' ', $user->name ?? 'Pengguna')[0];
    $defaultEyebrow = $isGuru ? 'Panel Pengajar' : 'Ruang Belajar';
    $defaultTitle = 'Halo, ' . $firstName;
    $profileUrl = $isGuru ? '/teachers' : '/biodata';
    $dashboardUrl = $isGuru ? '/guru-dashboard' : '/siswa-dashboard';
@endphp

<header class="gh-app-header" x-data="{ searchOpen: {{ $showSearch ? 'true' : 'false' }} }">
    <div class="gh-app-header-inner">
        <a href="{{ $dashboardUrl }}" class="gh-app-header-greeting lg:hidden">
            <p class="gh-app-header-eyebrow">{{ $eyebrow ?? $defaultEyebrow }}</p>
            <h1 class="gh-app-header-title">{{ $title ?? $defaultTitle }}</h1>
        </a>

        <a href="{{ $dashboardUrl }}" class="gh-nav-brand hidden lg:flex">
            <div class="gh-nav-brand-logo">
                <img src="{{ asset('assets/logo-app/guru_hub_logo.jpeg') }}" alt="GuruHub"
                    class="h-full w-full object-contain">
            </div>
            <span class="gh-nav-brand-title">GuruHub</span>
        </a>

        @if ($isGuru)
            <nav class="gh-app-desktop-nav" aria-label="Navigasi desktop pengajar">
                <a href="/guru-dashboard" @class(['gh-app-desktop-link', 'gh-app-desktop-link-active' => request()->is('guru-dashboard*')])>
                    <x-ui.lucide name="layout-dashboard" class="h-4 w-4" /> Dashboard
                </a>
                <a href="/courses" @class(['gh-app-desktop-link', 'gh-app-desktop-link-active' => request()->is('courses*')])>
                    <x-ui.lucide name="layers" class="h-4 w-4" /> Kelas
                </a>
                <a href="/materials" @class(['gh-app-desktop-link', 'gh-app-desktop-link-active' => request()->is('materials*')])>
                    <x-ui.lucide name="book-open" class="h-4 w-4" /> Materi
                </a>
                <a href="/schedules" @class(['gh-app-desktop-link', 'gh-app-desktop-link-active' => request()->is('schedules*')])>
                    <x-ui.lucide name="calendar" class="h-4 w-4" /> Jadwal
                </a>
                <a href="/earnings" @class(['gh-app-desktop-link', 'gh-app-desktop-link-active' => request()->is('earnings*')])>
                    <x-ui.lucide name="circle-dollar-sign" class="h-4 w-4" /> Pendapatan
                </a>
            </nav>
        @else
            <nav class="gh-app-desktop-nav" aria-label="Navigasi desktop siswa">
                <a href="/siswa-dashboard" @class(['gh-app-desktop-link', 'gh-app-desktop-link-active' => request()->is('siswa-dashboard*')])>
                    <x-ui.lucide name="layout-dashboard" class="h-4 w-4" /> Home
                </a>
                <a href="/tampil-kursus" @class(['gh-app-desktop-link', 'gh-app-desktop-link-active' => request()->is('tampil-kursus*')])>
                    <x-ui.lucide name="library" class="h-4 w-4" /> Katalog
                </a>
                <a href="/my-courses" @class(['gh-app-desktop-link', 'gh-app-desktop-link-active' => request()->is('my-courses*')])>
                    <x-ui.lucide name="book-open" class="h-4 w-4" /> Kelas Saya
                </a>
                <a href="/history-bookings" @class(['gh-app-desktop-link', 'gh-app-desktop-link-active' => request()->is('history-bookings*')])>
                    <x-ui.lucide name="receipt-text" class="h-4 w-4" /> Riwayat
                </a>
            </nav>
        @endif

        <div class="gh-app-header-actions">
            @if ($showSearch && $searchAction)
                <button type="button" class="gh-app-icon-btn lg:hidden" @click="searchOpen = !searchOpen"
                    aria-label="Cari">
                    <x-ui.lucide name="search" class="h-4 w-4" />
                </button>
            @endif

            <button type="button" class="gh-app-icon-btn" aria-label="Notifikasi">
                <x-ui.lucide name="bell" class="h-4 w-4" />
            </button>

            <a href="{{ $profileUrl }}" class="lg:hidden" title="Profil">
                <x-app.user-avatar :user="$user" size="md" />
            </a>

            <a href="{{ url('/logout') }}" class="gh-app-icon-btn" title="Keluar" aria-label="Keluar">
                <x-ui.lucide name="log-out" class="h-4 w-4" />
            </a>
        </div>
    </div>

    @if ($showSearch && $searchAction)
        <div x-show="searchOpen" x-cloak x-transition class="border-t border-[#0A1A4F]/[0.06] px-4 py-3 lg:hidden">
            <form action="{{ $searchAction }}" method="GET" class="gh-app-search">
                <x-ui.lucide name="search" class="gh-app-search-icon" />
                <input type="search" name="search" value="{{ request('search') }}"
                    placeholder="{{ $searchPlaceholder }}" class="gh-app-input">
            </form>
        </div>
    @endif
</header>
