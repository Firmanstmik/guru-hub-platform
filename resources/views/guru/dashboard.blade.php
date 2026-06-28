@extends('layout.master-app')

@section('content')
    <div x-data="{ activeTab: 'semua' }" class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner">

            <x-app.welcome-hero
                eyebrow="Panel Pengajar"
                title="Selamat datang, {{ explode(' ', auth()->user()->name)[0] }}"
                subtitle="Pantau kelas, jadwal live, dan perkembangan siswa dari satu tempat."
            />

            {{-- Quick stats --}}
            <div class="gh-app-stat-grid">
                <div class="gh-app-stat">
                    <p class="gh-app-stat-value text-[14px]">Rp {{ number_format($monthlyEarnings, 0, ',', '.') }}</p>
                    <p class="gh-app-stat-label">Omset bulan ini</p>
                </div>
                <div class="gh-app-stat">
                    <p class="gh-app-stat-value">{{ $activeStudentsCount }}</p>
                    <p class="gh-app-stat-label">Total siswa</p>
                </div>
                <div class="gh-app-stat">
                    <p class="gh-app-stat-value">{{ $releasedCoursesCount }}</p>
                    <p class="gh-app-stat-label">Kelas aktif</p>
                </div>
                <div class="gh-app-stat">
                    <p class="gh-app-stat-value">{{ number_format($averageRating, 1) }}★</p>
                    <p class="gh-app-stat-label">Rating</p>
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="gh-app-quick-grid">
                <a href="/courses" class="gh-app-quick-action">
                    <span class="gh-app-quick-icon"><x-ui.lucide name="layers" class="h-4 w-4" /></span>
                    <span class="gh-app-quick-label">Kelas</span>
                </a>
                <a href="/materials" class="gh-app-quick-action">
                    <span class="gh-app-quick-icon"><x-ui.lucide name="file-text" class="h-4 w-4" /></span>
                    <span class="gh-app-quick-label">Materi</span>
                </a>
                <a href="/videos" class="gh-app-quick-action">
                    <span class="gh-app-quick-icon"><x-ui.lucide name="video" class="h-4 w-4" /></span>
                    <span class="gh-app-quick-label">Video</span>
                </a>
                <a href="/schedules" class="gh-app-quick-action">
                    <span class="gh-app-quick-icon"><x-ui.lucide name="calendar" class="h-4 w-4" /></span>
                    <span class="gh-app-quick-label">Jadwal</span>
                </a>
                <a href="/earnings" class="gh-app-quick-action">
                    <span class="gh-app-quick-icon"><x-ui.lucide name="circle-dollar-sign" class="h-4 w-4" /></span>
                    <span class="gh-app-quick-label">Pendapatan</span>
                </a>
                <a href="/certificates" class="gh-app-quick-action">
                    <span class="gh-app-quick-icon"><x-ui.lucide name="award" class="h-4 w-4" /></span>
                    <span class="gh-app-quick-label">Sertifikat</span>
                </a>
            </div>

            {{-- Upcoming schedule --}}
            <section>
                <div class="gh-app-section-head">
                    <div>
                        <p class="gh-app-eyebrow">Live mentoring</p>
                        <h2 class="gh-app-heading mt-0.5">Jadwal <span class="gh-app-accent">mendatang</span></h2>
                    </div>
                    <a href="/schedules" class="gh-app-section-link">Semua <x-ui.lucide name="arrow-right" class="h-3 w-3" /></a>
                </div>
                <div class="gh-app-timeline mt-3">
                    @forelse($liveSchedules as $schedule)
                        @php $isToday = \Carbon\Carbon::parse($schedule->date)->isToday(); @endphp
                        <div @class(['gh-app-timeline-item', 'gh-app-timeline-item--today' => $isToday])>
                            <span class="gh-app-timeline-dot"></span>
                            <div class="gh-app-timeline-card">
                                @if ($isToday)
                                    <x-app.badge variant="info">Live hari ini</x-app.badge>
                                @else
                                    <p class="gh-app-caption">{{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('D, d M · H:i') }} WIB</p>
                                @endif
                                <h4 class="gh-app-subheading mt-1 truncate">{{ $schedule->topic }}</h4>
                                <p class="gh-app-caption">{{ $schedule->course->title ?? 'Umum' }}</p>
                                @if ($isToday && $schedule->link)
                                    <a href="{{ $schedule->link }}" target="_blank" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm mt-2 inline-flex">
                                        <x-ui.lucide name="video" class="h-3.5 w-3.5" /> Masuk Meet
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <x-app.empty-state icon="calendar-x" title="Belum ada jadwal" description="Atur jadwal live class dari menu Jadwal." action-label="Atur Jadwal" action-href="/schedules" />
                    @endforelse
                </div>
            </section>

            {{-- My courses --}}
            <section>
                <div class="gh-app-section-head">
                    <div>
                        <p class="gh-app-eyebrow">Performa kelas</p>
                        <h2 class="gh-app-heading mt-0.5">Kelas <span class="gh-app-accent">Anda</span></h2>
                    </div>
                    <a href="/courses" class="gh-app-section-link">Kelola <x-ui.lucide name="arrow-right" class="h-3 w-3" /></a>
                </div>
                <div class="gh-app-list mt-3">
                    @forelse($myCourses as $course)
                        @php $progress = min(100, max(12, $course->students_count * 12)); @endphp
                        <a href="/courses" class="gh-app-list-item gh-app-card-interactive">
                            <div class="gh-app-list-thumb">
                                <x-app.cover-image :src="$course->cover_image" type="course" :alt="$course->title" />
                            </div>
                            <div class="gh-app-list-body">
                                <p class="gh-app-caption">{{ $course->category->name ?? 'Materi' }} · {{ $course->students_count }} siswa</p>
                                <h3 class="gh-app-list-title">{{ $course->title }}</h3>
                                <div class="gh-app-progress mt-2"><i style="width: {{ $progress }}%"></i></div>
                            </div>
                            <x-ui.lucide name="arrow-right" class="h-4 w-4 shrink-0 text-[#94A3B8]" />
                        </a>
                    @empty
                        <x-app.empty-state icon="book-open" title="Belum ada kelas" description="Buat kelas pertama Anda sekarang." action-label="Buat Kelas" action-href="/courses" />
                    @endforelse
                </div>
            </section>

            {{-- Recent activity --}}
            <section>
                <div class="gh-app-section-head">
                    <div>
                        <p class="gh-app-eyebrow">Aktivitas</p>
                        <h2 class="gh-app-heading mt-0.5">Riwayat <span class="gh-app-accent">terbaru</span></h2>
                    </div>
                </div>
                <div class="gh-app-chip-row mt-3">
                    <button type="button" @click="activeTab = 'semua'" class="gh-app-chip" :class="activeTab === 'semua' ? 'gh-app-chip-active' : ''">Semua</button>
                    <button type="button" @click="activeTab = 'join'" class="gh-app-chip" :class="activeTab === 'join' ? 'gh-app-chip-active' : ''">Siswa Baru</button>
                    <button type="button" @click="activeTab = 'tugas'" class="gh-app-chip" :class="activeTab === 'tugas' ? 'gh-app-chip-active' : ''">Tugas</button>
                </div>
                <div class="gh-app-card gh-app-card-flush mt-3">
                    @forelse($activities as $act)
                        <div x-show="activeTab === 'semua' || activeTab === '{{ $act['type'] }}'" class="gh-app-activity-item">
                            <div @class([
                                'gh-app-activity-avatar',
                                'bg-blue-50 text-blue-600' => $act['type'] === 'join',
                                'bg-cyan-50 text-cyan-700' => $act['type'] !== 'join',
                            ])>{{ $act['initials'] }}</div>
                            <div class="min-w-0 flex-1">
                                <p class="gh-app-subheading truncate">{{ $act['user_name'] }}</p>
                                <p class="gh-app-caption truncate">{{ $act['description'] }}</p>
                            </div>
                            <x-app.badge :variant="$act['type'] === 'join' ? 'success' : 'info'">{{ $act['badge'] }}</x-app.badge>
                        </div>
                    @empty
                        <div class="p-4">
                            <x-app.empty-state icon="inbox" title="Belum ada aktivitas" description="Aktivitas siswa akan muncul di sini." />
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- Tip card --}}
            <div class="gh-app-card gh-app-card--dark">
                <x-app.badge variant="info">
                    <x-ui.lucide name="lightbulb" class="mr-1 inline h-3 w-3" /> Tips mentor
                </x-app.badge>
                <p class="mt-2 text-[12px] leading-relaxed text-white/75">
                    Respon ulasan tugas dalam <strong class="text-[#5EEAD4]">24 jam</strong> meningkatkan kelulusan proyek hingga <strong class="text-[#5EEAD4]">85%</strong>.
                </p>
            </div>

        </div>
    </div>
@endsection
