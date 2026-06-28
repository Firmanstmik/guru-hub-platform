<!DOCTYPE html>
<html lang="id">

<head>
    <x-layout.head title="Admin — Guru Hub" />
</head>

<body class="gh-app-shell">

    <div class="flex min-h-screen flex-col md:flex-row">
        @if (auth()->user()->hasRole('admin'))
            @include('layout.admin-sidebar')
        @endif

        <main class="gh-layout-main min-w-0 overflow-x-hidden">
            <div class="gh-layout-content-wide">
                <x-flash-messages />

                <div class="gh-animate-slide-up">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <x-layout.alert-dismiss />
</body>

</html>
