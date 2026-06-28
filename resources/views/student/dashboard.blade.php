@extends('layout.master-app')

@section('content')
    <div x-data="{ activeTab: 'semua' }" class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner">

            <x-app.welcome-hero
                eyebrow="Ruang Belajar"
                title="Hai, {{ explode(' ', auth()->user()->name)[0] }}"
                subtitle="Lanjutkan perjalanan belajarmu — setiap langkah mendekatkan ke targetmu."
            />

            {{-- Stats --}}
            <div class="gh-app-stat-grid gh-app-stat-grid--3">
                <div class="gh-app-stat">
                    <p class="gh-app-stat-value">{{ $enrolledCoursesCount }}</p>
                    <p class="gh-app-stat-label">Kelas aktif</p>
                </div>
                <div class="gh-app-stat">
                    <p class="gh-app-stat-value">{{ $completedCoursesCount }}</p>
                    <p class="gh-app-stat-label">Selesai</p>
                </div>
                <div class="gh-app-stat">
                    <p class="gh-app-stat-value">{{ $certificatesCount }}</p>
                    <p class="gh-app-stat-label">Sertifikat</p>
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="gh-app-quick-grid">
                <a href="/tampil-kursus" class="gh-app-quick-action">
                    <span class="gh-app-quick-icon"><x-ui.lucide name="library" class="h-4 w-4" /></span>
                    <span class="gh-app-quick-label">Katalog</span>
                </a>
                <a href="/my-courses" class="gh-app-quick-action">
                    <span class="gh-app-quick-icon"><x-ui.lucide name="book-open" class="h-4 w-4" /></span>
                    <span class="gh-app-quick-label">Kelas Saya</span>
                </a>
                <a href="/history-bookings" class="gh-app-quick-action">
                    <span class="gh-app-quick-icon"><x-ui.lucide name="receipt-text" class="h-4 w-4" /></span>
                    <span class="gh-app-quick-label">Bayar</span>
                </a>
                <a href="{{ url('/quiz/history') }}" class="gh-app-quick-action">
                    <span class="gh-app-quick-icon"><x-ui.lucide name="clipboard-check" class="h-4 w-4" /></span>
                    <span class="gh-app-quick-label">Nilai</span>
                </a>
                <a href="/biodata" class="gh-app-quick-action">
                    <span class="gh-app-quick-icon"><x-ui.lucide name="user" class="h-4 w-4" /></span>
                    <span class="gh-app-quick-label">Profil</span>
                </a>
                <a href="/tampil-kursus" class="gh-app-quick-action">
                    <span class="gh-app-quick-icon"><x-ui.lucide name="search" class="h-4 w-4" /></span>
                    <span class="gh-app-quick-label">Cari</span>
                </a>
            </div>

            {{-- Continue learning --}}
            <section>
                <div class="gh-app-section-head">
                    <div>
                        <p class="gh-app-eyebrow">Progress belajar</p>
                        <h2 class="gh-app-heading mt-0.5">Lanjutkan <span class="gh-app-accent">belajar</span></h2>
                    </div>
                    <a href="/my-courses" class="gh-app-section-link">Semua <x-ui.lucide name="arrow-right" class="h-3 w-3" /></a>
                </div>
                <div class="gh-app-list mt-3">
                    @forelse($activeCourses as $course)
                        <a href="/student/courses/{{ $course->id }}/learn" class="gh-app-list-item gh-app-card-interactive">
                            <div class="gh-app-list-thumb">
                                <x-app.cover-image :src="$course->cover_image ?? null" type="course" :alt="$course->title" />
                            </div>
                            <div class="gh-app-list-body">
                                <x-app.badge variant="info" class="mb-1">{{ $course->category->name ?? 'Materi' }}</x-app.badge>
                                <h3 class="gh-app-list-title">{{ $course->title }}</h3>
                                <p class="gh-app-caption">{{ $course->teacher->name ?? 'Pengajar' }}</p>
                                <div class="mt-2 flex items-center gap-2">
                                    <div class="gh-app-progress flex-1"><i style="width: {{ $course->student_progress }}%"></i></div>
                                    <span class="gh-app-caption font-bold text-[#0E7490]">{{ $course->student_progress }}%</span>
                                </div>
                            </div>
                            <x-ui.lucide name="play" class="h-4 w-4 shrink-0 text-[#0E7490]" />
                        </a>
                    @empty
                        <x-app.empty-state icon="book-open" title="Belum ada kelas aktif" description="Daftar kursus baru dan mulai belajar." action-label="Cari Kursus" action-href="/tampil-kursus" />
                    @endforelse
                </div>
            </section>

            {{-- Schedule & agenda --}}
            <section>
                <div class="gh-app-section-head">
                    <div>
                        <p class="gh-app-eyebrow">Agenda hari ini</p>
                        <h2 class="gh-app-heading mt-0.5">Jadwal & <span class="gh-app-accent">tugas</span></h2>
                    </div>
                </div>
                <div class="gh-app-chip-row mt-3">
                    <button type="button" @click="activeTab = 'semua'" class="gh-app-chip" :class="activeTab === 'semua' ? 'gh-app-chip-active' : ''">Semua</button>
                    <button type="button" @click="activeTab = 'live'" class="gh-app-chip" :class="activeTab === 'live' ? 'gh-app-chip-active' : ''">Live Class</button>
                </div>
                <div class="gh-app-timeline mt-3">
                    @foreach ($liveClasses as $live)
                        <div x-show="activeTab === 'semua' || activeTab === 'live'" class="gh-app-timeline-item gh-app-timeline-item--today">
                            <span class="gh-app-timeline-dot"></span>
                            <div class="gh-app-timeline-card">
                                <x-app.badge variant="danger">Live Class</x-app.badge>
                                <p class="gh-app-caption mt-1">{{ \Carbon\Carbon::parse($live->date)->translatedFormat('d M · H:i') }} WIB</p>
                                <h4 class="gh-app-subheading mt-0.5">{{ $live->topic }}</h4>
                                @if ($live->link)
                                    <a href="{{ $live->link }}" target="_blank" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm mt-2 inline-flex">
                                        <x-ui.lucide name="video" class="h-3.5 w-3.5" /> Gabung Meet
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @foreach ($pendingQuizzes as $quiz)
                        <div x-show="activeTab === 'semua'" class="gh-app-timeline-item">
                            <span class="gh-app-timeline-dot"></span>
                            <div class="gh-app-timeline-card">
                                <x-app.badge variant="warning">Kuis belum selesai</x-app.badge>
                                <h4 class="gh-app-subheading mt-1">{{ $quiz->title }}</h4>
                                <p class="gh-app-caption">{{ $quiz->duration_minutes }} menit</p>
                                <a href="{{ url('/materials/' . $quiz->material_id . '/quiz') }}" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm mt-2 inline-flex">
                                    Mulai Kuis
                                </a>
                            </div>
                        </div>
                    @endforeach

                    @if ($liveClasses->isEmpty() && $pendingQuizzes->isEmpty())
                        <x-app.empty-state icon="calendar-x" title="Tidak ada agenda" description="Jadwal live class dan kuis akan muncul di sini." />
                    @endif
                </div>
            </section>

            {{-- CTA --}}
            <div class="gh-app-card text-center">
                <p class="gh-app-subheading">Siap belajar sesuatu baru?</p>
                <p class="gh-app-caption mt-1">Jelajahi katalog kursus dari pengajar terbaik.</p>
                <a href="/tampil-kursus" class="gh-app-btn gh-app-btn-primary gh-app-btn-block mt-3">
                    <x-ui.lucide name="library" class="h-4 w-4" /> Jelajahi Katalog
                </a>
            </div>

        </div>
    </div>
@endsection
