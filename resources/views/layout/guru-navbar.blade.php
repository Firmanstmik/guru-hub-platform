<nav x-data="{ openMobile: false, openDropdown: false }" class="gh-nav-shell gh-guru-nav" aria-label="Navigasi pengajar">
    <div class="gh-container-wide flex h-16 items-center justify-between lg:px-10">
        <div class="flex items-center gap-8">
            <a href="/guru-dashboard" class="gh-nav-brand">
                <div class="gh-nav-brand-logo">
                    <img src="{{ asset('assets') }}/logo-app/guru_hub_logo.jpeg" alt="Guru Hub Logo"
                        class="h-full w-full object-contain">
                </div>
                <span class="gh-nav-brand-title hidden sm:inline">GuruHub</span>
            </a>

            <div class="gh-nav-items" role="menubar">
                <a href="/guru-dashboard" role="menuitem"
                    class="gh-nav-link {{ request()->is('guru-dashboard*') ? 'gh-nav-link-active' : '' }}">
                    <x-ui.lucide name="layout-dashboard" class="gh-nav-icon" />
                    <span>Dashboard</span>
                </a>

                <a href="/categories" role="menuitem"
                    class="gh-nav-link {{ request()->is('guru/kelas') ? 'gh-nav-link-active' : '' }}">
                    <x-ui.lucide name="folder" class="gh-nav-icon" />
                    <span>Kelas</span>
                </a>

                <a href="/courses" role="menuitem"
                    class="gh-nav-link {{ request()->is('guru/kelola-kelas*') ? 'gh-nav-link-active' : '' }}">
                    <x-ui.lucide name="layers" class="gh-nav-icon" />
                    <span>Kelola Kelas</span>
                </a>

                <div class="relative">
                    <button type="button" @click="openDropdown = !openDropdown" @click.away="openDropdown = false"
                        class="gh-nav-link inline-flex items-center gap-1 focus:outline-none"
                        :aria-expanded="openDropdown">
                        <x-ui.lucide name="book-open" class="gh-nav-icon" />
                        <span>Fitur Mengajar</span>
                        <svg class="h-3.5 w-3.5 shrink-0 transition-transform duration-200" :class="openDropdown ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>

                    <div x-show="openDropdown" x-cloak
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="gh-nav-dropdown">
                        <a href="materials" class="gh-nav-dropdown-item">
                            <x-ui.lucide name="file-text" class="gh-nav-icon" />
                            <span>Materi</span>
                        </a>
                        <a href="videos" class="gh-nav-dropdown-item">
                            <x-ui.lucide name="video" class="gh-nav-icon" />
                            <span>Video Pembelajaran</span>
                        </a>
                        <a href="schedules" class="gh-nav-dropdown-item">
                            <x-ui.lucide name="calendar" class="gh-nav-icon" />
                            <span>Jadwal Kelas</span>
                        </a>
                        <a href="earnings" class="gh-nav-dropdown-item justify-between">
                            <span class="flex items-center gap-2.5">
                                <x-ui.lucide name="circle-dollar-sign" class="gh-nav-icon" />
                                <span>Pendapatan</span>
                            </span>
                            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-success-500"></span>
                        </a>
                        <a href="certificates" class="gh-nav-dropdown-item">
                            <x-ui.lucide name="award" class="gh-nav-icon" />
                            <span>Sertifikat</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="gh-nav-actions">
            <a href="/teachers" class="gh-nav-profile" title="Profil Pengajar">
                <div class="gh-nav-profile-meta">
                    <p class="gh-nav-profile-name">{{ Auth::user()->name ?? 'Nama Mentor' }}</p>
                    <p class="gh-nav-profile-role">Akun Pengajar</p>
                </div>
                <div class="gh-avatar gh-avatar-accent">
                    {{ strtoupper(substr(Auth::user()->name ?? 'Mentor', 0, 2)) }}
                </div>
            </a>

            <a href="{{ url('/logout') }}" class="gh-nav-logout p-2" title="Keluar">
                <x-ui.lucide name="log-out" class="h-5 w-5" />
                <span class="hidden lg:inline">Keluar</span>
            </a>
        </div>

        <div class="gh-guru-nav-mobile-actions">
            <a href="/teachers" class="gh-avatar gh-avatar-accent text-xs" title="Profil">
                {{ strtoupper(substr(Auth::user()->name ?? 'GU', 0, 2)) }}
            </a>
        </div>

        <button type="button" class="gh-nav-mobile-toggle hidden" @click="openMobile = !openMobile"
            :aria-expanded="openMobile" aria-label="Menu navigasi">
            <span x-show="!openMobile"><x-ui.lucide name="menu" class="h-6 w-6" /></span>
            <span x-show="openMobile" x-cloak><x-ui.lucide name="x" class="h-6 w-6" /></span>
        </button>
    </div>

    <div x-show="openMobile"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        x-cloak
        class="gh-nav-mobile-panel max-h-[calc(100vh-4rem)] overflow-y-auto">

        <div class="gh-nav-mobile-body">
            <a href="/guru-dashboard"
                class="gh-nav-link w-full {{ request()->is('guru-dashboard*') ? 'gh-nav-link-active' : '' }}">
                <x-ui.lucide name="layout-dashboard" class="gh-nav-icon" />
                <span>Dashboard</span>
            </a>

            <a href="/guru/kelas"
                class="gh-nav-link w-full {{ request()->is('guru/kelas') ? 'gh-nav-link-active' : '' }}">
                <x-ui.lucide name="folder" class="gh-nav-icon" />
                <span>Kelas</span>
            </a>

            <a href="/guru/kelola-kelas"
                class="gh-nav-link w-full {{ request()->is('guru/kelola-kelas*') ? 'gh-nav-link-active' : '' }}">
                <x-ui.lucide name="layers" class="gh-nav-icon" />
                <span>Kelola Kelas</span>
            </a>

            <a href="/guru/materi"
                class="gh-nav-link w-full {{ request()->is('guru/materi*') ? 'gh-nav-link-active' : '' }}">
                <x-ui.lucide name="file-text" class="gh-nav-icon" />
                <span>Materi</span>
            </a>

            <a href="/guru/video"
                class="gh-nav-link w-full {{ request()->is('guru/video*') ? 'gh-nav-link-active' : '' }}">
                <x-ui.lucide name="video" class="gh-nav-icon" />
                <span>Video Pembelajaran</span>
            </a>

            <a href="/guru/jadwal"
                class="gh-nav-link w-full {{ request()->is('guru/jadwal*') ? 'gh-nav-link-active' : '' }}">
                <x-ui.lucide name="calendar" class="gh-nav-icon" />
                <span>Jadwal Kelas</span>
            </a>

            <a href="/guru/profil"
                class="gh-nav-link w-full {{ request()->is('guru/profil*') ? 'gh-nav-link-active' : '' }}">
                <x-ui.lucide name="user" class="gh-nav-icon" />
                <span>Profil Pengajar</span>
            </a>

            <a href="/guru/pendapatan"
                class="gh-nav-link w-full {{ request()->is('guru/pendapatan*') ? 'gh-nav-link-active' : '' }}">
                <x-ui.lucide name="circle-dollar-sign" class="gh-nav-icon" />
                <span>Pendapatan Guru</span>
            </a>

            <a href="/guru/sertifikat"
                class="gh-nav-link w-full {{ request()->is('guru/sertifikat*') ? 'gh-nav-link-active' : '' }}">
                <x-ui.lucide name="award" class="gh-nav-icon" />
                <span>Sertifikat</span>
            </a>
        </div>

        <div class="gh-nav-mobile-footer">
            <div class="flex items-center gap-3 px-2 py-1">
                <div class="gh-avatar gh-avatar-accent">
                    {{ strtoupper(substr(Auth::user()->name ?? 'Mentor', 0, 2)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="gh-nav-profile-name truncate">{{ Auth::user()->name ?? 'Nama Mentor' }}</p>
                    <p class="gh-nav-profile-role truncate">instructor@email.com</p>
                </div>
            </div>
            <a href="{{ url('/logout') }}" class="gh-nav-logout mt-3 w-full">
                <x-ui.lucide name="log-out" class="h-5 w-5" />
                <span>Keluar</span>
            </a>
        </div>
    </div>
</nav>
