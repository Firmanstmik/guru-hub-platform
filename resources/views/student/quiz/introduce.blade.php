@extends('layout.master-app')
@section('content')
<div class="p-6 max-w-2xl mx-auto mt-10">
    <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-xs space-y-6 text-center">
        
        <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center text-2xl mx-auto">
            📝
        </div>

        <div>
            <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Evaluasi Materi: {{ $material->title }}</span>
            <h2 class="text-gray-900 text-2xl font-black mt-1">{{ $quiz->title }}</h2>
            <p class="text-xs text-gray-400 mt-2 max-w-md mx-auto">{{ $quiz->description ?? 'Bacalah instruksi soal dengan seksama sebelum menjawab.' }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4 border-t border-b border-gray-50 py-4 text-sm max-w-sm mx-auto">
            <div class="text-left">
                <span class="text-gray-400 text-xs block">Durasi Ujian:</span>
                <strong class="text-gray-800 text-base">⏱️ {{ $quiz->duration_minutes }} Menit</strong>
            </div>
            <div class="text-left">
                <span class="text-gray-400 text-xs block">Jumlah Soal:</span>
                <strong class="text-gray-800 text-base">❓ {{ $quiz->questions->count() }} Butir</strong>
            </div>
        </div>

        <div class="pt-2">
            @if($alreadyTaken)
                <div class="p-3 bg-rose-50 border border-rose-100 text-rose-800 text-xs font-bold rounded-xl inline-block">
                    🔒 Anda sudah menyelesaikan kuis evaluasi untuk materi ini.
                </div>
            @else
                <div class="flex flex-col gap-3 max-w-xs mx-auto">
                    <a href="{{ url('/quiz/'.$quiz->id.'/take') }}" 
                       class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm py-3 rounded-xl transition shadow-xs">
                        Mulai Kerjakan Sekarang 🚀
                    </a>
                    <a href="/my-courses" class="text-xs font-semibold text-gray-500 hover:underline">
                        Kembali ke Kelas
                    </a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection