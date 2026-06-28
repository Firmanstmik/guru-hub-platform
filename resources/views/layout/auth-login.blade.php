<!DOCTYPE html>
<html lang="id">

<head>
    <x-layout.head :title="trim($__env->yieldContent('title')) ?: 'Masuk — Guru Hub'" />
</head>

<body class="gh-login-shell">
    <div class="gh-login-layout">
        <x-auth.login-brand />

        <main class="gh-login-main gh-auth-main">
            <div class="gh-login-main-inner gh-auth-main-inner gh-animate-slide-up">
                <div class="gh-login-mobile-header">
                    <a href="{{ url('/') }}" class="gh-login-back">← Beranda</a>
                    <span class="gh-ref-brand-logo" style="width:2.75rem;height:2.75rem">
                        <img src="{{ asset('assets/logo-app/guru_hub_logo.jpeg') }}" alt="Guru Hub">
                    </span>
                </div>

                <a href="{{ url('/') }}" class="gh-login-back mb-6 hidden lg:inline-flex">
                    <x-ui.lucide name="chevron-down" class="h-4 w-4 -rotate-90" />
                    Kembali ke beranda
                </a>

                <x-flash-messages />

                <div class="gh-auth-card-wrap">
                    <div class="gh-auth-card-glow" aria-hidden="true"></div>
                    <div class="gh-auth-card">
                        <div class="gh-auth-card-header">
                            @hasSection('login-header')
                                @yield('login-header')
                            @else
                                <h2 class="gh-login-title">Masuk ke GuruHub</h2>
                                <p class="gh-login-subtitle">Silakan masuk ke akun Anda untuk melanjutkan belajar</p>
                            @endif
                            <div class="gh-auth-divider" aria-hidden="true"><span></span></div>
                        </div>

                        <div class="gh-auth-card-body gh-login-form">
                            @yield('content')
                        </div>

                        @hasSection('login-footer')
                            <div class="gh-auth-card-footer gh-login-footer">
                                @yield('login-footer')
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
    </div>

    <x-layout.alert-dismiss />
</body>

</html>
