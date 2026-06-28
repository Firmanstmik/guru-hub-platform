@extends('layout.master-app')
@section('content')
<div class="gh-app-page">
    <div class="gh-app-page-grid" aria-hidden="true"></div>
    <div class="gh-app-page-inner">
        <div class="gh-app-card text-center space-y-4">
            <div class="gh-app-empty-icon mx-auto">📝</div>
            <x-app.badge variant="info">Evaluasi: {{ $material->title }}</x-app.badge>
            <h2 class="gh-app-heading-lg">{{ $quiz->title }}</h2>
            <p class="gh-app-body max-w-md mx-auto">{{ $quiz->description ?? 'Bacalah instruksi soal dengan seksama sebelum menjawab.' }}</p>

            <div class="gh-app-stat-grid max-w-sm mx-auto">
                <div class="gh-app-stat">
                    <p class="gh-app-stat-value text-[14px]">⏱️ {{ $quiz->duration_minutes }}</p>
                    <p class="gh-app-stat-label">Menit</p>
                </div>
                <div class="gh-app-stat">
                    <p class="gh-app-stat-value text-[14px]">{{ $quiz->questions->count() }}</p>
                    <p class="gh-app-stat-label">Soal</p>
                </div>
            </div>

            @if($alreadyTaken)
                <x-app.badge variant="danger">🔒 Anda sudah menyelesaikan kuis ini</x-app.badge>
            @else
                <a href="{{ url('/quiz/'.$quiz->id.'/take') }}" class="gh-app-btn gh-app-btn-primary gh-app-btn-block max-w-xs mx-auto">Mulai Kerjakan Sekarang 🚀</a>
                <a href="/my-courses" class="gh-app-btn gh-app-btn-ghost gh-app-btn-sm">Kembali ke Kelas</a>
            @endif
        </div>
    </div>
</div>
@endsection
