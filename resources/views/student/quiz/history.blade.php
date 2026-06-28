@extends('layout.master-app')
@section('content')
    <div class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner">
            <x-app.page-header title="Riwayat Tugas & Kuis" subtitle="Pantau nilai evaluasi dan status koreksi dari guru." back="/siswa-dashboard" />

            @if($quizHistory->isEmpty())
                <x-app.empty-state icon="clipboard-check" title="Belum ada riwayat tugas" description="Anda belum pernah mengambil kuis atau mengumpulkan tugas." />
            @else
                <div class="gh-app-list">
                    @foreach($quizHistory as $quiz)
                        <div class="gh-app-card">
                            <x-app.badge variant="neutral">{{ $quiz->material->title }}</x-app.badge>
                            <h3 class="gh-app-subheading mt-2">{{ $quiz->title }}</h3>
                            <p class="gh-app-caption">{{ $quiz->duration_minutes }} menit · {{ \Carbon\Carbon::parse($quiz->submitted_at)->translatedFormat('d M Y, H:i') }} WIB</p>
                            <div class="mt-3">
                                @if($quiz->need_review)
                                    <x-app.badge variant="warning">⏳ Menunggu Koreksi</x-app.badge>
                                @else
                                    <div class="gh-app-stat inline-block min-w-[100px] px-4 py-2">
                                        <p class="gh-app-stat-label">Skor Anda</p>
                                        <p class="gh-app-stat-value text-[14px]">{{ $quiz->student_score }} <span class="gh-app-caption font-normal">/ {{ $quiz->max_score }}</span></p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
