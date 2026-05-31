<!DOCTYPE html>
<html lang="en" class="bg-gray-50/50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Siswa - Guru Hub' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="antialiased text-gray-600 bg-gray-50/30">

    <div class="min-h-screen flex flex-col">
        @if (auth()->user()->hasRole('guru'))
            @include('layout.guru-navbar')
        @elseif(auth()->user()->hasRole('siswa'))
            @include('layout.siswa-navbar')
        @endif
        <main class="flex-1 w-full mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="py-6">

                @yield('content')

            </div>
        </main>

        <footer class="bg-white border-t border-gray-100 py-4 mt-auto">
            <div class="max-w-7xl mx-auto px-4 text-center text-[11px] text-gray-400 font-medium">
                &copy; {{ date('Y') }} Guru Hub. All rights reserved.
            </div>
        </footer>

    </div>

</body>

</html>
