@php
    $user = auth()->user();
    $isGuru = $user->hasRole('guru');

    $guruItems = [
        ['href' => '/guru-dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard', 'active' => request()->is('guru-dashboard*')],
        ['href' => '/categories', 'icon' => 'folder', 'label' => 'Kategori Kelas', 'active' => request()->is('categories*')],
        ['href' => '/courses', 'icon' => 'layers', 'label' => 'Kelola Kelas', 'active' => request()->is('courses*')],
        ['href' => '/materials', 'icon' => 'file-text', 'label' => 'Materi', 'active' => request()->is('materials*')],
        ['href' => '/videos', 'icon' => 'video', 'label' => 'Video', 'active' => request()->is('videos*')],
        ['href' => '/schedules', 'icon' => 'calendar', 'label' => 'Jadwal Mengajar', 'active' => request()->is('schedules*')],
        ['href' => '/earnings', 'icon' => 'circle-dollar-sign', 'label' => 'Pendapatan', 'active' => request()->is('earnings*')],
        ['href' => '/certificates', 'icon' => 'award', 'label' => 'Sertifikat', 'active' => request()->is('certificates*')],
        ['href' => '/teachers', 'icon' => 'user', 'label' => 'Profil Pengajar', 'active' => request()->is('teachers*')],
    ];

    $siswaItems = [
        ['href' => '/siswa-dashboard', 'icon' => 'layout-dashboard', 'label' => 'Beranda', 'active' => request()->is('siswa-dashboard*')],
        ['href' => '/tampil-kursus', 'icon' => 'library', 'label' => 'Katalog Kursus', 'active' => request()->is('tampil-kursus*')],
        ['href' => '/my-courses', 'icon' => 'book-open', 'label' => 'Program Saya', 'active' => request()->is('my-courses*')],
        ['href' => '/history-bookings', 'icon' => 'receipt-text', 'label' => 'Riwayat Pembayaran', 'active' => request()->is('history-bookings*')],
        ['href' => '/quiz/history', 'icon' => 'file-text', 'label' => 'Nilai Kuis', 'active' => request()->is('quiz/history*')],
        ['href' => '/biodata', 'icon' => 'user', 'label' => 'Profil & Biodata', 'active' => request()->is('biodata*')],
    ];

    $items = $isGuru ? $guruItems : $siswaItems;
@endphp

<div x-show="drawerOpen" x-cloak class="lg:hidden">
    <div class="gh-app-drawer-backdrop" @click="drawerOpen = false"
        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

    <aside class="gh-app-drawer" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full" role="dialog" aria-label="Menu navigasi">
        <div class="gh-app-drawer-header">
            <div class="flex items-center gap-3">
                <x-app.user-avatar :user="$user" size="md" />
                <div class="min-w-0">
                    <p class="gh-app-subheading truncate">{{ $user->name }}</p>
                    <p class="gh-app-caption truncate">{{ $isGuru ? 'Akun Pengajar' : 'Akun Siswa' }}</p>
                </div>
            </div>
            <button type="button" class="gh-app-icon-btn" @click="drawerOpen = false" aria-label="Tutup">
                <x-ui.lucide name="x" class="h-4 w-4" />
            </button>
        </div>

        <nav class="gh-app-drawer-body">
            @foreach ($items as $item)
                <a href="{{ $item['href'] }}" @class(['gh-app-drawer-item', 'gh-app-drawer-item-active' => $item['active']])
                    @click="drawerOpen = false">
                    <span class="gh-app-drawer-icon">
                        <x-ui.lucide :name="$item['icon']" class="h-4 w-4" />
                    </span>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="gh-app-drawer-footer">
            <a href="{{ url('/logout') }}" class="gh-app-btn gh-app-btn-secondary gh-app-btn-block">
                <x-ui.lucide name="log-out" class="h-4 w-4" />
                Keluar
            </a>
        </div>
    </aside>
</div>
