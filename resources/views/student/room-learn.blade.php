@extends('layout.master-app')
@section('content')
    <div class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner">
            <x-app.page-header :title="$course->title" back="/my-courses">
                <x-slot:action>
                    <a href="{{ url('/student/courses/' . $course->id . '/review') }}" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm">⭐ Beri Ulasan</a>
                </x-slot:action>
            </x-app.page-header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-start">

            <!-- PANEL KIRI (2/3): Player Video atau Preview Dokumen -->
            <div class="lg:col-span-2 space-y-4">
                @if ($activeItem)
                    <div
                        class="bg-black rounded-2xl overflow-hidden shadow-sm border border-gray-100 aspect-video relative">
                        @if ($activeType === 'video')
                            @php
                                $url = $activeItem->video_url;
                                $isYoutube = str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be');

                                // Deteksi ekstensi video murni untuk file lokal storage atau direct URL
                                $isDirectVideo = false;
                                foreach (['.mp4', '.webm', '.mov', '.avi'] as $ext) {
                                    if (str_contains(strtolower($url), $ext)) {
                                        $isDirectVideo = true;
                                        break;
                                    }
                                }

                                // Tentukan path asset: jika berupa URL penuh (http) pakai langsung, jika path lokal arahkan ke storage
                                $videoSource =
                                    str_starts_with($url, 'http://') || str_starts_with($url, 'https://')
                                        ? $url
                                        : asset('storage/' . $url);
                            @endphp

                            {{-- 1. Kondisi Pemutar YouTube Embed --}}
                            @if ($isYoutube)
                                @php
                                    $youtubeId = '';
                                    if (str_contains($url, 'youtu.be/')) {
                                        $parts = explode('youtu.be/', $url);
                                        $youtubeId = explode('?', explode('#', $parts[1])[0])[0];
                                    } elseif (str_contains($url, 'v=')) {
                                        $parts = explode('v=', $url);
                                        $youtubeId = explode('&', $parts[1])[0];
                                    } elseif (str_contains($url, 'embed/')) {
                                        $parts = explode('embed/', $url);
                                        $youtubeId = explode('?', explode('#', $parts[1])[0])[0];
                                    }
                                @endphp
                                <iframe class="w-full h-full"
                                    src="https://www.youtube.com/embed/{{ $youtubeId }}?rel=0&autoplay=1"
                                    title="{{ $activeItem->title }}" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>

                                {{-- 2. Kondisi Pemutar Video Fisik (HTML5 Player) Baik dari Storage Lokal maupun CDN Eksternal --}}
                            @elseif ($isDirectVideo)
                                <video class="w-full h-full object-contain focus:outline-none" controls autoplay
                                    controlsList="nodownload">
                                    <source src="{{ $videoSource }}" type="video/mp4">
                                    <source src="{{ $videoSource }}" type="video/webm">
                                    Browser Anda tidak mendukung pemutar video HTML5.
                                </video>

                                {{-- 3. Kondisi Fallback: Tautan Iframe Alternatif (Misal: Google Drive Embed atau Link Streaming Lain) --}}
                            @else
                                <iframe class="w-full h-full" src="{{ $url }}" frameborder="0" allow="autoplay"
                                    allowfullscreen></iframe>
                            @endif
                        @elseif($activeType === 'material')
                            {{-- Viewer PDF Menggunakan Embed Canvas Browser --}}
                            <iframe class="w-full h-full bg-white"
                                src="{{ asset('storage/' . $activeItem->file_path) }}#toolbar=0"
                                type="application/pdf"></iframe>
                        @endif
                    </div>

                    <div class="gh-app-card space-y-2">
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
            <div class="space-y-5 lg:col-span-1">

                @if ($isCompleted && $cert)
                    <div
                        class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl p-5 text-white shadow-xs border border-emerald-500/20 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-white/10 rounded-xl shrink-0 text-emerald-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                            </div>
                            <div class="space-y-0.5">
                                <h3 class="text-sm font-black tracking-wide">Selamat, Anda Lulus!</h3>
                                <p class="text-xs text-emerald-100/90 leading-tight">Anda telah menyelesaikan seluruh
                                    rangkaian program kelas pelatihan premium ini.</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $cert->file_path) }}" target="_blank"
                            class="w-full py-2.5 bg-white hover:bg-emerald-50 text-emerald-800 text-xs font-black rounded-xl text-center transition block shadow-md">
                            🎓 Unduh Sertifikat Resmi
                        </a>
                    </div>
                @endif

                <div class="gh-app-card space-y-5">
                    <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest border-b border-gray-50 pb-3">
                        Silabus & Modul Belajar</h3>

                    <div class="space-y-4 max-h-[600px] overflow-y-auto pr-1">

                        {{-- KELOMPOK VIDEO KULIAH --}}
                        <div class="space-y-2">
                            <span class="text-[10px] font-extrabold text-indigo-600 uppercase tracking-wider block">Video
                                Kuliah ({{ $course->videos->count() }})</span>
                            <div class="space-y-1.5">
                                @forelse($course->videos as $index => $video)
                                    @php
                                        $isCurrentVideo =
                                            $activeType === 'video' && $activeItem && $activeItem->id === $video->id;
                                        // Pastikan relasi / kondisi check data completion terdefinisi dari controller (contoh: $video->is_completed)
                                        $videoChecked = isset($video->is_completed) && $video->is_completed;
                                    @endphp
                                    <div
                                        class="flex items-center gap-2.5 p-3 rounded-xl border transition {{ $isCurrentVideo ? 'bg-indigo-50/70 border-indigo-200' : 'bg-gray-50/50 border-transparent hover:bg-gray-50' }}">

                                        {{-- Checkbox Kustom Video --}}
                                        <div class="flex items-center shrink-0">
                                            <input type="checkbox" data-id="{{ $video->id }}" data-type="video"
                                                class="progress-checkbox w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500/30 transition cursor-pointer"
                                                {{ $videoChecked ? 'checked' : '' }}>
                                        </div>

                                        <a href="?type=video&id={{ $video->id }}"
                                            class="flex items-start gap-3 text-xs min-w-0 flex-1 {{ $isCurrentVideo ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-gray-900' }}">
                                            <span
                                                class="w-5 h-5 rounded-md flex items-center justify-center font-bold text-[10px] shrink-0 {{ $isCurrentVideo ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                                                {{ $index + 1 }}
                                            </span>
                                            <div class="space-y-0.5 min-w-0">
                                                <p class="truncate leading-tight">{{ $video->title }}</p>
                                                <span
                                                    class="text-[10px] text-gray-400 block font-normal">{{ \Illuminate\Support\Str::upper($video->video_type ?? 'Streaming') }}</span>
                                            </div>
                                        </a>
                                    </div>
                                @empty
                                    <p class="text-[11px] text-gray-400 italic pl-1">Belum ada rekaman video.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- KELOMPOK DOKUMEN & HANDOUT --}}
                        <div class="space-y-2 pt-2 border-t border-gray-50">
                            <span class="text-[10px] font-extrabold text-emerald-600 uppercase tracking-wider block">Dokumen
                                & Handout ({{ $course->materials->count() }})</span>
                            <div class="space-y-1.5">
                                @forelse($course->materials as $index => $material)
                                    @php
                                        $isCurrentMaterial =
                                            $activeType === 'material' &&
                                            $activeItem &&
                                            $activeItem->id === $material->id;
                                        $materialChecked = isset($material->is_completed) && $material->is_completed;
                                    @endphp
                                    <div
                                        class="flex items-center gap-2.5 p-3 rounded-xl border transition {{ $isCurrentMaterial ? 'bg-emerald-50/70 border-emerald-200' : 'bg-gray-50/50 border-transparent hover:bg-gray-50' }}">

                                        {{-- Checkbox Kustom Dokumen --}}
                                        <div class="flex items-center shrink-0">
                                            <input type="checkbox" data-id="{{ $material->id }}" data-type="material"
                                                class="progress-checkbox w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500/30 transition cursor-pointer"
                                                {{ $materialChecked ? 'checked' : '' }}>
                                        </div>

                                        <div
                                            class="flex items-start gap-3 text-xs min-w-0 flex-1 {{ $isCurrentMaterial ? 'text-emerald-700 font-bold' : 'text-gray-600' }}">
                                            <svg class="w-4 h-4 shrink-0 mt-0.5 {{ $isCurrentMaterial ? 'text-emerald-600' : 'text-gray-400' }}"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125 504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>
                                            <div class="space-y-0.5 min-w-0 flex-1">
                                                <a href="?type=material&id={{ $material->id }}"
                                                    class="truncate leading-tight block hover:underline {{ $isCurrentMaterial ? 'text-emerald-900' : 'hover:text-gray-900' }}">{{ $material->title }}</a>
                                                <div class="flex items-center gap-2 text-[10px] text-gray-400 font-normal">
                                                    <a href="?type=material&id={{ $material->id }}"
                                                        class="hover:text-indigo-600">Baca Online</a>
                                                    <span>•</span>
                                                    <a href="{{ asset('storage/' . $material->file_path) }}" download
                                                        class="text-indigo-600 hover:underline font-medium">Download</a>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- tombol quiz --}}
                                        <a href="{{ url('/materials/'.$material->id.'/quiz') }}"
                                            class="btn bg-indigo-600 text-white font-bold">
                                            Kerjakan Kuis
                                        </a>
                                    </div>
                                @empty
                                    <p class="text-[11px] text-gray-400 italic pl-1">Belum ada modul PDF.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- KELOMPOK JADWAL LIVE CLASS --}}
                        <div class="space-y-2 pt-2 border-t border-gray-50">
                            <span class="text-[10px] font-extrabold text-amber-600 uppercase tracking-wider block">Jadwal
                                Live Class ({{ $course->schedules->count() }})</span>
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
                                                <p class="text-xs font-bold text-gray-800 leading-tight">
                                                    {{ $schedule->name }}</p>
                                                <div class="flex items-center gap-2 text-[10px] text-gray-400">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span>{{ $schedule->start_time->translatedFormat('d M Y, H:i') }}
                                                        WIB</span>
                                                </div>
                                            </div>
                                            @if ($isToday && !$isPassed)
                                                <span class="flex h-2 w-2 relative">
                                                    <span
                                                        class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-rose-400 opacity-75"></span>
                                                    <span
                                                        class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                                                </span>
                                            @endif
                                        </div>

                                        @if ($schedule->meeting_link)
                                            @if ($isPassed)
                                                <button disabled
                                                    class="w-full py-1.5 bg-gray-100 text-gray-400 text-[10px] font-bold rounded-lg cursor-not-allowed">Sesi
                                                    Berakhir</button>
                                            @else
                                                <a href="{{ $schedule->meeting_link }}" target="_blank"
                                                    class="w-full py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-[10px] font-bold rounded-lg text-center transition block shadow-sm">Gabung
                                                    Zoom / Meet</a>
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
        </div>
    </div>

    {{-- SCRIPT AJAX PROSES PROGRESS CHECKLIST --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.progress-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const itemId = this.getAttribute('data-id');
                    const itemType = this.getAttribute('data-type');
                    const isChecked = this.checked;

                    // Nonaktifkan sementara saat proses berlangsung (mencegah double-click spam)
                    this.disabled = true;

                    // AJAX Request menggunakan Fetch API bawaan browser
                    fetch('/course-progress/toggle', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Token CSRF Laravel Keamanan
                            },
                            body: JSON.stringify({
                                item_id: itemId,
                                item_type: itemType,
                                status: isChecked
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Jika lulus otomatis memicu reload untuk dapetin komponen sertifikat, silakan diaktifkan jika perlu:
                                // if(data.class_completed) window.location.reload();
                                console.log('Progress berhasil diperbarui');
                            } else {
                                // Balikkan state jika gagal di server
                                this.checked = !isChecked;
                                alert('Gagal memperbarui progress, silahkan coba lagi.');
                            }
                        })
                        .catch(error => {
                            this.checked = !isChecked;
                            console.error('Error:', error);
                        })
                        .finally(() => {
                            // Aktifkan kembali checkbox
                            this.disabled = false;
                        });
                });
            });
        });
    </script>
@endsection
