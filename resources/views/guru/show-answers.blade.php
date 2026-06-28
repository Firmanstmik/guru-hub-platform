@extends('layout.master-app')
@section('content')
    <div class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner space-y-4">
            <x-app.page-header title="Detail Jawaban" subtitle="Kunci jawaban dan respons siswa." />

            <div class="gh-app-card space-y-4">
                <div>
                    <p class="gh-app-label mb-1">Kunci Jawaban Benar</p>
                    <p class="text-sm font-bold text-[#0A1A4F]">
                        {{ $answer->question->options->where('is_correct', true)->first()->option_text }}
                    </p>
                </div>

                @if ($answer->question->isMultipleChoice())
                    <div class="border-t border-[#0A1A4F]/[0.06] pt-4">
                        <p class="gh-app-label mb-1">Jawaban Siswa</p>
                        <p class="text-sm text-gray-700">{{ $answer->chosenOption->option_text ?? 'Tidak menjawab' }}</p>
                    </div>

                @elif($answer->question->isEssay())
                    <div class="border-t border-[#0A1A4F]/[0.06] pt-4">
                        <p class="gh-app-label mb-1">Jawaban Siswa</p>
                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $answer->answer_text }}</p>
                    </div>

                @elif($answer->question->isPdfAttachment())
                    <div class="border-t border-[#0A1A4F]/[0.06] pt-4">
                        <p class="gh-app-label mb-2">Dokumen PDF Siswa</p>
                        <a href="{{ asset('storage/' . $answer->answer_text) }}" target="_blank"
                            class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm inline-flex">
                            📁 Buka Dokumen PDF Siswa
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
