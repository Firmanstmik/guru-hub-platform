@extends('layout.master-app')
@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-6">

    <div class="sticky top-4 z-40 bg-white/95 backdrop-blur-md p-5 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center gap-4">
        <div>
            <h3 class="text-gray-900 text-lg font-bold">{{ $quiz->title }}</h3>
            <p class="text-xs text-gray-400">Materi: {{ $quiz->material->title }}</p>
        </div>
        <div class="bg-rose-50 border border-rose-100 text-rose-700 font-mono font-black text-lg px-4 py-2 rounded-xl flex items-center gap-2 shrink-0">
            ⏳ <span id="quiz-timer">--:--</span>
        </div>
    </div>

    <form id="quiz-form" action="{{ url('/quiz/'.$quiz->id.'/submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        @foreach($quiz->questions as $index => $question)
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-xs space-y-4">
                
                <div class="flex items-start gap-3">
                    <span class="w-7 h-7 rounded-lg bg-gray-100 text-gray-700 font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                        {{ $index + 1 }}
                    </span>
                    <div class="space-y-1">
                        <p class="text-sm font-semibold text-gray-900 leading-relaxed">{{ $question->question_text }}</p>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Bobot: {{ $question->points }} Poin</span>
                    </div>
                </div>

                <div class="pl-10 text-sm">
                    
                    {{-- TIPE 1: PILIHAN GANDA --}}
                    @if($question->type === 'multiple_choice')
                        <div class="grid grid-cols-1 gap-2.5">
                            @foreach($question->options as $option)
                                <label class="p-3 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-indigo-50/30 hover:border-indigo-200 transition flex items-center gap-3 cursor-pointer">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" required
                                           class="text-indigo-600 focus:ring-indigo-500 w-4 h-4">
                                    <span class="text-gray-700 text-xs">{{ $option->option_text }}</span>
                                </label>
                            @endforeach
                        </div>

                    {{-- TIPE 2: ESSAY / JAWABAN TEKS --}}
                    @elseif($question->type === 'essay')
                        <textarea name="answers[{{ $question->id }}]" rows="4" required placeholder="Ketik lembar jawaban Anda di sini dengan lengkap..."
                                  class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl p-3 focus:ring-1 focus:ring-indigo-500 focus:outline-hidden leading-relaxed"></textarea>

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
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-2xl text-sm transition shadow-xs text-center">
                🔒 Selesai & Kirim Semua Jawaban
            </button>
        </div>
    </form>
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