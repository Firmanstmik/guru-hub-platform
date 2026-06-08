<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Guru Hub - Platform Ekosistem Kelas Modern')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-white min-h-screen text-slate-900 flex flex-col">

    <nav class="bg-white text-slate-900 shadow relative z-50">
        <div class="container mx-auto px-6 py-1.5 flex items-center justify-between">

            <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                <div
                    class="w-12 h-12 rounded-xl overflow-hidden shadow-sm shadow-indigo-600/10 group-hover:scale-105 transition duration-300">
                    <img src="{{ asset('assets') }}/logo-app/guru_hub_logo.jpeg" alt="Guru Hub Logo"
                        class="h-full rounded-full w-auto object-contain">
                </div>
                <div class="leading-none">
                    <span class="text-xl font-black text-gray-900 tracking-wide block">Guru Hub</span>
                </div>
            </a>

            <div class="hidden md:flex items-center space-x-1 font-semibold text-xs uppercase tracking-wider">
                <a href="{{ url('/') }}"
                    class="px-3 py-2 rounded-xl text-gray-600 hover:text-indigo-600 hover:bg-indigo-50/50 transition">
                    Home
                </a>

                <div class="relative id-dropdown-wrapper">
                    {{-- Tambahkan id="registerBtn" dan hapus class "group" di pembungkus --}}
                    <button type="button" id="registerBtn"
                        class="px-3 py-2 rounded-xl text-gray-600 hover:text-indigo-600 hover:bg-indigo-50/50 transition inline-flex items-center gap-1 focus:outline-none">
                        <span>Register</span>
                        {{-- Durasi rotasi transisi ikon panah --}}
                        <svg id="registerArrow" class="w-3 h-3 transition-transform duration-200" fill="none"
                            stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    {{-- Hapus "group-hover:block" dari class di bawah ini --}}
                    <div id="registerDropdown"
                        class="absolute right-0 mt-1 w-44 bg-white border border-gray-100 rounded-xl shadow-xl py-2 hidden transition animate-fade-in z-50">
                        <a href="{{ url('register/student') }}"
                            class="block px-4 py-2 text-[11px] text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 font-bold">Daftar
                            Jadi Siswa</a>
                        <a href="{{ url('register/teacher') }}"
                            class="block px-4 py-2 text-[11px] text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 font-bold">Daftar
                            Jadi Guru</a>
                    </div>
                </div>

                <a href="{{ url('/login') }}"
                    class="px-4 py-2 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-xs transition normal-case font-bold">
                    Login
                </a>
            </div>

            <button id="mobile-menu-btn" type="button"
                class="md:hidden p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                <svg id="menu-icon-open" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
                <svg id="menu-icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div id="mobile-menu-container"
            class="hidden md:hidden border-t border-gray-100 bg-white shadow-lg absolute inset-x-0 top-full px-6 py-4 space-y-3 font-semibold text-xs uppercase tracking-wider">
            <a href="{{ url('/') }}" class="block py-2 rounded-xl text-gray-600 hover:text-indigo-600 transition">
                Home
            </a>
            <div class="border-y border-gray-50 py-1 space-y-2">
                <span class="block text-[10px] text-gray-400 tracking-widest font-bold pt-1">Registrasi Akun</span>
                <a href="{{ url('register/student') }}"
                    class="block py-1 pl-2 text-gray-600 hover:text-indigo-600 normal-case font-bold">➔ Daftar Sebagai
                    Siswa</a>
                <a href="{{ url('register/teacher') }}"
                    class="block py-1 pl-2 text-gray-600 hover:text-emerald-700 normal-case font-bold">➔ Daftar Sebagai
                    Guru</a>
            </div>
            <a href="{{ url('/login') }}"
                class="block text-center py-2.5 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 font-bold transition normal-case">
                Login
            </a>
        </div>
    </nav>

    <div class="max-w-6xl w-full mx-auto p-6 flex-grow">
        <h1 class="text-2xl font-bold mb-4">@yield('header')</h1>

        {{-- ALERT NOTIFIKASI --}}
        @if (session('success'))
            <div
                class="auto-dismiss-alert transform transition-all duration-700 opacity-100 translate-y-0 mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-xs font-bold text-emerald-800 flex items-center gap-3 shadow-xs">
                <div class="p-1.5 bg-emerald-600 text-white rounded-lg shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
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
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
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
                    <svg class="w-4 h-4 shrink-0 text-rose-600" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btn = document.getElementById('registerBtn');
            const dropdown = document.getElementById('registerDropdown');
            const arrow = document.getElementById('registerArrow');

            // Toggle dropdown saat tombol diklik
            btn.addEventListener('click', function(e) {
                e.stopPropagation(); // Mencegah event bubbling ke dokumen
                dropdown.classList.toggle('hidden');
                arrow.classList.toggle('rotate-180');
            });

            // Tutup dropdown secara otomatis jika pengguna mengklik area di luar menu
            document.addEventListener('click', function(e) {
                if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                    arrow.classList.remove('rotate-180');
                }
            });

            // 2. Logika Toggle Menu Mobile Responsive
            const menuBtn = document.getElementById('mobile-menu-btn');
            const menuContainer = document.getElementById('mobile-menu-container');
            const iconOpen = document.getElementById('menu-icon-open');
            const iconClose = document.getElementById('menu-icon-close');

            menuBtn.addEventListener('click', function() {
                const isHidden = menuContainer.classList.contains('hidden');
                if (isHidden) {
                    menuContainer.classList.remove('hidden');
                    iconOpen.classList.add('hidden');
                    iconClose.classList.remove('hidden');
                } else {
                    menuContainer.classList.add('hidden');
                    iconOpen.classList.remove('hidden');
                    iconClose.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>
