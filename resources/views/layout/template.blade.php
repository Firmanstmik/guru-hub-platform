<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru Hub</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-50 antialiased font-sans">

    <div class="min-h-screen flex flex-col md:flex-row">
        @if (auth()->user()->hasRole('admin'))
            @include('layout.admin-sidebar')
        @endif

        <main class="flex-1 w-full min-w-0 overflow-x-hidden">
            <div class="p-4 sm:p-6 md:p-8">
                {{-- ALERT --}}
                @if (session('success'))
                    <div
                        class="auto-dismiss-alert transform transition-all duration-700 opacity-100 translate-y-0 mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-xs font-bold text-emerald-800 flex items-center gap-3 shadow-xs">
                        <div class="p-1.5 bg-emerald-600 text-white rounded-lg shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="auto-dismiss-alert transform transition-all duration-700 opacity-100 translate-y-0 mb-4 p-4 bg-rose-50 border border-rose-200 rounded-xl text-xs font-bold text-rose-800 flex items-center gap-3 shadow-xs">
                        <div class="p-1.5 bg-rose-600 text-white rounded-lg shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div
                        class="auto-dismiss-alert transform transition-all duration-700 opacity-100 translate-y-0 mb-4 p-4 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl shadow-xs text-xs text-rose-900">
                        <div class="flex items-center gap-2 mb-2 font-black text-rose-800 uppercase tracking-wider">
                            <svg class="w-4 h-4 shrink-0 text-rose-600" fill="none" stroke="currentColor"
                                stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                            <span>Terdapat Kesalahan</span>
                        </div>
                        <ul class="space-y-1 list-disc list-inside font-medium text-rose-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- END ALERT --}}
                @yield('content')
            </div>
        </main>

    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Mengambil semua elemen alert yang memiliki class 'auto-dismiss-alert'
            const alerts = document.querySelectorAll('.auto-dismiss-alert');

            alerts.forEach(function(alert) {
                // Memberikan efek transisi CSS halus pada elemen alert
                alert.style.transition = "opacity 0.8s ease, transform 0.8s ease";
                alert.style.opacity = "1";

                // Jalankan fungsi setelah 10000 milidetik (10 detik)
                setTimeout(function() {
                    // Proses 1: Buat element menjadi transparan & sedikit bergeser ke atas
                    alert.style.opacity = "0";
                    alert.style.transform = "translateY(-10px)";

                    // Proses 2: Hapus element sepenuhnya dari layout setelah animasi fade-out selesai (800ms)
                    setTimeout(function() {
                        alert.remove();
                    }, 800);

                }, 10000); // 10000 ms = 10 detik
            });
        });
    </script>
</body>

</html>
