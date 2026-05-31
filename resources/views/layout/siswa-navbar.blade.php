<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <div class="flex items-center space-x-8">
                <a href="/dashboard" class="flex items-center space-x-3 flex-shrink-0">
                    <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black text-sm shadow-sm">
                        G
                    </div>
                    <div class="leading-none">
                        <span class="text-xl font-black text-gray-900 tracking-wide block">Guru Hub</span>
                    </div>
                </a>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="/dashboard" 
                       class="flex items-center space-x-2 px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request()->is('dashboard-siswa*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a href="/tampil-kursus" 
                       class="flex items-center space-x-2 px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request()->is('tampil-kursus*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Katalog Kursus</span>
                    </a>

                    <a href="/my-courses" 
                       class="flex items-center space-x-2 px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request()->is('my-courses*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span>Kelas Saya</span>
                    </a>

                    <a href="/history-bookings" 
                       class="flex items-center space-x-2 px-3 py-2 rounded-xl text-xs font-bold transition-all {{ request()->is('history-bookings*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span>Riwayat Transaksi</span>
                    </a>
                </div>
            </div>

            <div class="hidden md:flex items-center space-x-4">
                <div class="flex items-center space-x-3 border-r border-gray-100 pr-4">
                    <div class="overflow-hidden leading-tight text-right">
                        <h4 class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name ?? 'Nama Siswa' }}</h4>
                        <p class="text-[10px] text-gray-400 truncate">{{ Auth::user()->email ?? 'student@email.com' }}</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center font-bold text-xs text-gray-700 flex-shrink-0">
                        {{ strtoupper(substr(Auth::user()->name ?? 'Student', 0, 2)) }}
                    </div>
                </div>

                <a href="{{ url('/logout') }}"
                   class="flex items-center space-x-2 p-2 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-all group"
                   title="Keluar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="hidden lg:inline">Keluar</span>
                </a>
            </div>

            <div class="flex items-center md:hidden">
                <button type="button" @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-400 hover:text-gray-500 hover:bg-gray-50 focus:outline-none transition-all">
                    <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         x-cloak
         class="md:hidden border-t border-gray-100 bg-white">
        
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="/dashboard" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('dashboard-siswa*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="/tampil-kursus" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('tampil-kursus*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span>Katalog Kursus</span>
            </a>

            <a href="/my-courses" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('my-courses*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>Kelas Saya</span>
            </a>

            <a href="/history-bookings" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all {{ request()->is('history-bookings*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:bg-gray-50' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Riwayat Transaksi</span>
            </a>
        </div>

        <div class="pt-4 pb-3 border-t border-gray-100 bg-gray-50 px-4">
            <div class="flex items-center space-x-3 px-3 py-1">
                <div class="w-8 h-8 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center font-bold text-xs text-gray-700 flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name ?? 'Student', 0, 2)) }}
                </div>
                <div class="overflow-hidden leading-tight">
                    <h4 class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name ?? 'Nama Siswa' }}</h4>
                    <p class="text-[10px] text-gray-400 truncate">{{ Auth::user()->email ?? 'student@email.com' }}</p>
                </div>
            </div>
            <div class="mt-3 px-2">
                <a href="{{ url('/logout') }}" class="flex items-center space-x-3 w-full px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-all">
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