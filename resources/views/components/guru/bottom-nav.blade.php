<nav class="gh-guru-bottom-nav" aria-label="Navigasi utama pengajar">
    <div class="gh-guru-bottom-nav-inner">
        <a href="/guru-dashboard" @class(['gh-guru-tab', 'gh-guru-tab-active' => request()->is('guru-dashboard*')])>
            <x-ui.lucide name="layout-dashboard" class="gh-guru-tab-icon" />
            <span>Home</span>
        </a>
        <a href="/courses" @class(['gh-guru-tab', 'gh-guru-tab-active' => request()->is('courses*')])>
            <x-ui.lucide name="layers" class="gh-guru-tab-icon" />
            <span>Kelas</span>
        </a>
        <a href="/materials" @class(['gh-guru-tab', 'gh-guru-tab-active' => request()->is('materials*')])>
            <x-ui.lucide name="book-open" class="gh-guru-tab-icon" />
            <span>Materi</span>
        </a>
        <a href="/schedules" @class(['gh-guru-tab', 'gh-guru-tab-active' => request()->is('schedules*')])>
            <x-ui.lucide name="calendar" class="gh-guru-tab-icon" />
            <span>Jadwal</span>
        </a>
        <a href="/teachers" @class(['gh-guru-tab', 'gh-guru-tab-active' => request()->is('teachers*')])>
            <x-ui.lucide name="user" class="gh-guru-tab-icon" />
            <span>Profil</span>
        </a>
    </div>
</nav>
