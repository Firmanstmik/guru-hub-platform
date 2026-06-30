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
    $roleLabel = $isGuru ? 'Pengajar' : 'Siswa';

    $guruNav = [
        ['href' => '/guru-dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard', 'active' => request()->is('guru-dashboard*')],
        ['href' => '/courses', 'icon' => 'layers', 'label' => 'Kelas', 'active' => request()->is('courses*') || request()->is('categories*')],
        ['href' => '/materials', 'icon' => 'book-open', 'label' => 'Materi', 'active' => request()->is('materials*') || request()->is('videos*')],
        ['href' => '/schedules', 'icon' => 'calendar', 'label' => 'Jadwal', 'active' => request()->is('schedules*')],
        ['href' => '/earnings', 'icon' => 'circle-dollar-sign', 'label' => 'Pendapatan', 'active' => request()->is('earnings*')],
        ['href' => $profileUrl, 'icon' => 'user', 'label' => 'Profil', 'active' => request()->is('teachers*')],
    ];

    $siswaNav = [
        ['href' => '/siswa-dashboard', 'icon' => 'layout-dashboard', 'label' => 'Home', 'active' => request()->is('siswa-dashboard*')],
        ['href' => '/tampil-kursus', 'icon' => 'library', 'label' => 'Katalog', 'active' => request()->is('tampil-kursus*') || request()->is('bookings*')],
        ['href' => '/my-courses', 'icon' => 'book-open', 'label' => 'Kelas Saya', 'active' => request()->is('my-courses*') || request()->is('student/courses*')],
        ['href' => '/history-bookings', 'icon' => 'receipt-text', 'label' => 'Riwayat', 'active' => request()->is('history-bookings*') || request()->is('payments-class*')],
        ['href' => $profileUrl, 'icon' => 'user', 'label' => 'Profil', 'active' => request()->is('biodata*')],
    ];

    $desktopNav = $isGuru ? $guruNav : $siswaNav;
@endphp

<header class="gh-app-header" x-data="{ searchOpen: {{ $showSearch ? 'true' : 'false' }} }">
    <div class="gh-app-header-inner">
        <a href="{{ $dashboardUrl }}" class="gh-app-header-greeting lg:hidden">
            <p class="gh-app-header-eyebrow">{{ $eyebrow ?? $defaultEyebrow }}</p>
            <h1 class="gh-app-header-title">{{ $title ?? $defaultTitle }}</h1>
        </a>

        <a href="{{ $dashboardUrl }}" class="gh-app-header-brand hidden lg:flex">
            <div class="gh-nav-brand-logo">
                <img src="{{ asset('assets/logo-app/guru_hub_logo.jpeg') }}" alt="GuruHub"
                    class="h-full w-full object-contain">
            </div>
            <span class="gh-app-header-brand-copy">
                <span class="gh-nav-brand-title">GuruHub</span>
                <span class="gh-app-header-brand-role">{{ $defaultEyebrow }}</span>
            </span>
        </a>

        <nav class="gh-app-desktop-nav" aria-label="{{ $isGuru ? 'Navigasi desktop pengajar' : 'Navigasi desktop siswa' }}">
            <div class="gh-app-desktop-nav-track" role="menubar">
                @foreach ($desktopNav as $item)
                    <a href="{{ $item['href'] }}" role="menuitem"
                        @class(['gh-app-desktop-link', 'gh-app-desktop-link-active' => $item['active']])>
                        <span class="gh-app-desktop-link-icon" aria-hidden="true">
                            <x-ui.lucide :name="$item['icon']" class="h-3.5 w-3.5" />
                        </span>
                        <span class="gh-app-desktop-link-label">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </nav>

        <div class="gh-app-header-actions">
            @if ($showSearch && $searchAction)
                <button type="button" class="gh-app-icon-btn lg:hidden" @click="searchOpen = !searchOpen"
                    aria-label="Cari">
                    <x-ui.lucide name="search" class="h-4 w-4" />
                </button>
            @endif

            <div class="gh-app-header-actions-main">
                <button type="button" class="gh-app-icon-btn gh-app-icon-btn--ghost" aria-label="Notifikasi">
                    <x-ui.lucide name="bell" class="h-4 w-4" />
                </button>

                <a href="{{ $profileUrl }}" class="gh-app-desktop-profile lg:flex" title="Profil {{ $roleLabel }}">
                    <x-app.user-avatar :user="$user" size="sm" :ring="false" />
                    <span class="gh-app-desktop-profile-meta">
                        <span class="gh-app-desktop-profile-name">{{ $firstName }}</span>
                        <span class="gh-app-desktop-profile-role">{{ $roleLabel }}</span>
                    </span>
                </a>

                <a href="{{ $profileUrl }}" class="lg:hidden" title="Profil">
                    <x-app.user-avatar :user="$user" size="md" />
                </a>

                <span class="gh-app-header-divider" aria-hidden="true"></span>

                <a href="{{ url('/logout') }}" class="gh-app-icon-btn gh-app-icon-btn--logout" title="Keluar" aria-label="Keluar">
                    <x-ui.lucide name="log-out" class="h-4 w-4" />
                </a>
            </div>
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
