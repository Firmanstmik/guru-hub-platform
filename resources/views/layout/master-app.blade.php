<!DOCTYPE html>
<html lang="id">

<head>
    <x-layout.head :title="$title ?? 'Guru Hub'" />
</head>

<body @class([
    'gh-app-shell gh-mobile-app' => auth()->user()->hasAnyRole(['guru', 'siswa']),
    'gh-guru-app' => auth()->user()->hasRole('guru'),
])>

    @if (auth()->user()->hasAnyRole(['guru', 'siswa']))
        <x-app.mobile-header
            :title="$headerTitle ?? null"
            :eyebrow="$headerEyebrow ?? null"
            :show-search="$showSearch ?? false"
            :search-action="$searchAction ?? null"
            :search-placeholder="$searchPlaceholder ?? 'Cari...'"
        />
        <x-app.bottom-nav />
    @endif

    <main class="gh-layout-main">
        <div class="gh-layout-content">
            <x-flash-messages />

            <div class="gh-animate-slide-up">
                @yield('content')
            </div>
        </div>
    </main>

    @stack('app-modals')

    <x-layout.alert-dismiss />
    @stack('app-scripts')
</body>

</html>
