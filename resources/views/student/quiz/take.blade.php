@extends('layout.master-app')
@section('content')
<div class="gh-app-page">
    <div class="gh-app-page-grid" aria-hidden="true"></div>
    <div class="gh-app-page-inner space-y-4">
        <div class="gh-app-card sticky top-16 z-30 flex items-center justify-between gap-3">
            <div>
                <h3 class="gh-app-subheading">{{ $quiz->title }}</h3>
                <p class="gh-app-caption">Materi: {{ $quiz->material->title }}</p>
            </div>
            <div class="gh-app-badge gh-app-badge--danger font-mono text-[14px] px-3 py-2">⏳ <span id="quiz-timer">--:--</span></div>
        </div>

    <form id="quiz-form" action="{{ url('/quiz/'.$quiz->id.'/submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        @foreach($quiz->questions as $index => $question)
            <div class="gh-app-card space-y-4">
                <div class="flex items-start gap-3">
                    <span class="gh-app-quick-icon text-[11px] font-bold">{{ $index + 1 }}</span>
                    <div>
                        <p class="gh-app-subheading leading-relaxed">{{ $question->question_text }}</p>
                        <span class="gh-app-caption font-bold uppercase">Bobot: {{ $question->points }} Poin</span>
                    </div>
                </div>
                <div class="text-sm">
                    
                    {{-- TIPE 1: PILIHAN GANDA --}}
                    @if($question->type === 'multiple_choice')
                        <div class="grid grid-cols-1 gap-2.5">
                            @foreach($question->options as $option)
                                <label class="gh-app-card flex cursor-pointer items-center gap-3 p-3 transition hover:border-[#14B8A6]/30">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" required class="text-[#0E7490]">
                                    <span class="gh-app-body">{{ $option->option_text }}</span>
                                </label>
                            @endforeach
                        </div>

                    {{-- TIPE 2: ESSAY / JAWABAN TEKS --}}
                    @elseif($question->type === 'essay')
                        <textarea name="answers[{{ $question->id }}]" rows="4" required placeholder="Ketik jawaban Anda di sini..."
                                  class="gh-app-textarea"></textarea>

                    {{-- TIPE 3: PDF ATTACHMENT (UPLOAD FILE) --}}
                    @elseif($question->type === 'pdf_attachment')
                        <div class="space-y-3">
                            @if($question->pdf_file_path)
                                <div class="p-3 bg-indigo-50/50 border border-indigo-100 rounded-xl flex items-center justify-between">
                                    <span class="text-xs font-medium text-indigo-950">📂 Berkas Pendukung/Studi Kasus dari Guru:</span>
                                    <a href="{{ asset('storage/' . $question->pdf_file_path) }}" target="_blank" class="text-xs font-bold text-indigo-600 hover:underline">Unduh PDF ⬇️</a>
                                </div>
                            @endif
                            <div class="border border-dashed border-gray-200 rounded-xl p-4 text-center bg-gray-50/50">
                                <input type="file" name="answers[{{ $question->id }}]" accept="application/pdf" required
                                       class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="text-[10px] text-gray-400 mt-2">*Wajib mengunggah lembar jawaban dalam format berkas PDF (Max 5MB)</p>
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        @endforeach

        <div class="pt-4">
            <button type="submit" onclick="return confirm('Apakah Anda yakin semua jawaban sudah terisi dan ingin mengirimkan kuis sekarang?')"
                    class="gh-app-btn gh-app-btn-primary gh-app-btn-block">
                🔒 Selesai & Kirim Semua Jawaban
            </button>
        </div>
    </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let durationMinutes = {{ $quiz->duration_minutes }};
        let totalSeconds = durationMinutes * 60;
        const timerElement = document.getElementById('quiz-timer');
        const formElement = document.getElementById('quiz-form');

        const interval = setInterval(function() {
            let minutes = Math.floor(totalSeconds / 60);
            let seconds = totalSeconds % 60;

            // Format penulisan waktu dua digit angka (00:00)
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            timerElement.textContent = minutes + ':' + seconds;

            if (totalSeconds <= 0) {
                clearInterval(interval);
                alert('Waktu pengerjaan kuis telah habis! Sistem akan mengumpulkan jawaban Anda secara otomatis.');
                formElement.submit(); // Paksa submit otomatis jika waktu habis
            }

            totalSeconds--;
        }, 1000);
    });
</script>
@endsection