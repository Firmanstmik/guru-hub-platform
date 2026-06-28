<!DOCTYPE html>
<html lang="id">

<head>
    <x-layout.head :title="trim($__env->yieldContent('title')) ?: 'Guru Hub'" />
</head>

<body @class(['gh-auth-shell', 'gh-auth-register-mode' => View::hasSection('auth-simple')])>
    <div class="gh-auth-layout">
        @hasSection('auth-simple')
            <x-auth.brand-panel-simple
                :headline="trim($__env->yieldContent('brand-headline')) ?: 'Bergabung dengan komunitas belajar GuruHub.'"
                :subline="trim($__env->yieldContent('brand-subline')) ?: 'Daftar sekali, langsung akses katalog kursus dan materi interaktif.'" />
        @else
            <x-auth.brand-panel
                :headline="trim($__env->yieldContent('brand-headline')) ?: 'Belajar dari para ahli. Mengajar dengan dampak nyata.'"
                :subline="trim($__env->yieldContent('brand-subline')) ?: 'Platform pembelajaran premium untuk siswa, pengajar, dan institusi — dari kurikulum hingga sertifikasi.'"
                :quote="trim($__env->yieldContent('brand-quote')) ?: 'Guru Hub membantu saya menyelesaikan kurikulum intensif dengan materi yang jelas dan progress yang terukur.'"
                :author="trim($__env->yieldContent('brand-author')) ?: 'Rina Wijaya'"
                :authorRole="trim($__env->yieldContent('brand-author-role')) ?: 'Siswa — Kelas Digital Marketing'" />
        @endif

        <main class="gh-auth-main @hasSection('auth-simple') gh-auth-main--register @endif">
            <div class="gh-auth-main-inner gh-animate-slide-up @hasSection('auth-simple') gh-auth-main-inner--register @endif">
                <a href="{{ url('/') }}" class="gh-auth-back">
                    <x-ui.lucide name="chevron-down" class="h-4 w-4 -rotate-90" />
                    Kembali ke beranda
                </a>

                @unless(View::hasSection('auth-simple'))
                    <div class="gh-auth-mobile-brand">
                        <span class="gh-ref-brand-logo">
                            <img src="{{ asset('assets/logo-app/guru_hub_logo.jpeg') }}" alt="Guru Hub">
                        </span>
                    </div>

                    <x-auth.mobile-trust />
                @endunless

                <x-flash-messages />

                <div class="gh-auth-card-wrap @hasSection('auth-simple') gh-auth-card-wrap--register @endif">
                    <div class="gh-auth-card-glow" aria-hidden="true"></div>
                    <div class="gh-auth-card @hasSection('auth-simple') gh-auth-card--register @endif">
                        @hasSection('auth-header')
                            <div class="gh-auth-card-header @hasSection('auth-simple') gh-auth-card-header--register @endif">
                                @yield('auth-header')
                                @unless(View::hasSection('auth-simple'))
                                    <div class="gh-auth-divider" aria-hidden="true"><span></span></div>
                                @endunless
                            </div>
                        @endif

                        <div class="gh-auth-card-body @hasSection('auth-simple') gh-auth-card-body--register @endif">
                            @yield('content')
                        </div>

                        @hasSection('auth-footer')
                            @unless(View::hasSection('auth-simple'))
                                <div class="gh-auth-card-footer">
                                    @yield('auth-footer')
                                </div>
                            @endunless
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    <x-layout.alert-dismiss />
</body>

</html>
