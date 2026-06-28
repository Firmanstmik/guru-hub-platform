@php
    $isGuru = auth()->user()->hasRole('guru');

    $guruTabs = [
        ['href' => '/guru-dashboard', 'icon' => 'layout-dashboard', 'label' => 'Home', 'active' => request()->is('guru-dashboard*')],
        ['href' => '/courses', 'icon' => 'layers', 'label' => 'Kelas', 'active' => request()->is('courses*') || request()->is('categories*')],
        ['href' => '/materials', 'icon' => 'book-open', 'label' => 'Materi', 'active' => request()->is('materials*') || request()->is('videos*')],
        ['href' => '/schedules', 'icon' => 'calendar', 'label' => 'Jadwal', 'active' => request()->is('schedules*')],
        ['href' => '/teachers', 'icon' => 'user', 'label' => 'Profil', 'active' => request()->is('teachers*')],
    ];

    $siswaTabs = [
        ['href' => '/siswa-dashboard', 'icon' => 'layout-dashboard', 'label' => 'Home', 'active' => request()->is('siswa-dashboard*')],
        ['href' => '/my-courses', 'icon' => 'book-open', 'label' => 'Kelas', 'active' => request()->is('my-courses*') || request()->is('student/courses*')],
        ['href' => '/tampil-kursus', 'icon' => 'library', 'label' => 'Katalog', 'active' => request()->is('tampil-kursus*') || request()->is('bookings*')],
        ['href' => '/history-bookings', 'icon' => 'receipt-text', 'label' => 'Bayar', 'active' => request()->is('history-bookings*') || request()->is('payments-class*')],
        ['href' => '/biodata', 'icon' => 'user', 'label' => 'Profil', 'active' => request()->is('biodata*')],
    ];

    $tabs = $isGuru ? $guruTabs : $siswaTabs;
@endphp

<nav class="gh-app-bottom-nav" aria-label="Navigasi utama">
    <div class="gh-app-bottom-nav-dock">
        <div class="gh-app-bottom-nav-inner gh-app-bottom-nav-inner--5">
            @foreach ($tabs as $tab)
                <a href="{{ $tab['href'] }}" @class(['gh-app-tab', 'gh-app-tab-active' => $tab['active']])>
                    <span class="gh-app-tab-icon-wrap" aria-hidden="true">
                        <x-ui.lucide :name="$tab['icon']" class="gh-app-tab-icon" />
                    </span>
                    <span class="gh-app-tab-label">{{ $tab['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</nav>
