@extends('layout.master-app')
@section('content')
    <div class="p-4 md:p-6 max-w-[1600px] mx-auto">

        <!-- Header Navigasi Atas -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-100 pb-5 mb-6">
            <div class="space-y-1">
                <a href="/my-courses"
                    class="inline-flex items-center gap-1 text-xs font-semibold text-gray-400 hover:text-indigo-600 transition mb-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Kelas Saya
                </a>
                <h1 class="text-xl font-black text-gray-900 leading-tight">{{ $course->title }}</h1>
            </div>

            <!-- Tombol Tulis Testimoni/Review -->
            <div>
                <a href="{{ url('/student/courses/' . $course->id . '/review') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl shadow-xs transition">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                        <path
                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    Beri Ulasan Kelas
                </a>
            </div>
        </div>

        <!-- Grid Layout Belajar Utama -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <!-- PANEL KIRI (2/3): Player Video atau Preview Dokumen -->
            <div class="lg:col-span-2 space-y-4">
                @if ($activeItem)
                    <!-- Frame Konten Utama -->
                    <div
                        class="bg-black rounded-2xl overflow-hidden shadow-sm border border-gray-100 aspect-video relative">
                        @if ($activeType === 'video')
                            {{-- Deteksi otomatis jika menggunakan Youtube embed --}}
                            @if (str_contains($activeItem->video_url, 'youtube.com') || str_contains($activeItem->video_url, 'youtu.be'))
                                @php
                                    // Ekstraksi ID video youtube sederhana
                                    preg_match(
                                        '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|[^/]+\?v=)|youtu\.be/)([^"&?/\s]{11})%i',
                                        $activeItem->video_url,
                                        $match,
                                    );
                                    $youtubeId = $match[1] ?? '';
                                @endphp
                                <iframe class="w-full h-full"
                                    src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0&autoplay=1"
                                    title="{{ $activeItem->title }}" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                            @else
                                {{-- Fallback untuk file video langsung (HTML5 Video Player) --}}
                                <video class="w-full h-full" controls autoplay controlsList="nodownload">
                                    <source src="{{ asset('storage/' . $activeItem->video_url) }}" type="video/mp4">
                                    Browser Anda tidak mendukung pemutar video HTML5.
                                </video>
                            @endif
                        @elseif($activeType === 'material')
                            {{-- Viewer PDF Menggunakan Embed Canvas Browser --}}
                            <iframe class="w-full h-full bg-white"
                                src="{{ asset('storage/' . $activeItem->file_path) }}#toolbar=0"
                                type="application/pdf"></iframe>
                        @endif
                    </div>

                    <!-- Detail Deskripsi Item yang Sedang Aktif -->
                    <div class="bg-white border border-gray-100 rounded-2xl p-5 space-y-2">
                        <div class="flex items-center gap-2">
                            <span
                                class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $activeType === 'video' ? 'bg-indigo-50 text-indigo-600' : 'bg-emerald-50 text-emerald-600' }}">
                                {{ $activeType }}
                            </span>
                            <h2 class="text-base font-bold text-gray-900">{{ $activeItem->title }}</h2>
                        </div>
                        <p class="text-xs text-gray-400 leading-relaxed">
                            Anda sedang memuat modul pengajaran resmi dari kelas <span
                                class="font-medium text-gray-600">{{ $course->title }}</span>. Jika konten bermasalah,
                            silakan hubungi tim administrasi Guru Hub.
                        </p>
                    </div>
                @else
                    <!-- State Jika Kelas Belum Memiliki Konten Apapun -->
                    <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center space-y-3">
                        <div
                            class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.225 2.225 0 01-2.247-2.118L3.75 7.5m16.5 0a1.5 1.5 0 00-1.5-1.5H16.25M20.25 7.5L18.75 3.375c-.062-.172-.175-.333-.3-.46a1.5 1.5 0 00-1.012-.414H6.562c-.369 0-.714.133-.976.38a1.5 1.5 0 00-.337.545L3.75 7.5M15 7.5a3 3 0 11-6 0M3.75 7.5h16.5" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-sm font-bold text-gray-800">Kurikulum Belum Tersedia</h3>
                            <p class="text-xs text-gray-400 max-w-xs mx-auto">Instruktur pengajar belum mengunggah materi
                                maupun video streaming ke dalam sistem kelas ini.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- PANEL KANAN (1/3): Daftar Silabus Materi & Video -->
            <div class="bg-white border border-gray-100 rounded-2xl p-5 space-y-5 shadow-2xs">
                <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest border-b border-gray-50 pb-3">Silabus
                    & Modul Belajar</h3>

                <div class="space-y-4 max-h-[600px] overflow-y-auto pr-1">

                    <!-- Kategori Kelompok: Sesi Video Streaming -->
                    <div class="space-y-2">
                        <span class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-wider block">Video Kuliah
                            ({{ $course->videos->count() }})</span>
                        <div class="space-y-1.5">
                            @forelse($course->videos as $index => $video)
                                @php $isCurrentVideo = ($activeType === 'video' && $activeItem && $activeItem->id === $video->id); @endphp
                                <a href="?type=video&id={{ $video->id }}"
                                    class="flex items-start gap-3 p-3 rounded-xl text-xs transition border {{ $isCurrentVideo ? 'bg-indigo-50/70 border-indigo-200 text-indigo-700 font-bold' : 'bg-gray-50/50 border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <span
                                        class="w-5 h-5 rounded-md flex items-center justify-center font-bold text-[10px] shrink-0 {{ $isCurrentVideo ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="space-y-0.5 min-w-0">
                                        <p class="truncate leading-tight">{{ $video->title }}</p>
                                        {{-- Sebelum: {{ uppercase($video->video_type ?? 'Streaming') }} --}}
                                        {{-- Perbaikan Menggunakan Helper Laravel Str: --}}
                                        <span
                                            class="text-[10px] text-gray-400 block font-normal">{{ \Illuminate\Support\Str::upper($video->video_type ?? 'Streaming') }}</span>
                                    </div>
                                </a>
                            @empty
                                <p class="text-[11px] text-gray-400 italic pl-1">Belum ada rekaman video.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Kategori Kelompok: File Ringkasan Materi -->
                    <div class="space-y-2 pt-2 border-t border-gray-50">
                        <span class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider block">Dokumen &
                            Handout ({{ $course->materials->count() }})</span>
                        <div class="space-y-1.5">
                            @forelse($course->materials as $index => $material)
                                @php $isCurrentMaterial = ($activeType === 'material' && $activeItem && $activeItem->id === $material->id); @endphp
                                <a href="?type=material&id={{ $material->id }}"
                                    class="flex items-start gap-3 p-3 rounded-xl text-xs transition border {{ $isCurrentMaterial ? 'bg-emerald-50/70 border-emerald-200 text-emerald-700 font-bold' : 'bg-gray-50/50 border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <!-- Icon Dokumen PDF -->
                                    <svg class="w-4 h-4 shrink-0 mt-0.5 {{ $isCurrentMaterial ? 'text-emerald-600' : 'text-gray-400' }}"
                                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    <div class="space-y-0.5 min-w-0 flex-1">
                                        <p class="truncate leading-tight">{{ $material->title }}</p>
                                        <div class="flex items-center gap-2 text-[10px] text-gray-400 font-normal">
                                            <span>Baca Online</span>
                                            <span>•</span>
                                            <a href="{{ asset('storage/' . $material->file_path) }}" download
                                                class="text-indigo-600 hover:underline font-medium">Download</a>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <p class="text-[11px] text-gray-400 italic pl-1">Belum ada modul PDF.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="space-y-2 pt-2 border-t border-gray-50">
                        <span class="text-[10px] font-extrabold text-amber-600 uppercase tracking-wider block">Jadwal Live
                            Class ({{ $course->schedules->count() }})</span>
                        <div class="space-y-1.5">
                            @forelse($course->schedules as $schedule)
                                @php
                                    $isPassed = $schedule->start_time->isPast();
                                    $isToday = $schedule->start_time->isToday();
                                @endphp
                                <div
                                    class="p-3 rounded-xl border bg-gray-50/50 {{ $isToday ? 'border-amber-200 bg-amber-50/30' : 'border-transparent' }} space-y-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="space-y-0.5">
                                            <p class="text-xs font-bold text-gray-800 leading-tight">{{ $schedule->name }}
                                            </p>
                                            <div class="flex items-center gap-2 text-[10px] text-gray-400">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span>{{ $schedule->start_time->translatedFormat('d M Y, H:i') }} WIB</span>
                                            </div>
                                        </div>
                                        @if ($isToday && !$isPassed)
                                            <span class="flex h-2 w-2">
                                                <span
                                                    class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-rose-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                                            </span>
                                        @endif
                                    </div>

                                    @if ($schedule->meeting_link)
                                        @if ($isPassed)
                                            <button disabled
                                                class="w-full py-1.5 bg-gray-100 text-gray-400 text-[10px] font-bold rounded-lg cursor-not-allowed">
                                                Sesi Berakhir
                                            </button>
                                        @else
                                            <a href="{{ $schedule->meeting_link }}" target="_blank"
                                                class="w-full py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-[10px] font-bold rounded-lg text-center transition block shadow-sm">
                                                Gabung Zoom / Meet
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-[9px] text-gray-400 italic block">Link belum tersedia</span>
                                    @endif
                                </div>
                            @empty
                                <p class="text-[11px] text-gray-400 italic pl-1">Belum ada jadwal live.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
