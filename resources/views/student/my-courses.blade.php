@extends('layout.master-app')
@section('content')
    <div x-data="{ tab: 'active' }" class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner">
            <x-app.page-header title="Program Saya" subtitle="Akses materi dan pantau progress belajar." />

            <div class="gh-app-chip-row">
                <button type="button" @click="tab = 'active'" class="gh-app-chip" :class="tab === 'active' ? 'gh-app-chip-active' : ''">
                    Aktif ({{ $activeCourses->count() }})
                </button>
                <button type="button" @click="tab = 'completed'" class="gh-app-chip" :class="tab === 'completed' ? 'gh-app-chip-active' : ''">
                    Selesai ({{ $completedCourses->count() }})
                </button>
            </div>

            <div x-show="tab === 'active'" class="gh-app-list">
                @forelse($activeCourses as $booking)
                    <div class="gh-app-card gh-app-card-flush overflow-hidden">
                        <div class="gh-app-course-thumb !h-28">
                            <x-app.cover-image :src="$booking->course->cover_image ?? null" type="course" :alt="$booking->course->title" />
                        </div>
                        <div class="p-4">
                        <div class="flex items-start justify-between gap-2">
                            <x-app.badge variant="info">{{ $booking->course->category->name ?? 'Umum' }}</x-app.badge>
                            <span class="gh-app-caption font-mono">{{ $booking->transaction_code }}</span>
                        </div>
                        <h3 class="gh-app-subheading mt-2">{{ $booking->course->title }}</h3>
                        <p class="gh-app-caption">{{ $booking->course->teacher->name ?? 'Instruktur' }}</p>
                        <div class="mt-3 flex items-center gap-2">
                            <div class="gh-app-progress flex-1"><i style="width: {{ $booking->progress_percentage ?? 0 }}%"></i></div>
                            <span class="gh-app-caption font-bold text-[#0E7490]">{{ $booking->progress_percentage ?? 0 }}%</span>
                        </div>
                        <a href="/student/courses/{{ $booking->course_id }}/learn" class="gh-app-btn gh-app-btn-primary gh-app-btn-block mt-3">
                            <x-ui.lucide name="play" class="h-4 w-4" /> Mulai Belajar
                        </a>
                        </div>
                    </div>
                @empty
                    <x-app.empty-state icon="book-open" title="Belum ada kelas aktif" description="Selesaikan pembayaran atau daftar kelas baru." action-label="Cek Pembayaran" action-href="/history-bookings" />
                @endforelse
            </div>

            <div x-show="tab === 'completed'" x-cloak class="gh-app-list">
                @forelse($completedCourses as $booking)
                    <div class="gh-app-card opacity-90">
                        <x-app.badge variant="success">Lulus</x-app.badge>
                        <h3 class="gh-app-subheading mt-2 text-[#94A3B8] line-through">{{ $booking->course->title }}</h3>
                        <p class="gh-app-caption">{{ $booking->course->teacher->name ?? 'Instruktur' }} · {{ $booking->updated_at->format('d M Y') }}</p>
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            <a href="/student/courses/{{ $booking->course_id }}/certificate" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm">Sertifikat</a>
                            <a href="/student/courses/{{ $booking->course_id }}/review" class="gh-app-btn gh-app-btn-ghost gh-app-btn-sm">Ulasan</a>
                        </div>
                    </div>
                @empty
                    <x-app.empty-state icon="award" title="Belum ada kelas selesai" description="Selesaikan modul untuk mendapat sertifikat." />
                @endforelse
            </div>
        </div>
    </div>
@endsection
