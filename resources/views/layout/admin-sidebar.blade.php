<div x-data="{ isOpen: false }" class="gh-sidebar-root">
    <div class="gh-sidebar-mobile-bar">
        <div class="flex items-center gap-2.5">
            <div class="gh-nav-brand-logo h-10 w-10">
                <img src="{{ asset('assets') }}/logo-app/guru_hub_logo.jpeg" alt="Guru Hub Logo"
                    class="h-full w-full object-contain">
            </div>
            <span class="gh-heading-md text-white">Guru Hub</span>
        </div>
        <button type="button" @click="isOpen = !isOpen" class="gh-btn-icon text-white hover:bg-brand-800"
            :aria-expanded="isOpen" aria-label="Menu sidebar">
            <span x-show="!isOpen"><x-ui.lucide name="menu" class="h-6 w-6" /></span>
            <span x-show="isOpen" x-cloak><x-ui.lucide name="x" class="h-6 w-6" /></span>
        </button>
    </div>

    <div x-show="isOpen" @click="isOpen = false" x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="gh-sidebar-overlay"></div>

    <aside :class="isOpen ? 'translate-x-0' : '-translate-x-full'" class="gh-sidebar">
        <div class="flex h-full flex-col overflow-hidden">
            <div class="gh-sidebar-header">
                <div class="gh-nav-brand-logo h-11 w-11">
                    <img src="{{ asset('assets') }}/logo-app/guru_hub_logo.jpeg" alt="Guru Hub Logo"
                        class="h-full w-full object-contain">
                </div>
                <div>
                    <h1 class="gh-sidebar-header-title">Guru Hub</h1>
                    <span class="gh-sidebar-header-subtitle">Dashboard Admin</span>
                </div>
            </div>

            <nav class="gh-sidebar-nav" aria-label="Menu admin">
                <span class="gh-sidebar-section">Utama</span>

                <a href="/admin-dashboard"
                    class="gh-sidebar-link {{ request()->is('admin-dashboard*') ? 'gh-sidebar-link-active' : '' }}">
                    <x-ui.lucide name="home" class="gh-sidebar-icon" />
                    <span>Dashboard</span>
                </a>

                <a href="/company-accounts"
                    class="gh-sidebar-link {{ request()->is('company-accounts*') ? 'gh-sidebar-link-active' : '' }}">
                    <x-ui.lucide name="credit-card" class="gh-sidebar-icon" />
                    <span>Rekening Perusahaan</span>
                </a>

                <a href="/categories"
                    class="gh-sidebar-link {{ request()->is('categories*') ? 'gh-sidebar-link-active' : '' }}">
                    <x-ui.lucide name="list" class="gh-sidebar-icon" />
                    <span>Kelas Kursus</span>
                </a>

                <a href="/courses"
                    class="gh-sidebar-link {{ request()->is('courses*') ? 'gh-sidebar-link-active' : '' }}">
                    <x-ui.lucide name="book-open" class="gh-sidebar-icon" />
                    <span>Kelola Kelas</span>
                </a>

                <a href="/users"
                    class="gh-sidebar-link {{ request()->is('users*') ? 'gh-sidebar-link-active' : '' }}">
                    <x-ui.lucide name="users" class="gh-sidebar-icon" />
                    <span>Data Users</span>
                </a>

                <a href="/teachers"
                    class="gh-sidebar-link {{ request()->is('teachers*') ? 'gh-sidebar-link-active' : '' }}">
                    <x-ui.lucide name="shield-check" class="gh-sidebar-icon" />
                    <span>Verifikasi Guru</span>
                </a>

                <a href="/student-biodata"
                    class="gh-sidebar-link {{ request()->is('student-biodata*') ? 'gh-sidebar-link-active' : '' }}">
                    <x-ui.lucide name="user-check" class="gh-sidebar-icon" />
                    <span>Verifikasi Siswa</span>
                </a>

                <span class="gh-sidebar-section">Materi Konten</span>

                <a href="/materials"
                    class="gh-sidebar-link {{ request()->is('materials*') ? 'gh-sidebar-link-active' : '' }}">
                    <x-ui.lucide name="file-text" class="gh-sidebar-icon" />
                    <span>File Modul / Materi</span>
                </a>

                <a href="/videos"
                    class="gh-sidebar-link {{ request()->is('videos*') ? 'gh-sidebar-link-active' : '' }}">
                    <x-ui.lucide name="video" class="gh-sidebar-icon" />
                    <span>Video Kursus</span>
                </a>

                <span class="gh-sidebar-section">Operasional</span>

                <a href="/course-students"
                    class="gh-sidebar-link {{ request()->is('course-students*') ? 'gh-sidebar-link-active' : '' }}">
                    <x-ui.lucide name="users-round" class="gh-sidebar-icon" />
                    <span>Pendaftaran Kelas</span>
                </a>

                <a href="/schedules"
                    class="gh-sidebar-link {{ request()->is('schedules*') ? 'gh-sidebar-link-active' : '' }}">
                    <x-ui.lucide name="calendar" class="gh-sidebar-icon" />
                    <span>Jadwal Live Class</span>
                </a>

                <a href="/bookings"
                    class="gh-sidebar-link {{ request()->is('bookings*') ? 'gh-sidebar-link-active' : '' }}">
                    <x-ui.lucide name="clipboard-check" class="gh-sidebar-icon" />
                    <span>Monitoring Booking</span>
                </a>

                <a href="/certificates"
                    class="gh-sidebar-link {{ request()->is('certificates*') ? 'gh-sidebar-link-active' : '' }}">
                    <x-ui.lucide name="badge-check" class="gh-sidebar-icon" />
                    <span>Sertifikat Kelulusan</span>
                </a>

                <a href="/reviews"
                    class="gh-sidebar-link {{ request()->is('reviews*') ? 'gh-sidebar-link-active' : '' }}">
                    <x-ui.lucide name="message-square" class="gh-sidebar-icon" />
                    <span>Ulasan Siswa</span>
                </a>

                <span class="gh-sidebar-section">Keuangan</span>

                <a href="/payments"
                    class="gh-sidebar-link {{ request()->is('payments*') ? 'gh-sidebar-link-active' : '' }}">
                    <x-ui.lucide name="credit-card" class="gh-sidebar-icon" />
                    <span>Verifikasi Pembayaran</span>
                </a>

                <a href="/earnings"
                    class="gh-sidebar-link {{ request()->is('earnings*') ? 'gh-sidebar-link-active' : '' }}">
                    <x-ui.lucide name="circle-dollar-sign" class="gh-sidebar-icon" />
                    <span>Pendapatan Guru</span>
                </a>

                <span class="gh-sidebar-section">Konfigurasi</span>

                <div x-data="{ isDropdownOpen: {{ request()->is('akses*') || request()->is('roles*') || request()->is('permissions*') || request()->is('users-manage*') ? 'true' : 'false' }} }">
                    <button type="button" @click="isDropdownOpen = !isDropdownOpen" class="gh-sidebar-dropdown-trigger">
                        <span class="flex items-center gap-3">
                            <x-ui.lucide name="settings" class="gh-sidebar-icon" />
                            <span>Settings</span>
                        </span>
                        <svg class="h-4 w-4 shrink-0 text-brand-400 transition-transform duration-200"
                            :class="isDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </button>

                    <div x-show="isDropdownOpen" x-cloak x-collapse class="gh-sidebar-dropdown-panel">
                        <a href="/akses"
                            class="gh-sidebar-dropdown-link {{ request()->is('akses*') ? 'gh-sidebar-dropdown-link-active' : '' }}">All
                            Akses</a>
                        <a href="/roles"
                            class="gh-sidebar-dropdown-link {{ request()->is('roles*') ? 'gh-sidebar-dropdown-link-active' : '' }}">Roles</a>
                        <a href="/permissions"
                            class="gh-sidebar-dropdown-link {{ request()->is('permissions*') ? 'gh-sidebar-dropdown-link-active' : '' }}">Permissions</a>
                        <a href="/users-manage"
                            class="gh-sidebar-dropdown-link {{ request()->is('users-manage*') ? 'gh-sidebar-dropdown-link-active' : '' }}">Manage
                            Roles</a>
                    </div>
                </div>
            </nav>

            <div class="gh-sidebar-footer">
                <div class="gh-sidebar-profile">
                    <div class="gh-avatar gh-avatar-accent">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="min-w-0 max-w-[140px] truncate">
                        <p class="gh-sidebar-profile-name">{{ auth()->user()->name ?? '403' }}</p>
                        <p class="gh-sidebar-profile-role">Sistem Kontrol</p>
                    </div>
                </div>
                <a href="{{ url('/logout') }}" class="gh-sidebar-logout" title="Keluar">
                    <x-ui.lucide name="log-out" class="h-5 w-5" />
                </a>
            </div>
        </div>
    </aside>
</div>
