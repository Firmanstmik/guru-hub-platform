@extends('layout.master-app')
@section('content')
    <div class="p-4 md:p-6 max-w-[800px] mx-auto">
        
        <div class="mb-6">
            <a href="/guru/materials" class="inline-flex items-center gap-1 text-xs font-semibold text-gray-400 hover:text-indigo-600 transition mb-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Materi
            </a>
            <h1 class="text-xl font-black text-gray-900 leading-tight">Atur Jadwal Live Class</h1>
            <p class="text-xs text-gray-400 mt-1">
                Membuat sesi pertemuan tatap muka digital otomatis untuk kelas <span class="font-bold text-gray-700">{{ $material->course->title ?? '-' }}</span>.
            </p>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl p-5 md:p-6 shadow-2xs">
            
            <div class="mb-6 p-4 bg-indigo-50/50 border border-indigo-100/50 rounded-xl flex items-start gap-3">
                <span class="text-xl">📢</span>
                <div class="text-xs">
                    <p class="font-bold text-indigo-900 mb-0.5">Materi Pemicu Live:</p>
                    <p class="text-indigo-700 font-medium mb-1">{{ $material->title }}</p>
                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-md font-bold text-[10px]">
                        ✓ Diselesaikan oleh {{ $completedCount }} Siswa
                    </span>
                </div>
            </div>

            <form action="{{ url('/guru/schedules/store') }}" method="POST" class="space-y-4">
                @csrf
                
                <input type="hidden" name="material_id" value="{{ $material->id }}">

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Topik Sesi Pembelajaran</label>
                    <input type="text" name="topic" value="{{ old('topic', 'Live Class: ' . $material->title) }}" required
                        placeholder="Contoh: Pertemuan 1 - Pembahasan Hiragana"
                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Waktu Mulai Sesi</label>
                        <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" required
                            class="w-full border-gray-300 rounded-lg text-sm p-2 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Waktu Selesai Sesi</label>
                        <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" required
                            class="w-full border-gray-300 rounded-lg text-sm p-2 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Platform Video Conference</label>
                    <select name="platform" required
                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="zoom" {{ old('platform') == 'zoom' ? 'selected' : '' }}>Zoom Meeting</option>
                        <option value="google_meet" {{ old('platform') == 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tautan Room Meeting (Link URL)</label>
                    <input type="url" name="meeting_link" value="{{ old('meeting_link') }}"
                        placeholder="https://zoom.us/j/... atau https://meet.google.com/..."
                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Meeting ID (Opsional)</label>
                        <input type="text" name="meeting_id" value="{{ old('meeting_id') }}"
                            placeholder="Contoh: 845 2311 4452"
                            class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Meeting Password / Passcode (Opsional)</label>
                        <input type="text" name="meeting_password" value="{{ old('meeting_password') }}"
                            placeholder="Contoh: 123456"
                            class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-end gap-3 border-t border-gray-50 mt-6">
                    <a href="/guru/materials" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-bold rounded-xl transition">
                        Batalkan
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md transition">
                        🚀 Terbitkan Jadwal Live
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection