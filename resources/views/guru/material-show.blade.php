@extends('layout.master-app')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-xs">
        <div>
            <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Ruang Lingkup Materi</span>
            <h2 class="text-gray-900 text-2xl font-bold mt-1">{{ $material->title }}</h2>
            <p class="text-xs text-gray-500 mt-1">Kelas: <strong class="text-gray-700">{{ $material->course->title ?? 'Kelas Tidak Ditemukan' }}</strong></p>
        </div>
        <a href="/teacher/materials" class="text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 px-4 py-2.5 rounded-xl transition">
            ⬅️ Kembali ke Daftar Materi
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs space-y-4">
                <h3 class="font-bold text-gray-900 text-lg border-b border-gray-50 pb-2">📖 Informasi Deskripsi Materi</h3>
                
                <div class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                    {{ $material->description ?? 'Tidak ada deskripsi tambahan untuk materi ini.' }}
                </div>

                <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl text-xl">
                            📄
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold block uppercase">Lampiran Berkas</span>
                            <span class="text-xs font-semibold text-gray-700">Materi_{{ Str::slug($material->title) }}.pdf</span>
                        </div>
                    </div>
                    <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank"
                       class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:underline">
                        Buka Dokumen Konten ➡️
                    </a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs">
                <h3 class="font-bold text-gray-900 text-sm mb-3">Progression Rate</h3>
                <div class="flex items-center gap-4">
                    <div class="text-3xl font-black text-gray-900">{{ $material->completed_count ?? 0 }}</div>
                    <div class="text-xs text-gray-400 leading-tight">Siswa terdeteksi telah menuntaskan aktivitas membaca materi ini secara mandiri.</div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-xs space-y-4">
                <div class="flex items-center gap-2 border-b border-gray-50 pb-3">
                    <span class="text-xl">📝</span>
                    <div>
                        <h4 class="font-bold text-gray-900 text-base">Evaluasi Kuis Materi</h4>
                        <p class="text-[11px] text-gray-400">Setiap materi memiliki maksimal 1 kuis evaluasi.</p>
                    </div>
                </div>

                {{-- KONDISI A: JIKA MATERI INI SUDAH MEMILIKI KUIS --}}
                @if($material->quiz)
                    <div class="bg-indigo-50/70 border border-indigo-100 rounded-xl p-4 space-y-3">
                        <div>
                            <h5 class="font-bold text-indigo-950 text-sm">{{ $material->quiz->title }}</h5>
                            <p class="text-[11px] text-gray-500 mt-0.5">
                                ⏱️ {{ $material->quiz->duration_minutes }} Menit | ❓ {{ $material->quiz->questions_count ?? $material->quiz->questions->count() }} Pertanyaan
                            </p>
                        </div>
                        
                        <div class="flex flex-col gap-2 pt-1">
                            <a href="{{ url('/quiz/'.$material->quiz->id.'/build') }}" 
                               class="w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs py-2.5 rounded-lg transition shadow-xs">
                                ⚙️ Kelola & Tambah Soal
                            </a>
                            <a href="{{ url('/quiz/'.$material->quiz->id.'/review') }}" 
                               class="w-full text-center bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold text-xs py-2.5 rounded-lg transition">
                                📊 Periksa Nilai Jawaban Siswa
                            </a>
                        </div>
                    </div>

                {{-- KONDISI B: JIKA MATERI INI BELUM MEMILIKI KUIS (TAMPILKAN FORM AKTIVASI) --}}
                @else
                    <form action="{{ url('/quiz/store') }}" method="POST" class="space-y-3.5">
                        @csrf
                        
                        <input type="hidden" name="material_id" value="{{ $material->id }}">

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Judul Kuis</label>
                            <input type="text" name="title" required placeholder="Contoh: Evaluasi Coding Bab 1"
                                class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl p-2.5 focus:ring-1 focus:ring-indigo-500 focus:outline-hidden">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi / Petunjuk</label>
                            <textarea name="description" rows="2" placeholder="Contoh: Kerjakan jujur, terdapat soal pilihan ganda & upload PDF..."
                                class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl p-2.5 focus:ring-1 focus:ring-indigo-500 focus:outline-hidden"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Durasi Pengerjaan</label>
                            <div class="relative flex items-center">
                                <input type="number" name="duration_minutes" min="1" value="30" required
                                    class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl p-2.5 pr-12 focus:ring-1 focus:ring-indigo-500 focus:outline-hidden">
                                <span class="absolute right-3 text-xs text-gray-400 font-medium pointer-events-none">Menit</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl text-xs transition shadow-xs">
                            🚀 Aktifkan Kuis Baru
                        </button>
                    </form>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection