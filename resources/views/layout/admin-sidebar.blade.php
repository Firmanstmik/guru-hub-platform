{{-- Sidebar admin — state `mobileOpen` & `collapsed` dari parent di template.blade.php --}}

{{-- Mobile top bar --}}
<div class="md:hidden flex items-center justify-between bg-gray-900 text-white px-4 py-3 sticky top-0 z-50 shadow-md border-b border-gray-800">
    <div class="flex items-center gap-2.5 min-w-0">
        <div class="h-9 w-9 shrink-0 rounded-full overflow-hidden bg-gray-800 ring-2 ring-indigo-500/30">
            <img src="{{ asset('assets') }}/logo-app/guru_hub_logo.jpeg" alt="Guru Hub"
                class="h-full w-full object-cover">
        </div>
        <span class="font-semibold text-base tracking-wide truncate">Guru Hub</span>
    </div>
    <button type="button" @click="mobileOpen = !mobileOpen"
        class="p-2 rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white transition focus:outline-none focus:ring-2 focus:ring-indigo-500"
        :aria-expanded="mobileOpen" aria-label="Buka menu sidebar">
        <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg x-show="mobileOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>

{{-- Mobile overlay --}}
<div x-show="mobileOpen" x-cloak @click="mobileOpen = false"
    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-40 md:hidden"></div>

<aside :class="[
    mobileOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
    collapsed ? 'md:w-[4.75rem]' : 'md:w-64',
]"
    class="admin-sidebar-shell fixed top-0 left-0 z-50 flex h-[100dvh] w-64 shrink-0 flex-col overflow-hidden bg-gray-900 text-gray-300 border-r border-gray-800 shadow-xl md:shadow-none transition-all duration-300 ease-in-out">

    {{-- Header brand + toggle desktop --}}
    <div class="relative flex-shrink-0 flex items-center gap-3 px-4 pt-5 pb-4 border-b border-gray-800"
        :class="collapsed ? 'md:justify-center md:px-2 md:pt-5' : ''">
        <div class="h-10 w-10 shrink-0 rounded-full overflow-hidden bg-gray-800 ring-2 ring-indigo-500/30"
            :class="collapsed ? 'md:h-9 md:w-9' : ''">
            <img src="{{ asset('assets') }}/logo-app/guru_hub_logo.jpeg" alt="Guru Hub Logo"
                class="h-full w-full object-cover">
        </div>
        <div x-show="!collapsed" x-cloak class="min-w-0 flex-1 hidden md:block">
            <h1 class="font-bold text-white leading-tight tracking-wide truncate">Guru Hub</h1>
            <span class="text-xs text-gray-500">Dashboard Admin</span>
        </div>
        <div x-show="collapsed" x-cloak class="hidden md:block md:sr-only">
            <span class="sr-only">Guru Hub Admin</span>
        </div>

        {{-- Toggle collapse (desktop, hanya saat sidebar terbuka) --}}
        <button type="button" x-show="!collapsed" @click="collapsed = true"
            class="hidden md:flex items-center justify-center h-8 w-8 shrink-0 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition focus:outline-none focus:ring-2 focus:ring-indigo-500"
            title="Tutup sidebar" aria-label="Tutup sidebar">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
        </button>
    </div>

    {{-- Navigasi (scroll terpisah dari header & footer) --}}
    <nav class="admin-nav-scroll flex-1 overflow-y-auto overflow-x-hidden py-4 px-3 min-h-0 space-y-1"
        :class="collapsed ? 'md:px-2' : ''">

        <span x-show="!collapsed" class="block px-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Utama</span>
        <span x-show="collapsed" x-cloak class="hidden md:block h-px bg-gray-800 my-2 mx-2"></span>

        @php
            $linkBase = 'flex items-center gap-3 rounded-xl text-sm font-medium transition group';
            $linkIdle = 'text-gray-400 hover:bg-gray-800 hover:text-white';
            $linkActive = 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10';
            $iconIdle = 'text-gray-400 group-hover:text-white transition-colors';
            $iconActive = 'text-white';
        @endphp

        <a href="/admin-dashboard" @click="mobileOpen = false"
            title="Dashboard"
            class="{{ $linkBase }} px-3 py-2 {{ request()->is('admin-dashboard*') ? $linkActive : $linkIdle }}"
            :class="collapsed ? 'md:justify-center md:px-2' : ''">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('admin-dashboard*') ? $iconActive : $iconIdle }}"
                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span x-show="!collapsed" class="truncate">Dashboard</span>
        </a>

        <a href="/company-accounts" @click="mobileOpen = false" title="Rekening Perusahaan"
            class="{{ $linkBase }} px-3 py-2 {{ request()->is('company-accounts*') ? $linkActive : $linkIdle }}"
            :class="collapsed ? 'md:justify-center md:px-2' : ''">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('company-accounts*') ? $iconActive : $iconIdle }}"
                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
            <span x-show="!collapsed" class="truncate">Rekening Perusahaan</span>
        </a>

        <a href="/categories" @click="mobileOpen = false" title="Kelas Kursus"
            class="{{ $linkBase }} px-3 py-2 {{ request()->is('categories*') ? $linkActive : $linkIdle }}"
            :class="collapsed ? 'md:justify-center md:px-2' : ''">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('categories*') ? $iconActive : $iconIdle }}" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
            <span x-show="!collapsed" class="truncate">Kelas Kursus</span>
        </a>

        <a href="/courses" @click="mobileOpen = false" title="Kelola Kelas"
            class="{{ $linkBase }} px-3 py-2 {{ request()->is('courses*') ? $linkActive : $linkIdle }}"
            :class="collapsed ? 'md:justify-center md:px-2' : ''">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('courses*') ? $iconActive : $iconIdle }}" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span x-show="!collapsed" class="truncate">Kelola Kelas</span>
        </a>

        <a href="/users" @click="mobileOpen = false" title="Data Users"
            class="{{ $linkBase }} px-3 py-2 {{ request()->is('users*') ? $linkActive : $linkIdle }}"
            :class="collapsed ? 'md:justify-center md:px-2' : ''">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('users*') ? $iconActive : $iconIdle }}" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span x-show="!collapsed" class="truncate">Data Users</span>
        </a>

        <a href="/teachers" @click="mobileOpen = false" title="Verifikasi Guru"
            class="{{ $linkBase }} px-3 py-2 {{ request()->is('teachers*') ? $linkActive : $linkIdle }}"
            :class="collapsed ? 'md:justify-center md:px-2' : ''">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('teachers*') ? $iconActive : $iconIdle }}" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <span x-show="!collapsed" class="truncate">Verifikasi Guru</span>
        </a>

        <a href="/student-biodata" @click="mobileOpen = false" title="Verifikasi Siswa"
            class="{{ $linkBase }} px-3 py-2 {{ request()->is('student-biodata*') ? $linkActive : $linkIdle }}"
            :class="collapsed ? 'md:justify-center md:px-2' : ''">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('student-biodata*') ? $iconActive : $iconIdle }}"
                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <span x-show="!collapsed" class="truncate">Verifikasi Siswa</span>
        </a>

        <span x-show="!collapsed"
            class="block px-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider pt-4 mb-2">Materi
            Konten</span>
        <span x-show="collapsed" x-cloak class="hidden md:block h-px bg-gray-800 my-2 mx-2"></span>

        <a href="/materials" @click="mobileOpen = false" title="File Modul / Materi"
            class="{{ $linkBase }} px-3 py-2 {{ request()->is('materials*') ? $linkActive : $linkIdle }}"
            :class="collapsed ? 'md:justify-center md:px-2' : ''">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('materials*') ? $iconActive : $iconIdle }}" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span x-show="!collapsed" class="truncate">File Modul / Materi</span>
        </a>

        <a href="/videos" @click="mobileOpen = false" title="Video Kursus"
            class="{{ $linkBase }} px-3 py-2 {{ request()->is('videos*') ? $linkActive : $linkIdle }}"
            :class="collapsed ? 'md:justify-center md:px-2' : ''">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('videos*') ? $iconActive : $iconIdle }}" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            <span x-show="!collapsed" class="truncate">Video Kursus</span>
        </a>

        <span x-show="!collapsed"
            class="block px-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider pt-4 mb-2">Operasional</span>
        <span x-show="collapsed" x-cloak class="hidden md:block h-px bg-gray-800 my-2 mx-2"></span>

        <a href="/course-students" @click="mobileOpen = false" title="Pendaftaran Kelas"
            class="{{ $linkBase }} px-3 py-2 {{ request()->is('course-students*') ? $linkActive : $linkIdle }}"
            :class="collapsed ? 'md:justify-center md:px-2' : ''">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('course-students*') ? $iconActive : $iconIdle }}"
                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span x-show="!collapsed" class="truncate">Pendaftaran Kelas</span>
        </a>

        <a href="/schedules" @click="mobileOpen = false" title="Jadwal Live Class"
            class="{{ $linkBase }} px-3 py-2 {{ request()->is('schedules*') ? $linkActive : $linkIdle }}"
            :class="collapsed ? 'md:justify-center md:px-2' : ''">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('schedules*') ? $iconActive : $iconIdle }}" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span x-show="!collapsed" class="truncate">Jadwal Live Class</span>
        </a>

        <a href="/bookings" @click="mobileOpen = false" title="Monitoring Booking"
            class="{{ $linkBase }} px-3 py-2 {{ request()->is('bookings*') ? $linkActive : $linkIdle }}"
            :class="collapsed ? 'md:justify-center md:px-2' : ''">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('bookings*') ? $iconActive : $iconIdle }}" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <span x-show="!collapsed" class="truncate">Monitoring Booking</span>
        </a>

        <a href="/certificates" @click="mobileOpen = false" title="Sertifikat Kelulusan"
            class="{{ $linkBase }} px-3 py-2 {{ request()->is('certificates*') ? $linkActive : $linkIdle }}"
            :class="collapsed ? 'md:justify-center md:px-2' : ''">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('certificates*') ? $iconActive : $iconIdle }}" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
            <span x-show="!collapsed" class="truncate">Sertifikat Kelulusan</span>
        </a>

        <a href="/homepage-testimonials" @click="mobileOpen = false" title="Testimoni Beranda"
            class="{{ $linkBase }} px-3 py-2 {{ request()->is('homepage-testimonials*') ? $linkActive : $linkIdle }}"
            :class="collapsed ? 'md:justify-center md:px-2' : ''">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('homepage-testimonials*') ? $iconActive : $iconIdle }}" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
            </svg>
            <span x-show="!collapsed" class="truncate">Testimoni Beranda</span>
        </a>

        <a href="/reviews" @click="mobileOpen = false" title="Ulasan Siswa"
            class="{{ $linkBase }} px-3 py-2 {{ request()->is('reviews*') ? $linkActive : $linkIdle }}"
            :class="collapsed ? 'md:justify-center md:px-2' : ''">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('reviews*') ? $iconActive : $iconIdle }}" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
            </svg>
            <span x-show="!collapsed" class="truncate">Ulasan Siswa</span>
        </a>

        <span x-show="!collapsed"
            class="block px-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider pt-4 mb-2">Keuangan</span>
        <span x-show="collapsed" x-cloak class="hidden md:block h-px bg-gray-800 my-2 mx-2"></span>

        <a href="/payments" @click="mobileOpen = false" title="Verifikasi Pembayaran"
            class="{{ $linkBase }} px-3 py-2 {{ request()->is('payments*') ? $linkActive : $linkIdle }}"
            :class="collapsed ? 'md:justify-center md:px-2' : ''">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('payments*') ? $iconActive : $iconIdle }}" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
            <span x-show="!collapsed" class="truncate">Verifikasi Pembayaran</span>
        </a>

        <a href="/earnings" @click="mobileOpen = false" title="Pendapatan Guru"
            class="{{ $linkBase }} px-3 py-2 {{ request()->is('earnings*') ? $linkActive : $linkIdle }}"
            :class="collapsed ? 'md:justify-center md:px-2' : ''">
            <svg class="w-5 h-5 shrink-0 {{ request()->is('earnings*') ? $iconActive : $iconIdle }}" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span x-show="!collapsed" class="truncate">Pendapatan Guru</span>
        </a>

        <span x-show="!collapsed"
            class="block px-3 text-[10px] font-semibold text-gray-500 uppercase tracking-wider pt-4 mb-2">Konfigurasi</span>
        <span x-show="collapsed" x-cloak class="hidden md:block h-px bg-gray-800 my-2 mx-2"></span>

        <div x-data="{ isDropdownOpen: {{ request()->is('akses*') || request()->is('roles*') || request()->is('permissions*') || request()->is('users-manage*') ? 'true' : 'false' }} }"
            x-show="!collapsed" class="space-y-1">
            <button type="button" @click="isDropdownOpen = !isDropdownOpen"
                class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition group text-gray-400 hover:bg-gray-800 hover:text-white">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0 text-gray-400 group-hover:text-white" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Settings</span>
                </div>
                <svg :class="isDropdownOpen ? 'rotate-180' : ''"
                    class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="isDropdownOpen" x-cloak class="pl-11 pr-2 mt-1 space-y-1">
                <a href="/akses" @click="mobileOpen = false"
                    class="block py-1 text-sm font-medium rounded-md transition {{ request()->is('akses*') ? 'text-white font-semibold' : 'text-gray-400 hover:text-white' }}">All
                    Akses</a>
                <a href="/roles" @click="mobileOpen = false"
                    class="block py-1 text-sm font-medium rounded-md transition {{ request()->is('roles*') ? 'text-white font-semibold' : 'text-gray-400 hover:text-white' }}">Roles</a>
                <a href="/permissions" @click="mobileOpen = false"
                    class="block py-1 text-sm font-medium rounded-md transition {{ request()->is('permissions*') ? 'text-white font-semibold' : 'text-gray-400 hover:text-white' }}">Permissions</a>
                <a href="/users-manage" @click="mobileOpen = false"
                    class="block py-1 text-sm font-medium rounded-md transition {{ request()->is('users-manage*') ? 'text-white font-semibold' : 'text-gray-400 hover:text-white' }}">Manage
                    Roles</a>
            </div>
        </div>

        <a href="/akses" x-show="collapsed" x-cloak @click="mobileOpen = false" title="Settings"
            class="hidden md:flex {{ $linkBase }} px-2 py-2 justify-center {{ request()->is('akses*') || request()->is('roles*') || request()->is('permissions*') || request()->is('users-manage*') ? $linkActive : $linkIdle }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </a>
    </nav>

    {{-- Footer profil --}}
    <div class="relative z-10 flex-shrink-0 border-t border-gray-800 bg-gray-900 p-3 pb-4"
        :class="collapsed ? 'md:px-2' : ''">
        <div class="flex items-center gap-2"
            :class="collapsed ? 'md:flex-col md:gap-2' : 'justify-between'">
            <div class="flex items-center gap-2 min-w-0"
                :class="collapsed ? 'md:flex-col' : 'max-w-[150px]'">
                <div
                    class="h-8 w-8 shrink-0 rounded-full bg-indigo-600/20 ring-1 ring-indigo-500/30 flex items-center justify-center font-bold text-sm text-indigo-300">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div x-show="!collapsed" class="truncate min-w-0">
                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-[10px] text-gray-500 truncate">Sistem Kontrol</p>
                </div>
            </div>
            <a href="{{ url('/logout') }}" title="Keluar"
                class="p-1.5 shrink-0 rounded-lg hover:bg-gray-800 text-gray-500 hover:text-rose-400 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </a>
        </div>
    </div>
</aside>

{{-- Tombol buka sidebar saat collapsed (desktop) --}}
<button type="button" x-show="collapsed" x-cloak @click="collapsed = false"
    class="hidden md:flex fixed left-[4.75rem] top-5 z-30 items-center justify-center h-9 w-9 rounded-r-xl bg-gray-900 border border-l-0 border-gray-700 text-gray-400 hover:text-white hover:bg-gray-800 shadow-lg transition"
    title="Buka sidebar" aria-label="Buka sidebar">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
    </svg>
</button>
