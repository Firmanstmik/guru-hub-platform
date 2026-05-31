<div x-data="{ isOpen: false }" class="relative x-cloak">
    <!-- Tombol Trigger Mobile (Muncul hanya di layar kecil) -->
    <div class="md:hidden flex items-center justify-between bg-gray-900 text-white p-4 sticky top-0 z-40 shadow-md">
        <div class="flex items-center gap-2">
            <div class="h-8 w-8 rounded-lg bg-indigo-600 flex items-center justify-center font-bold text-white">G</div>
            <span class="font-semibold text-lg tracking-wider">Guru Hub</span>
        </div>
        <button @click="isOpen = !isOpen" class="p-1 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!isOpen">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="isOpen" x-cloak>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Overlay Latar Belakang di Mobile -->
    <div x-show="isOpen" @click="isOpen = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/50 z-40 md:hidden"></div>

    <!-- Kontainer Kiri Sidebar -->
    <aside :class="isOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed md:sticky top-0 left-0 z-40 w-64 h-screen bg-gray-900 text-gray-300 flex flex-col justify-between border-r border-gray-800 transition-transform duration-300 ease-in-out md:translate-x-0">
        
        <div class="flex flex-col h-full overflow-y-auto">
            <!-- Header Brand/Logo (Desktop) -->
            <div class="hidden md:flex items-center gap-3 px-6 py-5 border-b border-gray-800 flex-shrink-0">
                <div class="h-9 w-9 rounded-xl bg-indigo-600 flex items-center justify-center font-black text-white text-xl shadow-md">G</div>
                <div>
                    <h1 class="font-bold text-white leading-tight tracking-wide">Guru Hub</h1>
                    <span class="text-xs text-gray-500">Dashboard Admin</span>
                </div>
            </div>

            <!-- List Menu Navigasi -->
            <nav class="px-4 py-6 space-y-1 flex-1">
                
                <span class="block px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Utama</span>
                
                <!-- 1. Kategori -->
                <a href="/categories" class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-sm font-medium transition group {{ request()->is('categories*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->is('categories*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Kelas Kursus
                </a>

                <!-- 1. Kursus -->
                <a href="/courses" class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-sm font-medium transition group {{ request()->is('courses*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->is('courses*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Kelola Kelas
                </a>

                <!-- 3. Users -->
                <a href="/users" class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-sm font-medium transition group {{ request()->is('users*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->is('users*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Data Users
                </a>

                <!-- 4. Verifikasi Guru (Teacher Profiles) -->
                <a href="/teachers" class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-sm font-medium transition group {{ request()->is('teachers*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->is('teachers*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Verifikasi Guru
                </a>

                <span class="block px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider pt-4 mb-2">Materi Konten</span>

                <!-- 5. Dokumen Materi -->
                <a href="/materials" class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-sm font-medium transition group {{ request()->is('materials*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->is('materials*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    File Modul / Materi
                </a>

                <!-- 6. Video Pembelajaran -->
                <a href="/videos" class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-sm font-medium transition group {{ request()->is('videos*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->is('videos*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Video Kursus
                </a>

                <span class="block px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider pt-4 mb-2">Operasional</span>

                <!-- 7. Pendaftaran Kelas (Course Students) -->
                <a href="/course-students" class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-sm font-medium transition group {{ request()->is('course-students*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->is('course-students*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Pendaftaran Kelas
                </a>

                <!-- 8. Jadwal Live Class (Schedules) -->
                <a href="/schedules" class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-sm font-medium transition group {{ request()->is('schedules*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->is('schedules*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Jadwal Live Class
                </a>

                <!-- 9. Monitoring Booking -->
                <a href="/bookings" class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-sm font-medium transition group {{ request()->is('bookings*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->is('bookings*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Monitoring Booking
                </a>

                <!-- 10. Sertifikat Kelulusan -->
                <a href="/certificates" class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-sm font-medium transition group {{ request()->is('certificates*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->is('certificates*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    Sertifikat Kelulusan
                </a>

                <!-- 11. Ulasan Siswa (Reviews) -->
                <a href="/reviews" class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-sm font-medium transition group {{ request()->is('reviews*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->is('reviews*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    Ulasan Siswa
                </a>

                <span class="block px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider pt-4 mb-2">Keuangan</span>

                <!-- 12. Pembayaran (Payments) -->
                <a href="/payments" class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-sm font-medium transition group {{ request()->is('payments*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->is('payments*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Verifikasi Pembayaran
                </a>

                <!-- 13. Pendapatan Guru (Teacher Earnings) -->
                <a href="/earnings" class="flex items-center gap-3 px-3 py-1.5 rounded-lg text-sm font-medium transition group {{ request()->is('earnings*') ? 'bg-indigo-600 text-white' : 'hover:bg-gray-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ request()->is('earnings*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Pendapatan Guru
                </a>

                <span class="block px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider pt-4 mb-2">Konfigurasi</span>

                <!-- 14. Menu Dropdown Settings -->
                <div x-data="{ isDropdownOpen: {{ request()->is('akses*') || request()->is('roles*') || request()->is('permissions*') || request()->is('users-manage*') ? 'true' : 'false' }} }">
                    <button @click="isDropdownOpen = !isDropdownOpen" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition group hover:bg-gray-800 hover:text-white">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 text-gray-400 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Settings</span>
                        </div>
                        <svg :class="isDropdownOpen ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="isDropdownOpen" x-cloak x-collapse class="pl-11 pr-2 mt-1 space-y-1">
                        <a href="/akses" class="block py-1 text-sm font-medium rounded-md transition {{ request()->is('akses*') ? 'text-white font-semibold' : 'text-gray-400 hover:text-white' }}">All Akses</a>
                        <a href="/roles" class="block py-1 text-sm font-medium rounded-md transition {{ request()->is('roles*') ? 'text-white font-semibold' : 'text-gray-400 hover:text-white' }}">Roles</a>
                        <a href="/permissions" class="block py-1 text-sm font-medium rounded-md transition {{ request()->is('permissions*') ? 'text-white font-semibold' : 'text-gray-400 hover:text-white' }}">Permissions</a>
                        <a href="/users-manage" class="block py-1 text-sm font-medium rounded-md transition {{ request()->is('users-manage*') ? 'text-white font-semibold' : 'text-gray-400 hover:text-white' }}">Manage Roles</a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Bagian Bawah Sidebar (User Profile / Logout singkat) -->
        <div class="p-4 border-t border-gray-800 bg-gray-950/40 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-2 max-w-[150px] truncate">
                <div class="h-8 w-8 rounded-full bg-gray-700 flex items-center justify-center font-bold text-sm text-indigo-400">A</div>
                <div class="truncate">
                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? '403' }}</p>
                    <p class="text-[10px] text-gray-500 truncate">Sistem Kontrol</p>
                </div>
            </div>
            <a href="{{ url('/logout') }}" class="p-1.5 rounded-md hover:bg-gray-800 text-gray-500 hover:text-rose-400 transition" title="Keluar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </a>
        </div>

    </aside>
</div>