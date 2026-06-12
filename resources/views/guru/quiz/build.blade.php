@extends('layout.master-app')
@section('content')
    <div class="p-6 max-w-5xl mx-auto space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Quiz Builder per Materi</span>
                <h3 class="text-gray-900 text-2xl font-bold mt-1">{{ $quiz->title }}</h3>
                <p class="text-xs text-gray-500 mt-1">Materi: <strong class="text-gray-700">{{ $quiz->material->title ?? 'N/A' }}</strong> | Durasi: <strong>{{ $quiz->duration_minutes }} Menit</strong></p>
            </div>
            <div class="flex gap-2">
                <a href="/quiz/{{ $quiz->id }}/review" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold px-4 py-2.5 rounded-xl transition">
                    📊 Lihat Pengumpulan Siswa
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs space-y-4 lg:col-span-1">
                <h4 class="font-bold text-gray-900 text-base border-b border-gray-50 pb-2">➕ Tambah Soal Baru</h4>
                
                <form action="/quiz/{{ $quiz->id }}/questions" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tipe Pertanyaan</label>
                        <select id="js-question-type" name="type" required
                            class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl p-2.5 focus:ring-1 focus:ring-indigo-500 focus:outline-hidden">
                            <option value="multiple_choice">Pilihan Ganda (PG)</option>
                            <option value="essay">Jawaban Esai / Teks</option>
                            <option value="pdf_attachment">Upload File Soal PDF</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Bobot Poin Soal</label>
                        <input type="number" name="points" min="1" value="10" required
                            class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl p-2.5 focus:ring-1 focus:ring-indigo-500 focus:outline-hidden">
                    </div>

                    <div id="container-question-text">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pertanyaan / Instruksi Soal</label>
                        <textarea name="question_text" rows="3" placeholder="Ketik butir soal di sini..."
                            class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl p-2.5 focus:ring-1 focus:ring-indigo-500 focus:outline-hidden"></textarea>
                    </div>

                    <div id="container-pdf-file" class="hidden">
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pilih File PDF Soal</label>
                        <input type="file" name="pdf_file" accept="application/pdf"
                            class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl p-2">
                        <p class="text-[10px] text-gray-400 mt-1">*Maksimal berkas dokumen 5MB</p>
                    </div>

                    <div id="container-options" class="space-y-3 pt-2 border-t border-dashed border-gray-100">
                        <label class="block text-xs font-bold text-indigo-600 uppercase tracking-wider">Opsi & Kunci Jawaban</label>
                        
                        @final
                        @foreach(['A', 'B', 'C', 'D'] as $index => $letter)
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="correct_option" value="{{ $index }}" {{ $index === 0 ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-xs font-bold text-gray-500">Opsi {{ $letter }}</span>
                                </div>
                                <input type="text" name="options[]" placeholder="Isi teks jawaban {{ $letter }}"
                                    class="w-full text-xs bg-gray-50 border border-gray-200 rounded-lg p-2 focus:ring-1 focus:ring-indigo-500 focus:outline-hidden">
                            </div>
                        @endforeach
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl text-xs transition shadow-xs">
                        Simpan Pertanyaan
                    </button>
                </form>
            </div>

            <div class="space-y-4 lg:col-span-2">
                <h4 class="font-bold text-gray-900 text-lg">📝 Daftar Pertanyaan Aktif ({{ $quiz->questions->count() }})</h4>
                
                @forelse($quiz->questions as $index => $question)
                    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-xs space-y-3">
                        <div class="flex justify-between items-start gap-2">
                            <div>
                                <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase mb-1">
                                    {{ str_replace('_', ' ', $question->type) }}
                                </span>
                                <p class="text-sm font-bold text-gray-900">Soal {{ $index + 1 }}. {{ $question->question_text }}</p>
                            </div>
                            <span class="text-xs font-semibold bg-gray-50 border text-gray-500 px-2 py-1 rounded-md shrink-0">
                                {{ $question->points }} Poin
                            </span>
                        </div>

                        @if($question->isMultipleChoice())
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2">
                                @foreach($question->options as $option)
                                    <div class="p-2 rounded-lg text-xs border flex items-center justify-between {{ $option->is_correct ? 'bg-emerald-50 border-emerald-100 text-emerald-800 font-semibold' : 'bg-gray-50/50 border-gray-100 text-gray-600' }}">
                                        <span>{{ $option->option_text }}</span>
                                        @if($option->is_correct) <span>🔑 Kunci</span> @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($question->isPdfAttachment() && $question->pdf_file_path)
                            <div class="pt-1">
                                <a href="{{ asset('storage/' . $question->pdf_file_path) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-indigo-600 font-medium hover:underline">
                                    📄 Buka Lampiran Berkas Soal
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white border border-dashed rounded-2xl p-12 text-center text-gray-400 italic">
                        📭 Kuis ini belum memiliki pertanyaan. Silakan tambah pertanyaan menggunakan form di sebelah kiri.
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('js-question-type');
            const containerOptions = document.getElementById('container-options');
            const containerPdf = document.getElementById('container-pdf-file');
            const questionText = document.getElementsByName('question_text')[0];

            typeSelect.addEventListener('change', function() {
                if (this.value === 'multiple_choice') {
                    containerOptions.classList.remove('hidden');
                    containerPdf.classList.add('hidden');
                    questionText.required = true;
                } else if (this.value === 'essay') {
                    containerOptions.classList.add('hidden');
                    containerPdf.classList.add('hidden');
                    questionText.required = true;
                } else if (this.value === 'pdf_attachment') {
                    containerOptions.classList.add('hidden');
                    containerPdf.classList.remove('hidden');
                    questionText.required = false; // Teks opsional karena sudah diganti file PDF
                }
            });
        });
    </script>
@endsection