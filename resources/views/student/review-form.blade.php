@extends('layout.master-app')
@section('content')
    <div class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner">
            <x-app.page-header title="Berikan Ulasan" subtitle="Bagikan pengalaman belajar Anda untuk meningkatkan kualitas pengajaran." back="/my-courses" />

            <div class="gh-app-card space-y-5">
                <div class="gh-app-card" style="background: linear-gradient(135deg, #ecfeff, #f0f9ff); border-color: rgb(34 211 238 / 0.2);">
                    <x-app.badge variant="info">{{ $course->category->name ?? 'Premium Course' }}</x-app.badge>
                    <h3 class="gh-app-subheading mt-2">{{ $course->title }}</h3>
                    <p class="gh-app-caption">Mengajar oleh: {{ $course->teacher->name ?? 'Instruktur' }}</p>
                </div>

                <form action="{{ url('/student/reviews') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <input type="hidden" name="teacher_id" value="{{ $course->teacher_id }}">

                    <div class="gh-app-form-group">
                        <label class="gh-app-label">Beri Skor Rating <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-1">
                            <input type="radio" name="rating" id="rating-1" value="1" class="hidden" required>
                            <input type="radio" name="rating" id="rating-2" value="2" class="hidden">
                            <input type="radio" name="rating" id="rating-3" value="3" class="hidden">
                            <input type="radio" name="rating" id="rating-4" value="4" class="hidden">
                            <input type="radio" name="rating" id="rating-5" value="5" class="hidden">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" onclick="setRating({{ $i }})" onmouseover="hoverRating({{ $i }})" onmouseleave="resetRating()" class="star-btn p-1 text-gray-200 hover:scale-110 transition duration-150 focus:outline-hidden" data-star="{{ $i }}">
                                    <svg class="w-7 h-7 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            @endfor
                            <span id="rating-text" class="gh-app-caption ml-2 font-bold">Pilih Bintang</span>
                        </div>
                        @error('rating')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="gh-app-form-group">
                        <label for="review_comment" class="gh-app-label">Pesan Testimoni <span class="text-red-500">*</span></label>
                        <textarea name="comment" id="review_comment" rows="5" required minlength="10" maxlength="1000"
                            placeholder="Tuliskan ulasan Anda mengenai metode penyampaian materi..."
                            class="gh-app-textarea">{{ old('comment') }}</textarea>
                        <div class="flex justify-between gh-app-caption">
                            <span>Minimal 10 karakter.</span>
                            <span id="char-counter">0 / 1000 Karakter</span>
                        </div>
                        @error('comment')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="gh-app-btn gh-app-btn-primary gh-app-btn-block">Kirim Ulasan & Testimoni</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let selectedRating = 0;
        const ratingLabels = { 1: 'Sangat Mengecewakan 😠', 2: 'Kurang Memuaskan 🙁', 3: 'Biasa Saja / Cukup 😐', 4: 'Bagus & Bermanfaat 🙂', 5: 'Sangat Puas / Sempurna! 🤩' };
        const stars = document.querySelectorAll('.star-btn');
        const labelText = document.getElementById('rating-text');
        const textarea = document.getElementById('review_comment');
        const charCounter = document.getElementById('char-counter');

        function setRating(val) { selectedRating = val; document.getElementById(`rating-${val}`).checked = true; renderStars(val, 'text-amber-400'); labelText.textContent = ratingLabels[val]; labelText.className = "gh-app-caption font-extrabold text-amber-500 ml-2"; }
        function hoverRating(val) { renderStars(val, 'text-amber-300'); labelText.textContent = ratingLabels[val]; labelText.className = "gh-app-caption font-bold text-amber-400 ml-2"; }
        function resetRating() { if (selectedRating > 0) { renderStars(selectedRating, 'text-amber-400'); labelText.textContent = ratingLabels[selectedRating]; labelText.className = "gh-app-caption font-extrabold text-amber-500 ml-2"; } else { renderStars(0, 'text-gray-200'); labelText.textContent = 'Pilih Bintang'; labelText.className = "gh-app-caption font-bold text-gray-400 ml-2"; } }
        function renderStars(count, colorClass) { stars.forEach(star => { const starVal = parseInt(star.getAttribute('data-star')); star.className = starVal <= count ? `star-btn p-1 ${colorClass} hover:scale-110 transition duration-150 focus:outline-hidden` : 'star-btn p-1 text-gray-200 hover:scale-110 transition duration-150 focus:outline-hidden'; }); }
        textarea.addEventListener('input', function() { charCounter.textContent = `${this.value.length} / 1000 Karakter`; charCounter.className = this.value.length >= 1000 ? "text-[10px] text-red-500 font-bold" : "gh-app-caption"; });
        if(textarea.value.length > 0) charCounter.textContent = `${textarea.value.length} / 1000 Karakter`;
    </script>
@endsection
