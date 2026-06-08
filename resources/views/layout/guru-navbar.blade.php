<nav x-data="{ openMobile: false, openDropdown: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="flex items-center space-x-8">
                <a href="/guru-dashboard" class="flex items-center space-x-3 flex-shrink-0">
                    <div class="h-12 flex items-center justify-center">
                        <img src="{{ asset('assets') }}/logo-app/guru_hub_logo.jpeg" alt="Guru Hub Logo"
                            class="h-full rounded-full w-auto object-contain">
                    </div>
                    <div class="leading-none">
                        <span class="text-xl font-black text-gray-900 tracking-wide block">Guru Hub</span>
                    </div>
                </a>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="/guru-dashboard"
                        class="flex items-center space-x-2 px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request()->is('guru-dashboard*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <span>Dashboard</span>
                    </a>

                    <a href="/categories"
                        class="flex items-center space-x-2 px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request()->is('guru/kelas') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <span>Kelas</span>
                    </a>

                    <a href="/courses"
                        class="flex items-center space-x-2 px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request()->is('guru/kelola-kelas*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <span>Kelola Kelas</span>
                    </a>

                    <div class="relative">
                        <button @click="openDropdown = !openDropdown" @click.away="openDropdown = false"
                            class="flex items-center space-x-1 px-3 py-2 rounded-xl text-xs font-bold text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition-all focus:outline-none">
                            <span>Fitur Mengajar</span>
                            <svg class="w-3 h-3 transition-transform duration-200"
                                :class="openDropdown ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="openDropdown" x-cloak x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            class="absolute left-0 mt-2 w-48 rounded-2xl bg-white border border-gray-100 shadow-xl p-2 space-y-0.5 z-50">

                            <a href="materials"
                                class="block px-3 py-2 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 hover:text-gray-900">Materi</a>

                            <a href="videos"
                                class="block px-3 py-2 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 hover:text-gray-900">Video
                                Pembelajaran</a>

                            <a href="schedules"
                                class="block px-3 py-2 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 hover:text-gray-900">Jadwal
                                Kelas</a>

                            <a href="earnings"
                                class="block px-3 py-2 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 hover:text-gray-900 flex justify-between items-center">
                                <span>Pendapatan</span>
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            </a>

                            <a href="certificates"
                                class="block px-3 py-2 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 hover:text-gray-900">Sertifikat</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-4">
                <a href="/teachers" class="flex items-center space-x-3 border-r border-gray-100 pr-4 group"
                    title="9. Profil Pengajar">
                    <div class="overflow-hidden leading-tight text-right">
                        <h4
                            class="text-xs font-bold text-gray-900 group-hover:text-indigo-600 transition-colors truncate">
                            {{ Auth::user()->name ?? 'Nama Mentor' }}</h4>
                        <p class="text-[10px] text-gray-400 truncate">Instructor Account</p>
                    </div>
                    <div
                        class="w-8 h-8 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center font-bold text-xs text-indigo-600 flex-shrink-0 shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name ?? 'Mentor', 0, 2)) }}
                    </div>
                </a>

                <a href="{{ url('/logout') }}"
                    class="flex items-center space-x-2 p-2 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-all group"
                    title="Keluar Aplikasi">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="hidden lg:inline">Keluar</span>
                </a>
            </div>

            <div class="flex items-center md:hidden">
                <button type="button" @click="openMobile = !openMobile"
                    class="inline-flex items-center justify-center p-2 rounded-xl text-gray-400 hover:text-gray-500 hover:bg-gray-50 focus:outline-none transition-all">
                    <svg x-show="!openMobile" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="openMobile" x-cloak class="h-6 w-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <div x-show="openMobile"  x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2" x-cloak
        class="md:hidden border-t border-gray-100 bg-white max-h-[calc(100vh-4rem)] overflow-y-auto">

        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="/guru-dashboard"
                class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('guru-dashboard*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50' }}">Dashboard</a>

            <a href="/guru/kelas"
                class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('guru/kelas') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50' }}">Kelas</a>

            <a href="/guru/kelola-kelas"
                class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('guru/kelola-kelas*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50' }}">Kelola
                Kelas</a>

            <a href="/guru/materi"
                class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('guru/materi*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50' }}">Materi</a>

            <a href="/guru/video"
                class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('guru/video*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50' }}">Video
                Pembelajaran</a>

            <a href="/guru/jadwal"
                class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('guru/jadwal*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50' }}">Jadwal
                Kelas</a>

            <a href="/guru/profil"
                class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('guru/profil*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50' }}">Profil
                Pengajar</a>

            <a href="/guru/pendapatan"
                class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('guru/pendapatan*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50' }}">Pendapatan
                Guru</a>

            <a href="/guru/sertifikat"
                class="flex items-center px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('guru/sertifikat*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50' }}">Sertifikat</a>
        </div>

        <div class="pt-4 pb-3 border-t border-gray-100 bg-gray-50 px-4">
            <div class="flex items-center space-x-3 px-3 py-1">
                <div
                    class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name ?? 'Mentor', 0, 2)) }}
                </div>
                <div class="overflow-hidden leading-tight">
                    <h4 class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name ?? 'Nama Mentor' }}
                    </h4>
                    <p class="text-[10px] text-gray-400 truncate">instructor@email.com</p>
                </div>
            </div>
            <div class="mt-3 px-2">
                <a href="{{ url('/logout') }}"
                    class="flex items-center space-x-3 w-full px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Keluar</span>
                </a>
            </div>
        </div>
    </div>
</nav>
