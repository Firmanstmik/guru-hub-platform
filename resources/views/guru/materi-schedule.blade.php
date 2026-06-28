@extends('layout.master-app')
@section('content')
    <div class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner">
            <x-app.page-header title="Atur Jadwal Live Class" subtitle="Sesi tatap muka untuk {{ $material->course->title ?? '-' }}." back="/materials" />

        <div class="gh-app-card mb-4 flex items-start gap-3">
                <span class="text-xl">📢</span>
                <div class="text-xs">
                    <p class="font-bold text-indigo-900 mb-0.5">Materi Pemicu Live:</p>
                    <p class="text-indigo-700 font-medium mb-1">{{ $material->title }}</p>
                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-md font-bold text-[10px]">
                        ✓ Diselesaikan oleh {{ $completedCount }} Siswa
                    </span>
                </div>
            </div>

            <form action="{{ url('/guru/schedules/store') }}" method="POST" class="gh-app-card space-y-4">
                @csrf
                
                <input type="hidden" name="material_id" value="{{ $material->id }}">

                <div>
                    <label class="gh-app-label mb-1 block">Topik Sesi Pembelajaran</label>
                    <input type="text" name="topic" value="{{ old('topic', 'Live Class: ' . $material->title) }}" required
                        placeholder="Contoh: Pertemuan 1 - Pembahasan Hiragana"
                        class="gh-app-input">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="gh-app-label mb-1 block">Waktu Mulai Sesi</label>
                        <input type="datetime-local" name="start_time" value="{{ old('start_time') }}" required class="gh-app-input">
                    </div>
                    <div>
                        <label class="gh-app-label mb-1 block">Waktu Selesai Sesi</label>
                        <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" required class="gh-app-input">
                    </div>
                </div>

                <div>
                    <label class="gh-app-label mb-1 block">Platform Video Conference</label>
                    <select name="platform" required class="gh-app-input">
                        <option value="zoom" {{ old('platform') == 'zoom' ? 'selected' : '' }}>Zoom Meeting</option>
                        <option value="google_meet" {{ old('platform') == 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                    </select>
                </div>

                <div>
                    <label class="gh-app-label mb-1 block">Tautan Room Meeting (Link URL)</label>
                    <input type="url" name="meeting_link" value="{{ old('meeting_link') }}"
                        placeholder="https://zoom.us/j/... atau https://meet.google.com/..."
                        class="gh-app-input">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="gh-app-label mb-1 block">Meeting ID (Opsional)</label>
                        <input type="text" name="meeting_id" value="{{ old('meeting_id') }}"
                            placeholder="Contoh: 845 2311 4452"
                            class="gh-app-input">
                    </div>
                    <div>
                        <label class="gh-app-label mb-1 block">Meeting Password / Passcode (Opsional)</label>
                        <input type="text" name="meeting_password" value="{{ old('meeting_password') }}"
                            placeholder="Contoh: 123456"
                            class="gh-app-input">
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-end gap-3 border-t border-gray-50 mt-6">
                    <a href="/materials" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm">Batalkan</a>
                    <button type="submit" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">🚀 Terbitkan Jadwal Live</button>
                </div>
            </form>
        </div>
    </div>
@endsection