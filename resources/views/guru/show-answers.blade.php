@extends('layout.master-app')
@section('content')
    <p>Kunci Jawaban Benar:
        <strong>{{ $answer->question->options->where('is_correct', true)->first()->option_text }}</strong></p>

    @if ($answer->question->isMultipleChoice())
        <p>Jawaban Siswa: {{ $answer->chosenOption->option_text ?? 'Tidak menjawab' }}</p>

        @elif($answer->question->isEssay())
        <p>Jawaban Siswa: {{ $answer->answer_text }}</p>

        @elif($answer->question->isPdfAttachment())
        <a href="{{ asset('storage/' . $answer->answer_text) }}" target="_blank" class="text-blue-600 underline">
            📁 Buka Dokumen PDF Siswa
        </a>
    @endif
@endsection
