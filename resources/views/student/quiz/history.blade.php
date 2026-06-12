@extends('layout.master-app')
@section('content')
<div class="p-6 max-w-5xl mx-auto space-y-6">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-gray-100 pb-5">
        <div>
            <h2 class="text-gray-900 text-2xl font-black tracking-tight">📜 Riwayat Tugas & Kuis</h2>
            <p class="text-xs text-gray-400 mt-1">Pantau nilai evaluasi capaian belajar Anda dan status koreksi tugas dari guru.</p>
        </div>
        <a href="{{url('/siswa-dashboard')}}" class="text-xs font-bold text-indigo-600 hover:underline flex items-center gap-1 bg-indigo-50 px-3 py-2 rounded-xl">
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Kondisi jika belum pernah mengerjakan kuis sama sekali -->
    @if($quizHistory->isEmpty())
        <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center shadow-xs">
            <div class="text-4xl mb-3">📭</div>
            <h4 class="text-gray-700 font-bold text-sm">Belum Ada Riwayat Tugas</h4>
            <p class="text-xs text-gray-400 mt-1 max-w-xs mx-auto">Anda belum pernah mengambil kuis atau mengumpulkan tugas apapun sejauh ini.</p>
        </div>
    @else
        <!-- Grid List Riwayat Kuis -->
        <div class="grid grid-cols-1 gap-4">
            @foreach($quizHistory as $quiz)
                <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition hover:border-gray-200">
                    
                    <!-- Detail Kuis & Materi -->
                    <div class="space-y-1.5">
                        <span class="px-2.5 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-md uppercase tracking-wider">
                            📚 {{ $quiz->material->title }}
                        </span>
                        <h3 class="text-gray-900 font-bold text-base leading-snug">{{ $quiz->title }}</h3>
                        <div class="flex items-center gap-3 text-xs text-gray-400 font-medium">
                            <span>⏱️ {{ $quiz->duration_minutes }} Menit</span>
                            <span>•</span>
                            <span>📅 Dikerjakan: {{ \Carbon\Carbon::parse($quiz->submitted_at)->translatedFormat('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>

                    <!-- Status Kelulusan / Skor -->
                    <div class="flex items-center gap-4 shrink-0 sm:text-right self-end sm:self-center">
                        @if($quiz->need_review)
                            <!-- Jika ada soal esai/file yang belum diperiksa guru -->
                            <div class="bg-amber-50 border border-amber-100 text-amber-800 px-4 py-2.5 rounded-xl text-center">
                                <span class="text-[10px] uppercase tracking-wider font-bold block text-amber-500">Status Nilai</span>
                                <span class="text-xs font-bold flex items-center gap-1">⏳ Menunggu Koreksi</span>
                            </div>
                        @else
                            <!-- Jika semua nilai sudah keluar (Pilihan Ganda / Esai sudah dinilai semua) -->
                            <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 px-5 py-2.5 rounded-xl text-center min-w-[100px]">
                                <span class="text-[10px] uppercase tracking-wider font-bold block text-emerald-500">Skor Anda</span>
                                <span class="text-lg font-black font-mono">
                                    {{ $quiz->student_score }} <span class="text-xs font-normal text-gray-400">/ {{ $quiz->max_score }}</span>
                                </span>
                            </div>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection