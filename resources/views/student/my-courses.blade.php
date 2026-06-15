@extends('layout.master-app')
@section('content')
    <div class="p-6 max-w-5xl mx-auto space-y-6">

        <!-- Header Halaman -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-100 pb-5">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Ruang Belajar Saya</h1>
                <p class="text-xs text-gray-500">Akses materi pembelajaran dan pantau perkembangan studi Anda di sini.</p>
            </div>

            <!-- Tab Switcher Navigasi -->
            <div class="flex bg-gray-100 p-1 rounded-xl shrink-0 self-start md:self-center">
                <button onclick="switchTab('tab-active', this)"
                    class="tab-btn px-4 py-1.5 bg-white text-indigo-600 shadow-2xs text-xs font-bold rounded-lg transition-all duration-200">
                    Kelas Aktif ({{ $activeCourses->count() }})
                </button>
                <button onclick="switchTab('tab-completed', this)"
                    class="tab-btn px-4 py-1.5 text-gray-500 hover:text-gray-900 text-xs font-semibold rounded-lg transition-all duration-200">
                    Sudah Selesai ({{ $completedCourses->count() }})
                </button>
            </div>
        </div>

        <!-- ================= PANEL 1: KELAS AKTIF ================= -->
        <div id="tab-active" class="tab-content space-y-4">
            @if ($activeCourses->isEmpty())
                <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center space-y-3">
                    <div
                        class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center mx-auto text-indigo-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-sm font-bold text-gray-900">Tidak Ada Kelas Aktif</h3>
                        <p class="text-xs text-gray-400 max-w-xs mx-auto">Anda tidak memiliki kelas yang sedang berjalan
                            saat ini. Silakan selesaikan pembayaran atau daftar kelas baru.</p>
                    </div>
                    <div class="pt-2">
                        <a href="/history-bookings"
                            class="inline-flex text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-xl shadow-2xs transition">
                            Cek Tagihan Pembayaran
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach ($activeCourses as $booking)
                        <div
                            class="bg-white border border-gray-100 rounded-2xl p-5 shadow-2xs flex flex-col justify-between gap-4 transition hover:border-gray-200 hover:shadow-xs">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider bg-indigo-50 px-2.5 py-1 rounded-md">
                                        {{ $booking->course->category->name ?? 'Umum' }}
                                    </span>
                                    <span class="text-[11px] text-gray-400 font-mono">
                                        Inv: {{ $booking->transaction_code }}
                                    </span>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900 leading-snug">{{ $booking->course->title }}</h3>
                                <p class="text-xs text-gray-400">Mentor: <span
                                        class="text-gray-600 font-semibold">{{ $booking->course->teacher->name ?? 'Instruktur' }}</span>
                                </p>
                            </div>

                            <div class="space-y-1.5 pt-2 border-t border-gray-50">
                                <div class="flex justify-between text-[11px]">
                                    <span class="text-gray-400">Progress Belajar</span>
                                    <span class="font-bold text-gray-700">{{ $booking->progress_percentage ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                                    <div class="bg-indigo-600 h-full rounded-full transition-all duration-300"
                                        style="width: {{ $booking->progress_percentage ?? 0 }}%">
                                    </div>
                                </div>
                            </div>

                            <!-- Tombol Menuju Kelas -->
                            <a href="/student/courses/{{ $booking->course_id }}/learn"
                                class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl text-center shadow-2xs transition block">
                                Mulai Belajar Sekarang
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- ================= PANEL 2: KELAS SELESAI ================= -->
        <div id="tab-completed" class="tab-content space-y-4 hidden">
            @if ($completedCourses->isEmpty())
                <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center space-y-3">
                    <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-sm font-bold text-gray-900">Belum Ada Kelas Selesai</h3>
                        <p class="text-xs text-gray-400 max-w-xs mx-auto">Selesaikan seluruh modul materi pada kelas aktif
                            Anda untuk mendapatkan sertifikat kelulusan resmi.</p>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach ($completedCourses as $booking)
                        <div
                            class="bg-white border border-gray-100 rounded-2xl p-5 shadow-2xs flex flex-col justify-between gap-4 opacity-85">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100">
                                        Lulus / Selesai
                                    </span>
                                    <span class="text-[11px] text-gray-400 font-mono">
                                        {{ $booking->updated_at->format('d M Y') }}
                                    </span>
                                </div>
                                <h3 class="text-sm font-bold text-gray-400 line-through leading-snug">
                                    {{ $booking->course->title }}</h3>
                                <p class="text-xs text-gray-400">Mentor: <span
                                        class="text-gray-500 font-medium">{{ $booking->course->teacher->name ?? 'Instruktur' }}</span>
                                </p>
                            </div>

                            <!-- Tombol Aksi Riwayat Kelas (Contoh: Unduh Sertifikat) -->
                            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-50">
                                <a href="/student/courses/{{ $booking->course_id }}/certificate"
                                    class="py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-xl text-center transition block">
                                    Unduh Sertifikat
                                </a>
                                <a href="/student/courses/{{ $booking->course_id }}/review"
                                    class="py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 text-xs font-semibold rounded-xl text-center transition block">
                                    Lihat Ulasan
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    <!-- Script Logika Tab Switcher -->
    <script>
        function switchTab(tabId, buttonElement) {
            // Hapus status aktif dari semua kontent panel tab
            document.querySelectorAll('.tab-content').forEach(panel => {
                panel.classList.add('hidden');
            });

            // Tampilkan panel tab yang dipilih
            document.getElementById(tabId).classList.remove('hidden');

            // Reset style semua tombol tab switcher
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'text-indigo-600', 'shadow-2xs', 'font-bold');
                btn.classList.add('text-gray-500', 'font-semibold');
            });

            // Tambahkan style aktif pada tombol yang sedang diklik
            buttonElement.classList.add('bg-white', 'text-indigo-600', 'shadow-2xs', 'font-bold');
            buttonElement.classList.remove('text-gray-500', 'font-semibold');
        }
    </script>
@endsection
