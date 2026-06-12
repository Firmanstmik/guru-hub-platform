@extends('layout.master-app')
@section('content')
    <div class="p-6 max-w-5xl mx-auto space-y-6">

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm">
                ❌ {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-xs">
            <div>
                <a href="/teacher/quiz/{{ $quiz->id }}/review" class="text-xs font-bold text-indigo-600 hover:underline">
                    ⬅️ Kembali ke Daftar Siswa
                </a>
                <h3 class="text-gray-900 text-xl font-bold mt-1">Koreksi Jawaban: {{ $student->name }}</h3>
                <p class="text-xs text-gray-500 mt-0.5">Kuis: {{ $quiz->title }}</p>
            </div>
            <div class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-right">
                <span class="text-[10px] uppercase tracking-wider text-gray-400 font-bold block">Total Skor Saat Ini</span>
                <span class="text-xl font-black text-gray-900">{{ $answers->sum('score_achieved') }} Poin</span>
            </div>
        </div>

        <div class="space-y-4">
            @foreach($answers as $index => $answer)
                <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-xs space-y-4">
                    
                    <div class="flex justify-between items-start gap-4 border-b border-gray-50 pb-3">
                        <div>
                            <span class="text-xs font-bold text-gray-400 block uppercase">Pertanyaan {{ $index + 1 }}</span>
                            <p class="text-sm font-semibold text-gray-900 mt-1">{{ $answer->question->question_text }}</p>
                        </div>
                        <span class="text-xs bg-gray-100 text-gray-600 font-bold px-2.5 py-1 rounded-md shrink-0">
                            Bobot: {{ $answer->question->points }} Poin
                        </span>
                    </div>

                    <div class="text-sm">
                        
                        {{-- 1. PILIHAN GANDA --}}
                        @if($answer->question->type === 'multiple_choice')
                            <div class="space-y-2">
                                <span class="text-xs text-gray-400 font-medium block mb-1">Pilihan Jawaban:</span>
                                @foreach($answer->question->options as $option)
                                    <div class="p-2.5 rounded-xl text-xs border flex items-center justify-between
                                        {{ $option->is_correct ? 'bg-emerald-50 border-emerald-200 text-emerald-900 font-semibold' : 'border-gray-100' }}
                                        {{ $answer->answer_text == $option->id && !$option->is_correct ? 'bg-rose-50 border-rose-200 text-rose-900 font-semibold' : '' }}">
                                        
                                        <div class="flex items-center gap-2">
                                            <span>{{ $answer->answer_text == $option->id ? '🔘' : '⚪' }}</span>
                                            <span>{{ $option->option_text }}</span>
                                        </div>

                                        @if($option->is_correct)
                                            <span class="text-[10px] uppercase tracking-wider font-bold text-emerald-700">Kunci Jawaban</span>
                                        @endif
                                        @if($answer->answer_text == $option->id && !$option->is_correct)
                                            <span class="text-[10px] uppercase tracking-wider font-bold text-rose-700">Pilihan Siswa (Salah)</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-3 p-3 rounded-xl flex items-center justify-between text-xs font-bold bg-gray-50 border border-gray-100">
                                <span class="text-gray-500">Hasil Penilaian Otomatis Sistem:</span>
                                <span class="{{ $answer->is_correct ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $answer->is_correct ? '✅ Benar (+'.$answer->score_achieved.' Poin)' : '❌ Salah (+0 Poin)' }}
                                </span>
                            </div>

                        {{-- 2. ESSAY --}}
                        @elseif($answer->question->type === 'essay')
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-3">
                                <span class="text-[10px] uppercase tracking-wider font-bold text-gray-400 block mb-1">Jawaban Teks Siswa:</span>
                                <p class="text-gray-800 whitespace-pre-line text-sm leading-relaxed">{{ $answer->answer_text }}</p>
                            </div>

                        {{-- 3. PDF ATTACHMENT --}}
                        @elseif($answer->question->type === 'pdf_attachment')
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3 bg-indigo-50/50 border border-indigo-100 rounded-xl">
                                <div>
                                    <span class="text-[10px] uppercase tracking-wider font-bold text-indigo-500 block">Lampiran Dokumen Jawaban:</span>
                                    <span class="text-xs font-semibold text-gray-700 truncate max-w-xs block mt-0.5">Dokumen_Siswa.pdf</span>
                                </div>
                                <a href="{{ asset('storage/' . $answer->answer_text) }}" target="_blank" 
                                   class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition shrink-0 shadow-2xs">
                                    📂 Buka & Baca File PDF
                                </a>
                            </div>
                        @endif

                    </div>

                    {{-- BOX PENILAIAN MANUAL GURU (Hanya tampil untuk Essay dan PDF) --}}
                    @if($answer->question->type !== 'multiple_choice')
                        <div class="mt-4 pt-4 border-t border-dashed border-gray-100 bg-gray-50/50 p-4 rounded-xl">
                            <form action="/teacher/quiz/answer/{{ $answer->id }}/grade" method="POST" class="flex flex-wrap items-end gap-4">
                                @csrf
                                
                                <div class="w-32">
                                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Input Nilai</label>
                                    <input type="number" name="score" min="0" max="{{ $answer->question->points }}" 
                                           value="{{ $answer->score_achieved }}" required
                                           class="w-full text-sm bg-white border border-gray-200 rounded-lg p-2 focus:ring-1 focus:ring-indigo-500">
                                </div>

                                <div class="w-40">
                                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Status Kelulusan</label>
                                    <select name="is_correct" required class="w-full text-sm bg-white border border-gray-200 rounded-lg p-2 focus:ring-1 focus:ring-indigo-500">
                                        <option value="1" {{ $answer->is_correct === true ? 'selected' : '' }}>✅ Benar / Lulus</option>
                                        <option value="0" {{ $answer->is_correct === false ? 'selected' : '' }}>❌ Salah / Gagal</option>
                                        <option value="" {{ is_null($answer->is_correct) ? 'selected' : '' }} disabled>-- Pilih Status --</option>
                                    </select>
                                </div>

                                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold px-4 py-2.5 rounded-lg transition shadow-xs">
                                    Simpan Nilai Soal Ini
                                </button>

                                @if(!is_null($answer->is_correct))
                                    <span class="text-xs text-emerald-600 font-bold ml-auto mb-2.5">
                                        ✓ Sudah Dinilai
                                    </span>
                                @endif
                            </form>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

    </div>
@endsection