<!DOCTYPE html>
<html lang="id">

<head>
    <x-layout.head
        :title="trim($__env->yieldContent('title')) ?: 'GuruHub — Platform Pembelajaran Digital Indonesia'"
        :description="trim($__env->yieldContent('meta_description')) ?: null"
        :image="trim($__env->yieldContent('meta_image')) ?: null"
    />
</head>

<body @class(['gh-app-shell', 'gh-landing-mode' => View::hasSection('flush')])
    x-data="{ mobileOpen: false, registerOpen: false, navScrolled: false }"
    @scroll.window="navScrolled = window.scrollY > 12">

@hasSection('flush')
    <header class="gh-ref-nav-bar fixed inset-x-0 top-0 z-50" aria-label="Navigasi situs">
        <div class="gh-ref-nav-inner">
            <div class="gh-ref-nav-brand-wrap">
                <a href="{{ url('/') }}" class="shrink-0" aria-label="Guru Hub">
                    <span class="gh-ref-nav-logo">
                        <img src="{{ asset('assets/logo-app/guru_hub_logo.jpeg') }}" alt="Guru Hub">
                    </span>
                </a>
                <div class="gh-ref-nav-brand-text lg:hidden">
                    <span class="gh-ref-nav-brand-name">GuruHub</span>
                    <span class="gh-ref-nav-brand-tag">Belajar • Mengajar • Berkembang</span>
                </div>
            </div>

            <div class="gh-ref-nav-actions">
                <div class="relative hidden sm:block">
                    <button type="button" @click="registerOpen = !registerOpen" @click.away="registerOpen = false"
                        class="gh-ref-btn-ghost-nav"
                        x-bind:class="registerOpen ? 'border-white/25 bg-white/10 text-white' : ''"
                        x-bind:aria-expanded="registerOpen">
                        <x-ui.lucide name="users" class="h-4 w-4" />
                        <span>Daftar</span>
                        <span class="inline-flex transition-transform duration-200"
                            x-bind:class="registerOpen ? 'rotate-180' : ''">
                            <x-ui.lucide name="chevron-down" class="h-3.5 w-3.5 opacity-70" />
                        </span>
                    </button>
                    <div x-show="registerOpen" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                        class="gh-ref-nav-dropdown">
                        <a href="{{ url('register/student') }}" class="gh-ref-nav-dropdown-item">
                            <div class="gh-ref-nav-dropdown-icon">
                                <x-ui.lucide name="book-open" class="h-4 w-4" />
                            </div>
                            <div>
                                <span class="gh-ref-nav-dropdown-label">Daftar sebagai Siswa</span>
                                <span class="gh-ref-nav-dropdown-desc">Akses katalog kursus & sertifikat</span>
                            </div>
                        </a>
                        <a href="{{ url('register/teacher') }}" class="gh-ref-nav-dropdown-item">
                            <div class="gh-ref-nav-dropdown-icon gh-ref-nav-dropdown-icon-teal">
                                <x-ui.lucide name="award" class="h-4 w-4" />
                            </div>
                            <div>
                                <span class="gh-ref-nav-dropdown-label">Daftar sebagai Pengajar</span>
                                <span class="gh-ref-nav-dropdown-desc">Rilis kursus & kelola siswa</span>
                            </div>
                        </a>
                    </div>
                </div>
                <a href="{{ url('register/student') }}" class="gh-ref-btn-ghost-nav sm:hidden" aria-label="Daftar">
                    <x-ui.lucide name="users" class="h-5 w-5" />
                    <span class="sr-only">Daftar</span>
                </a>
                <span class="gh-ref-nav-divider" aria-hidden="true"></span>
                <a href="{{ url('/login') }}" class="gh-ref-btn-primary">Masuk</a>
                <button type="button" class="grid h-10 w-10 place-items-center rounded-full border border-white/10 text-white/80 transition hover:bg-white/5 sm:hidden"
                    @click="mobileOpen = !mobileOpen" x-bind:aria-expanded="mobileOpen" aria-label="Menu navigasi">
                    <span x-show="!mobileOpen"><x-ui.lucide name="menu" class="h-5 w-5" /></span>
                    <span x-show="mobileOpen" x-cloak><x-ui.lucide name="x" class="h-5 w-5" /></span>
                </button>
            </div>
        </div>

        <div x-show="mobileOpen" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="gh-ref-nav-mobile-panel sm:hidden">
            <nav class="flex flex-col gap-1" aria-label="Navigasi mobile">
                <a href="{{ url('register/student') }}" class="rounded-lg px-3 py-2.5 text-sm text-white/80" @click="mobileOpen = false">
                    <x-ui.lucide name="book-open" class="mr-2 inline h-4 w-4 opacity-70" />
                    Daftar Siswa
                </a>
                <a href="{{ url('register/teacher') }}" class="rounded-lg px-3 py-2.5 text-sm text-white/80" @click="mobileOpen = false">
                    <x-ui.lucide name="award" class="mr-2 inline h-4 w-4 opacity-70" />
                    Daftar Pengajar
                </a>
                <div class="mt-2 border-t border-white/10 pt-3">
                    <a href="{{ url('/login') }}" class="gh-ref-btn-primary justify-center" @click="mobileOpen = false">Masuk</a>
                </div>
            </nav>
        </div>
    </header>
@else
    <header class="gh-nav-shell gh-nav-shell-public transition-all duration-300"
        x-bind:class="navScrolled ? 'gh-nav-shell-scrolled' : ''"
        aria-label="Navigasi situs">
        <div class="gh-container gh-nav-public-inner flex items-center justify-between gap-4">
            <div class="flex min-w-0 items-center gap-6 lg:gap-10">
                <a href="{{ url('/') }}" class="gh-nav-brand min-w-0">
                    <div class="gh-nav-brand-logo">
                        <img src="{{ asset('assets') }}/logo-app/guru_hub_logo.jpeg" alt="Guru Hub"
                            class="h-full w-full object-contain">
                    </div>
                    <div class="min-w-0 leading-tight">
                        <span class="gh-nav-brand-title">Guru Hub</span>
                        <span class="gh-nav-brand-tagline">Belajar dengan para ahli</span>
                    </div>
                </a>

                <span class="gh-nav-brand-divider" aria-hidden="true"></span>

                <nav class="gh-nav-items" aria-label="Navigasi utama" role="menubar">
                    <a href="{{ url('/') }}" role="menuitem"
                        class="gh-nav-link {{ request()->is('/') ? 'gh-nav-link-active' : '' }}">
                        <x-ui.lucide name="home" class="gh-nav-icon" />
                        <span>Beranda</span>
                    </a>
                </nav>
            </div>

            <div class="gh-nav-actions">
                <div class="relative">
                    <button type="button" @click="registerOpen = !registerOpen" @click.away="registerOpen = false"
                        class="gh-nav-link inline-flex items-center gap-1"
                        x-bind:class="registerOpen ? 'gh-nav-link-active' : ''"
                        x-bind:aria-expanded="registerOpen">
                        <x-ui.lucide name="users" class="gh-nav-icon" />
                        <span>Daftar</span>
                        <span class="inline-flex transition-transform duration-200"
                            x-bind:class="registerOpen ? 'rotate-180' : ''">
                            <x-ui.lucide name="chevron-down" class="h-3.5 w-3.5" />
                        </span>
                    </button>

                    <div x-show="registerOpen" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                        class="gh-nav-dropdown gh-nav-dropdown-end">
                        <a href="{{ url('register/student') }}" class="gh-nav-dropdown-item gh-nav-dropdown-item-rich">
                            <div class="gh-nav-dropdown-icon">
                                <x-ui.lucide name="book-open" class="h-4 w-4" />
                            </div>
                            <div>
                                <span class="gh-nav-dropdown-label">Daftar sebagai Siswa</span>
                                <span class="gh-nav-dropdown-desc">Akses katalog kursus & sertifikat</span>
                            </div>
                        </a>
                        <a href="{{ url('register/teacher') }}" class="gh-nav-dropdown-item gh-nav-dropdown-item-rich">
                            <div class="gh-nav-dropdown-icon gh-nav-dropdown-icon-teal">
                                <x-ui.lucide name="award" class="h-4 w-4" />
                            </div>
                            <div>
                                <span class="gh-nav-dropdown-label">Daftar sebagai Pengajar</span>
                                <span class="gh-nav-dropdown-desc">Rilis kursus & kelola siswa</span>
                            </div>
                        </a>
                    </div>
                </div>

                <a href="{{ url('/login') }}" class="gh-btn-ghost gh-btn-sm">Masuk</a>
                <a href="{{ url('register/student') }}" class="gh-landing-btn-primary gh-btn-sm hidden lg:inline-flex">
                    Mulai belajar
                </a>
            </div>

            <button type="button" class="gh-nav-mobile-toggle" @click="mobileOpen = !mobileOpen"
                x-bind:aria-expanded="mobileOpen" aria-label="Menu navigasi">
                <span x-show="!mobileOpen"><x-ui.lucide name="menu" class="h-6 w-6" /></span>
                <span x-show="mobileOpen" x-cloak><x-ui.lucide name="x" class="h-6 w-6" /></span>
            </button>
        </div>

        <div x-show="mobileOpen" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            class="gh-nav-mobile-panel">
            <nav class="gh-nav-mobile-body" aria-label="Navigasi mobile">
                <a href="{{ url('/') }}"
                    class="gh-nav-link block {{ request()->is('/') ? 'gh-nav-link-active' : '' }}">
                    <x-ui.lucide name="home" class="gh-nav-icon" />
                    <span>Beranda</span>
                </a>
                <a href="{{ url('register/student') }}" class="gh-nav-link block">
                    <x-ui.lucide name="book-open" class="gh-nav-icon" />
                    <span>Daftar Siswa</span>
                </a>
                <a href="{{ url('register/teacher') }}" class="gh-nav-link block">
                    <x-ui.lucide name="award" class="gh-nav-icon" />
                    <span>Daftar Pengajar</span>
                </a>

                <div class="gh-nav-mobile-cta">
                    <a href="{{ url('/login') }}" class="gh-btn-ghost w-full">Masuk</a>
                    <a href="{{ url('register/student') }}" class="gh-landing-btn-primary w-full">Mulai belajar</a>
                </div>
            </nav>
        </div>
    </header>
@endif

    <main class="gh-layout-main">
        @hasSection('flush')
            @if (session()->has('success') || session()->has('error') || $errors->any())
                <div class="gh-ref-container-wide relative z-40 pt-20">
                    <x-flash-messages />
                </div>
            @endif

            @yield('content')
        @else
            <div class="gh-layout-content">
                @hasSection('header')
                    <div class="gh-page-header">
                        <h1 class="gh-page-title">@yield('header')</h1>
                    </div>
                @endif

                <x-flash-messages />

                <div class="gh-animate-slide-up">
                    @yield('content')
                </div>
            </div>
        @endif
    </main>

    @unless(View::hasSection('flush'))
    <footer class="gh-layout-footer">
        <div class="gh-container flex flex-col items-center justify-between gap-4 text-center sm:flex-row sm:text-left">
            <p class="text-sm text-brand-400">&copy; {{ date('Y') }} Guru Hub. Semua hak dilindungi.</p>
            <nav class="flex gap-6 text-sm text-brand-500" aria-label="Footer">
                <a href="{{ url('register/student') }}" class="transition-colors hover:text-brand-800">Untuk Siswa</a>
                <a href="{{ url('register/teacher') }}" class="transition-colors hover:text-brand-800">Untuk Pengajar</a>
                <a href="{{ url('/login') }}" class="transition-colors hover:text-brand-800">Masuk</a>
            </nav>
        </div>
    </footer>
    @endunless

    <x-layout.alert-dismiss />
</body>

</html>
