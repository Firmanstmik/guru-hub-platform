@extends('layout.master-app')
@section('content')
    <div class="p-6 max-w-5xl mx-auto space-y-6">

        <!-- Wizard Progress Indicator Stepper -->
        <div class="max-w-3xl mx-auto pb-4">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-0 right-0 top-1/3 -translate-y-1/2 h-0.5 bg-gray-200 z-0"></div>
                <div id="wizard-progress-bar"
                    class="absolute left-0 top-1/3 -translate-y-1/2 h-0.5 bg-indigo-600 transition-all duration-300 z-0"
                    style="width: 0%;"></div>

                <div class="flex flex-col items-center relative z-10">
                    <div id="step-circle-1"
                        class="w-9 h-9 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-sm shadow-xs transition duration-300">
                        1
                    </div>
                    <span id="step-text-1" class="text-xs font-bold text-indigo-600 mt-2 bg-gray-50 px-2">Data
                        Booking</span>
                </div>

                <div class="flex flex-col items-center relative z-10">
                    <div id="step-circle-2"
                        class="w-9 h-9 rounded-full bg-white text-gray-400 border-2 border-gray-200 font-bold flex items-center justify-center text-sm transition duration-300">
                        2
                    </div>
                    <span id="step-text-2" class="text-xs font-semibold text-gray-400 mt-2 bg-gray-50 px-2">Metode
                        Pembayaran</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <div class="lg:col-span-2">
                <div
                    class="bg-white p-6 rounded-2xl shadow-2xs border border-gray-100 min-h-[380px] flex flex-col justify-between">

                    <form action="/bookings" method="POST" id="wizard-form" class="space-y-5 my-auto">
                        @csrf

                        <!-- STEP 1: PILIH KELAS -->
                        <div id="wizard-step-1" class="space-y-5 transition-opacity duration-200">
                            <div>
                                <h2 class="text-base font-bold text-gray-900 mb-1">Pilih Program Pembelajaran</h2>
                                <p class="text-xs text-gray-400 mb-4">Tentukan kelas yang ingin Anda ikuti untuk mengamankan
                                    slot belajar.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Nama
                                        Lengkap</label>
                                    <input type="text" value="{{ $student->name }}" disabled
                                        class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Alamat
                                        Email</label>
                                    <input type="email" value="{{ $student->email }}" disabled
                                        class="w-full px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
                                </div>
                            </div>

                            <div>
                                <label for="course_select"
                                    class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Kelas
                                    yang Dipilih <span class="text-rose-500">*</span></label>
                                <select name="course_id" id="course_select" required
                                    class="w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-gray-700">
                                    <option value="" data-price="0" data-teacher="-" data-category="-">-- Pilih
                                        Program Kelas --</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}" data-price="{{ $course->price }}"
                                            data-teacher="{{ $course->teacher->name ?? 'Instruktur' }}"
                                            data-category="{{ $course->category->name ?? 'Umum' }}">
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="student_note"
                                    class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Catatan
                                    Tambahan untuk Pengajar (Opsional)</label>
                                <textarea name="note" id="student_note" rows="3"
                                    placeholder="Contoh: Saya ingin belajar dari dasar karena belum familiar dengan framework ini..."
                                    class="w-full px-4 py-2.5 text-sm bg-white border border-gray-200 rounded-xl focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-gray-700 placeholder:text-gray-400"></textarea>
                            </div>
                        </div>

                        <!-- STEP 2: RINCIAN TRANSFER BANK -->
                        <div id="wizard-step-2" class="space-y-3 hidden transition-opacity duration-200">
                            <div>
                                <h2 class="text-base font-bold text-gray-900 mb-1">Metode Transfer Bank Manual</h2>
                                <p class="text-xs text-gray-400 mb-4">Silakan transfer nominal tagihan Anda ke salah satu
                                    rekening resmi pengelola di bawah ini:</p>
                            </div>
                            @foreach ($banks as $bank)
                                <div
                                    class="px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between gap-4">
                                    <div class="space-y-1">
                                        <span
                                            class="text-xs font-bold text-blue-800 tracking-wider">{{ $bank->bank_name }}</span>
                                        <p class="text-sm font-mono font-bold text-gray-800 tracking-wide">
                                            {{ $bank->account_number }}
                                        </p>
                                        <p class="text-[11px] text-gray-600">Atas Nama:
                                            <span class="font-semibold text-gray-600">{{ $bank->account_name }}</span>
                                        </p>
                                    </div>
                                    <button type="button"
                                        onclick="copyToClipboardValue('{{ $bank->account_number }}', this)"
                                        class="px-3 py-1.5 bg-white border border-gray-200 hover:bg-gray-100 rounded-lg text-xs font-semibold text-gray-600 shadow-2xs transition flex items-center gap-1">
                                        Salin
                                    </button>
                                </div>
                            @endforeach

                            <div
                                class="bg-amber-50/70 border border-amber-100 p-3 rounded-xl text-[11px] text-amber-800 leading-normal">
                                <strong>Penting:</strong> Simpan struk/resi transfer m-banking Anda untuk divalidasi oleh
                                tim admin setelah menekan tombol submit akhir.
                            </div>
                        </div>

                        <!-- TOMBOL NAVIGASI -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                            <button type="button" id="btn-back"
                                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold rounded-xl transition opacity-0 cursor-default">
                                Kembali
                            </button>

                            <button type="button" id="btn-next"
                                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center gap-2">
                                <span>Lanjutkan Ke Pembayaran</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <!-- RINGKASAN KANAN -->
            <div class="lg:col-span-1">
                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-gray-100 space-y-4 sticky top-6">
                    <h2 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-3">Ringkasan Pembayaran</h2>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Kategori</span>
                            <span id="summary-category" class="font-semibold text-gray-700">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Guru Pengajar</span>
                            <span id="summary-teacher" class="font-semibold text-gray-700">-</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Biaya Pendaftaran</span>
                            <span class="font-semibold text-gray-700">Sistem Satu Kali Bayar</span>
                        </div>
                    </div>

                    <div class="bg-indigo-600 text-white p-4 rounded-xl space-y-1 shadow-sm mt-2">
                        <span class="text-[10px] text-indigo-200 font-semibold uppercase tracking-wider">Total yang Harus
                            Dibayar</span>
                        <div class="flex items-center justify-between">
                            <span id="summary-price" class="text-base font-extrabold font-mono">Rp 0</span>
                            <button type="button" id="btn-copy-price" disabled
                                class="px-2 py-1 bg-indigo-500/50 hover:bg-indigo-500 border border-indigo-400/30 text-[10px] font-semibold rounded-md transition opacity-0 cursor-default">
                                Salin
                            </button>
                        </div>
                    </div>

                    <div class="bg-indigo-50/50 p-3 rounded-xl text-[11px] text-indigo-700 leading-normal flex gap-2">
                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span id="summary-info-text">Pilih program kelas terlebih dahulu untuk melihat instruksi lengkap
                            detail transaksi pendaftaran.</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Element Selector
            const form = document.getElementById('wizard-form');
            const courseSelect = document.getElementById('course_select'); // SINKRONISASI ID SELEKTOR
            const step1 = document.getElementById('wizard-step-1');
            const step2 = document.getElementById('wizard-step-2');

            const btnBack = document.getElementById('btn-back');
            const btnNext = document.getElementById('btn-next');
            const btnCopyPrice = document.getElementById('btn-copy-price');

            const progressBar = document.getElementById('wizard-progress-bar');
            const circle2 = document.getElementById('step-circle-2');
            const text2 = document.getElementById('step-text-2');

            const summaryCategory = document.getElementById('summary-category');
            const summaryTeacher = document.getElementById('summary-teacher');
            const summaryPrice = document.getElementById('summary-price');
            const summaryInfoText = document.getElementById('summary-info-text');

            let currentStep = 1;
            let coursePrice = 0;

            function updateSummary() {
                const selectedOption = courseSelect.options[courseSelect.selectedIndex];

                if (selectedOption && selectedOption.value !== "") {
                    coursePrice = parseInt(selectedOption.getAttribute('data-price')) || 0;
                    summaryCategory.textContent = selectedOption.getAttribute('data-category');
                    summaryTeacher.textContent = selectedOption.getAttribute('data-teacher');
                    summaryPrice.textContent = 'Rp ' + coursePrice.toLocaleString('id-ID');

                    btnCopyPrice.classList.remove('opacity-0', 'cursor-default');
                    btnCopyPrice.removeAttribute('disabled');
                } else {
                    summaryCategory.textContent = '-';
                    summaryTeacher.textContent = '-';
                    summaryPrice.textContent = 'Rp 0';
                    btnCopyPrice.classList.add('opacity-0', 'cursor-default');
                    btnCopyPrice.setAttribute('disabled', 'true');
                }
            }

            courseSelect.addEventListener('change', updateSummary);

            const urlParams = new URLSearchParams(window.location.search);
            const courseIdFromUrl = urlParams.get('course_id');
            if (courseIdFromUrl) {
                courseSelect.value = courseIdFromUrl;
                updateSummary();
            }

            // LOGIKA NAVIGASI WIZARD
            btnNext.addEventListener('click', function() {
                if (currentStep === 1) {
                    if (!courseSelect.value) {
                        courseSelect.reportValidity();
                        return;
                    }

                    currentStep = 2;

                    step1.classList.add('hidden');
                    step2.classList.remove('hidden');

                    progressBar.style.width = '100%';
                    circle2.className =
                        "w-9 h-9 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-sm shadow-xs transition duration-300";
                    text2.className = "text-xs font-bold text-indigo-600 mt-2 bg-gray-50 px-2";

                    btnBack.classList.remove('opacity-0', 'cursor-default');
                    btnNext.innerHTML =
                        `<span>Selesaikan & Kirim Booking</span> <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
                    summaryInfoText.textContent =
                        "Silakan lakukan transfer sesuai nomor rekening tertera sebelum mengirim pendaftaran agar verifikasi berjalan cepat.";
                } else if (currentStep === 2) {
                    form.submit();
                }
            });

            btnBack.addEventListener('click', function() {
                if (currentStep === 2) {
                    currentStep = 1;

                    step2.classList.add('hidden');
                    step1.classList.remove('hidden');

                    progressBar.style.width = '0%';
                    circle2.className =
                        "w-9 h-9 rounded-full bg-white text-gray-400 border-2 border-gray-200 font-bold flex items-center justify-center text-sm transition duration-300";
                    text2.className = "text-xs font-semibold text-gray-400 mt-2 bg-gray-50 px-2";

                    btnBack.classList.add('opacity-0', 'cursor-default');
                    btnNext.innerHTML =
                        `<span>Lanjutkan Ke Pembayaran</span> <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>`;
                    summaryInfoText.textContent =
                        "Pilih program kelas terlebih dahulu untuk melihat instruksi lengkap detail transaksi pendaftaran.";
                }
            });

            btnCopyPrice.addEventListener('click', function() {
                if (coursePrice > 0) {
                    navigator.clipboard.writeText(coursePrice).then(() => {
                        const oldText = btnCopyPrice.innerText;
                        btnCopyPrice.innerText = 'Tersalin!';
                        setTimeout(() => btnCopyPrice.innerText = oldText, 2000);
                    });
                }
            });
        });

        function copyToClipboardValue(rawValue, buttonElement) {
            // Menyalin string langsung tanpa perlu mencari ID elemen di HTML
            navigator.clipboard.writeText(rawValue).then(() => {
                const originalText = buttonElement.innerText;

                // Berikan feedback visual sukses (warna hijau)
                buttonElement.innerText = 'Tersalin!';
                buttonElement.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
                buttonElement.classList.remove('bg-white', 'text-gray-600');

                // Kembalikan ke tombol semula setelah 2 detik
                setTimeout(() => {
                    buttonElement.innerText = originalText;
                    buttonElement.classList.remove('bg-emerald-50', 'text-emerald-700',
                        'border-emerald-200');
                    buttonElement.classList.add('bg-white', 'text-gray-600');
                }, 2000);
            }).catch(err => {
                console.error('Gagal menyalin teks: ', err);
            });
        }
    </script>
@endsection
