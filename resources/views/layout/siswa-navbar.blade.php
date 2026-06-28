<nav x-data="{ open: false }" class="gh-nav-shell" aria-label="Navigasi siswa">
    <div class="gh-container flex h-16 items-center justify-between">
        <div class="flex items-center gap-8">
            <a href="/siswa-dashboard" class="gh-nav-brand">
                <div class="gh-nav-brand-logo">
                    <img src="{{ asset('assets') }}/logo-app/guru_hub_logo.jpeg" alt="Guru Hub Logo"
                        class="h-full w-full object-contain">
                </div>
                <span class="gh-nav-brand-title">Guru Hub</span>
            </a>

            <div class="gh-nav-items" role="menubar">
                <a href="/siswa-dashboard" role="menuitem"
                    class="gh-nav-link {{ request()->is('dashboard-siswa*') || request()->is('siswa-dashboard*') ? 'gh-nav-link-active' : '' }}">
                    <x-ui.lucide name="layout-dashboard" class="gh-nav-icon" />
                    <span>Dashboard</span>
                </a>

                <a href="/tampil-kursus" role="menuitem"
                    class="gh-nav-link {{ request()->is('tampil-kursus*') ? 'gh-nav-link-active' : '' }}">
                    <x-ui.lucide name="library" class="gh-nav-icon" />
                    <span>Katalog Kursus</span>
                </a>

                <a href="/my-courses" role="menuitem"
                    class="gh-nav-link {{ request()->is('my-courses*') ? 'gh-nav-link-active' : '' }}">
                    <x-ui.lucide name="book-open" class="gh-nav-icon" />
                    <span>Kelas Saya</span>
                </a>

                <a href="/history-bookings" role="menuitem"
                    class="gh-nav-link {{ request()->is('history-bookings*') ? 'gh-nav-link-active' : '' }}">
                    <x-ui.lucide name="receipt-text" class="gh-nav-icon" />
                    <span>Riwayat Transaksi</span>
                </a>
            </div>
        </div>

        <div class="gh-nav-actions">
            <a href="/biodata" class="gh-nav-profile" title="Profil siswa">
                <div class="gh-nav-profile-meta">
                    <p class="gh-nav-profile-name">{{ Auth::user()->name ?? 'Nama Siswa' }}</p>
                    <p class="gh-nav-profile-role">{{ Auth::user()->email ?? 'student@email.com' }}</p>
                </div>
                <div class="gh-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'Student', 0, 2)) }}
                </div>
            </a>

            <a href="{{ url('/logout') }}" class="gh-nav-logout p-2" title="Keluar">
                <x-ui.lucide name="log-out" class="h-5 w-5" />
                <span class="hidden lg:inline">Keluar</span>
            </a>
        </div>

        <button type="button" class="gh-nav-mobile-toggle" @click="open = !open" :aria-expanded="open"
            aria-label="Menu navigasi">
            <span x-show="!open"><x-ui.lucide name="menu" class="h-6 w-6" /></span>
            <span x-show="open" x-cloak><x-ui.lucide name="x" class="h-6 w-6" /></span>
        </button>
    </div>

    <div x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        x-cloak
        class="gh-nav-mobile-panel">

        <div class="gh-nav-mobile-body">
            <a href="/siswa-dashboard"
                class="gh-nav-link w-full {{ request()->is('dashboard-siswa*') || request()->is('siswa-dashboard*') ? 'gh-nav-link-active' : '' }}">
                <x-ui.lucide name="layout-dashboard" class="gh-nav-icon" />
                <span>Dashboard</span>
            </a>

            <a href="/tampil-kursus"
                class="gh-nav-link w-full {{ request()->is('tampil-kursus*') ? 'gh-nav-link-active' : '' }}">
                <x-ui.lucide name="library" class="gh-nav-icon" />
                <span>Katalog Kursus</span>
            </a>

            <a href="/my-courses"
                class="gh-nav-link w-full {{ request()->is('my-courses*') ? 'gh-nav-link-active' : '' }}">
                <x-ui.lucide name="book-open" class="gh-nav-icon" />
                <span>Kelas Saya</span>
            </a>

            <a href="/history-bookings"
                class="gh-nav-link w-full {{ request()->is('history-bookings*') ? 'gh-nav-link-active' : '' }}">
                <x-ui.lucide name="receipt-text" class="gh-nav-icon" />
                <span>Riwayat Transaksi</span>
            </a>
        </div>

        <div class="gh-nav-mobile-footer">
            <div class="flex items-center gap-3 px-2 py-1">
                <div class="gh-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'Student', 0, 2)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="gh-nav-profile-name truncate">{{ Auth::user()->name ?? 'Nama Siswa' }}</p>
                    <p class="gh-nav-profile-role truncate">{{ Auth::user()->email ?? 'student@email.com' }}</p>
                </div>
            </div>
            <a href="{{ url('/logout') }}" class="gh-nav-logout mt-3 w-full">
                <x-ui.lucide name="log-out" class="h-5 w-5" />
                <span>Keluar</span>
            </a>
        </div>
    </div>
</nav>
