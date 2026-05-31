@extends('layout.master-app')
@section('content')
    <div class="p-6 max-w-2xl mx-auto space-y-6">
        
        <!-- Tombol Kembali -->
        <div>
            <a href="/my-courses" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-indigo-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Ruang Belajar
            </a>
        </div>

        <!-- Box Form Utama -->
        <div class="bg-white border border-gray-100 shadow-2xs rounded-2xl p-6 md:p-8 space-y-6">
            
            <!-- Informasi Judul -->
            <div class="border-b border-gray-50 pb-4 space-y-1">
                <h1 class="text-lg font-extrabold text-gray-900">Berikan Ulasan & Penilaian</h1>
                <p class="text-xs text-gray-400">Bagikan pengalaman belajar Anda untuk membantu meningkatkan kualitas pengajaran instruktur.</p>
            </div>

            <!-- Detail Identitas Kelas & Guru -->
            <div class="p-4 bg-indigo-50/50 border border-indigo-100/40 rounded-xl flex items-start gap-4">
                <div class="w-10 h-10 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shrink-0">
                    {{-- {{ uppercase(substr($course->title, 0, 2)) }} --}}
                    {{ \Illuminate\Support\Str::upper(substr($course->title, 0, 2)) }}
                </div>
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">{{ $course->category->name ?? 'Premium Course' }}</span>
                    <h3 class="text-sm font-bold text-gray-900 leading-tight">{{ $course->title }}</h3>
                    <p class="text-xs text-gray-400">Mengajar Oleh: <span class="text-gray-600 font-medium">{{ $course->teacher->name ?? 'Instruktur' }}</span></p>
                </div>
            </div>

            <!-- Formulir Kirim -->
            <form action="{{ url('/student/reviews') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Lemparan Hidden ID Relasi -->
                <input type="hidden" name="course_id" value="{{ $course->id }}">
                <input type="hidden" name="teacher_id" value="{{ $course->teacher_id }}">

                <!-- INPUT INTERAKTIF RATING BINTANG -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Beri Skor Rating Anda <span class="text-rose-500">*</span></label>
                    
                    <div class="flex items-center gap-1">
                        <!-- Input radio disembunyikan, digantikan elemen visual SVG -->
                        <input type="radio" name="rating" id="rating-1" value="1" class="hidden" required>
                        <input type="radio" name="rating" id="rating-2" value="2" class="hidden">
                        <input type="radio" name="rating" id="rating-3" value="3" class="hidden">
                        <input type="radio" name="rating" id="rating-4" value="4" class="hidden">
                        <input type="radio" name="rating" id="rating-5" value="5" class="hidden">

                        <!-- Komponen Iterasi Bintang -->
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" onmouseover="hoverRating({{ $i }})" onmouseleave="resetRating()" class="star-btn p-1 text-gray-200 hover:scale-110 transition duration-150 focus:outline-hidden" data-star="{{ $i }}">
                                <svg class="w-7 h-7 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </button>
                        @endfor

                        <!-- Deskripsi Teks Rating Dinamis -->
                        <span id="rating-text" class="text-xs font-bold text-gray-400 ml-2">Pilih Bintang</span>
                    </div>
                    @error('rating')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- KOTAK ISI TESTIMONI -->
                <div class="space-y-1.5">
                    <label for="review_comment" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Pesan Testimoni / Ulasan <span class="text-rose-500">*</span></label>
                    <textarea name="comment" id="review_comment" rows="5" required minlength="10" maxlength="1000"
                        placeholder="Tuliskan ulasan Anda mengenai metode penyampaian materi, kejelasan penjelasan, atau kesan umum terhadap instruktur pengajar..."
                        class="w-full px-4 py-3 text-sm bg-white border border-gray-200 rounded-xl focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-gray-700 placeholder:text-gray-400 leading-relaxed">{{ old('comment') }}</textarea>
                    
                    <div class="flex justify-between items-center text-[10px] text-gray-400">
                        <span>Minimal 10 karakter tulisan.</span>
                        <span id="char-counter">0 / 1000 Karakter</span>
                    </div>
                    @error('comment')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SUBMIT BUTTON -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl text-center shadow-xs transition duration-200 tracking-wide uppercase">
                        Kirim Ulasan & Testimoni
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- LOGIKA UTILITY JAVASCRIPT LOGIC RATING -->
    <script>
        let selectedRating = 0;
        const ratingLabels = {
            1: 'Sangat Mengecewakan 😠',
            2: 'Kurang Memuaskan 🙁',
            3: 'Biasa Saja / Cukup 😐',
            4: 'Bagus & Bermanfaat 🙂',
            5: 'Sangat Puas / Sempurna! 🤩'
        };

        const stars = document.querySelectorAll('.star-btn');
        const labelText = document.getElementById('rating-text');
        const textarea = document.getElementById('review_comment');
        const charCounter = document.getElementById('char-counter');

        function setRating(val) {
            selectedRating = val;
            document.getElementById(`rating-${val}`).checked = true;
            renderStars(val, 'text-amber-400');
            labelText.textContent = ratingLabels[val];
            labelText.className = "text-xs font-extrabold text-amber-500 ml-2 animate-pulse";
        }

        function hoverRating(val) {
            renderStars(val, 'text-amber-300');
            labelText.textContent = ratingLabels[val];
            labelText.className = "text-xs font-bold text-amber-400 ml-2";
        }

        function resetRating() {
            if (selectedRating > 0) {
                renderStars(selectedRating, 'text-amber-400');
                labelText.textContent = ratingLabels[selectedRating];
                labelText.className = "text-xs font-extrabold text-amber-500 ml-2";
            } else {
                renderStars(0, 'text-gray-200');
                labelText.textContent = 'Pilih Bintang';
                labelText.className = "text-xs font-bold text-gray-400 ml-2";
            }
        }

        function renderStars(count, colorClass) {
            stars.forEach(star => {
                const starVal = parseInt(star.getAttribute('data-star'));
                if (starVal <= count) {
                    star.className = `star-btn p-1 ${colorClass} hover:scale-110 transition duration-150 focus:outline-hidden`;
                } else {
                    star.className = 'star-btn p-1 text-gray-200 hover:scale-110 transition duration-150 focus:outline-hidden';
                }
            });
        }

        // Live Character Counter
        textarea.addEventListener('input', function() {
            const currentLen = this.value.length;
            charCounter.textContent = `${currentLen} / 1000 Karakter`;
            if (currentLen >= 1000) {
                charCounter.className = "text-[10px] text-rose-500 font-bold";
            } else {
                charCounter.className = "text-[10px] text-gray-400";
            }
        });
        
        // Trigger counter default on load
        if(textarea.value.length > 0) {
            charCounter.textContent = `${textarea.value.length} / 1000 Karakter`;
        }
    </script>
@endsection