@extends('layout.master-app')
@section('content')
    <div class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner">
            <x-app.page-header title="Jadwal Mengajar" subtitle="Kelola sesi live class Zoom / Google Meet.">
                <x-slot:action>
                    <button type="button" onclick="toggleModal('addScheduleModal')" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">
                        <x-ui.lucide name="plus" class="h-4 w-4" /> Baru
                    </button>
                </x-slot:action>
            </x-app.page-header>

            <div class="gh-app-filter-bar">
                <form action="/schedules" method="GET" class="flex flex-1 flex-col gap-2 sm:flex-row">
                    <select name="platform" onchange="this.form.submit()" class="gh-app-select flex-1">
                        <option value="">Semua Platform</option>
                        <option value="zoom" {{ request('platform') == 'zoom' ? 'selected' : '' }}>Zoom</option>
                        <option value="google_meet" {{ request('platform') == 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                    </select>
                    <select name="time_status" onchange="this.form.submit()" class="gh-app-select flex-1">
                        <option value="">Semua Waktu</option>
                        <option value="upcoming" {{ request('time_status') == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                        <option value="past" {{ request('time_status') == 'past' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </form>
            </div>

        <div class="gh-app-timeline">
            @forelse($schedules as $sched)
                <div class="gh-app-timeline-item">
                    <span class="gh-app-timeline-dot"></span>
                    <div class="gh-app-timeline-card">
                    <div class="space-y-3">

                        <div class="flex-1 min-w-0 space-y-2">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-md flex-shrink-0 border {{ $sched->platform === 'zoom' ? 'bg-blue-50 border-blue-100 text-blue-600' : 'bg-emerald-50 border-emerald-100 text-emerald-600' }}">
                                    📅
                                </div>
                                <div class="min-w-0 space-y-0.5">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="text-sm font-bold text-gray-900 truncate max-w-md" title="{{ $sched->course->title ?? 'Kelas Tidak Ditemukan' }}">
                                            {{ $sched->course->title ?? 'Kelas Tidak Ditemukan' }}
                                        </h4>
                                        @if ($sched->platform === 'zoom')
                                            <x-app.badge variant="info">Zoom</x-app.badge>
                                        @else
                                            <x-app.badge variant="success">Google Meet</x-app.badge>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-700 font-semibold">
                                        Topik: <span class="font-medium text-gray-500">{{ $sched->topic }}</span>
                                    </p>
                                    @if($sched->material)
                                        <p class="text-[11px] text-gray-500">
                                            📖 Materi: <span class="font-medium text-indigo-600">{{ $sched->material->title ?? 'Detail Materi' }}</span>
                                        </p>
                                    @endif
                                    
                                    @if($sched->meeting_id || $sched->meeting_password)
                                        <div class="flex gap-3 text-[11px] text-gray-400 bg-gray-50 p-1.5 rounded-md mt-1 w-fit">
                                            @if($sched->meeting_id) <span>ID: <strong class="text-gray-600">{{ $sched->meeting_id }}</strong></span> @endif
                                            @if($sched->meeting_password) <span>Pass: <strong class="text-gray-600">{{ $sched->meeting_password }}</strong></span> @endif
                                        </div>
                                    @endif

                                    @role('admin')
                                        <div class="text-[11px] text-gray-400 mt-1">
                                            👨‍🏫 Guru Pengajar: <span class="font-medium text-gray-600">{{ $sched->course->teacher->name ?? 'Tidak Ada' }}</span>
                                        </div>
                                    @endrole
                                </div>
                            </div>
                        </div>

                        <div class="text-xs text-gray-600 leading-relaxed min-w-[200px] border-t lg:border-t-0 pt-2 lg:pt-0 border-gray-50">
                            <span class="block text-[10px] text-gray-400 uppercase tracking-wider font-bold mb-0.5">Waktu Pelaksanaan</span>
                            <span class="font-semibold text-gray-900">
                                {{-- Pastikan start_time sudah di-cast sebagai Carbon di Model --}}
                                {{ is_string($sched->start_time) ? \Carbon\Carbon::parse($sched->start_time)->isoFormat('dddd, D MMMM YYYY') : $sched->start_time->isoFormat('dddd, D MMMM YYYY') }}
                            </span>
                            <span class="block text-gray-500 text-[11px]">
                                {{ is_string($sched->start_time) ? \Carbon\Carbon::parse($sched->start_time)->format('H:i') : $sched->start_time->format('H:i') }} - 
                                {{ is_string($sched->end_time) ? \Carbon\Carbon::parse($sched->end_time)->format('H:i') : $sched->end_time->format('H:i') }} WIB
                            </span>
                        </div>

                        <div class="flex items-center justify-between lg:justify-end gap-4 border-t lg:border-t-0 pt-3 lg:pt-0 border-gray-50 flex-shrink-0">
                            <div>
                                @if ($sched->meeting_link)
                                    <a href="{{ $sched->meeting_link }}" target="_blank" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm inline-flex">
                                        <x-ui.lucide name="video" class="h-3.5 w-3.5" /> Gabung Sesi
                                    </a>
                                @else
                                    <x-app.badge variant="neutral">Link belum diset</x-app.badge>
                                @endif
                                <button data-schedule='@json($sched)' onclick="handleOpenEditModal(this)" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm">
                                    <x-ui.lucide name="edit" class="h-3.5 w-3.5" /> Edit
                                </button>
                            </div>
                    </div>
                    </div>
                </div>
            @empty
                <x-app.empty-state icon="calendar-x" title="Jadwal kosong" description="Belum ada sesi live class terjadwal." action-label="Buat Sesi" action-href="#" />
            @endforelse
        </div>

        @if ($schedules->hasPages())
            <div class="gh-app-card mt-4">{{ $schedules->links() }}</div>
        @endif
        </div>
    </div>

    <div id="addScheduleModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="toggleModal('addScheduleModal')" aria-hidden="true"></div>
        <div class="relative z-10 flex min-h-full items-end justify-center p-4 pb-24 sm:items-center sm:pb-4">
            <div class="max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-xl border border-gray-100 bg-white shadow-xl" onclick="event.stopPropagation()">
                <form action="/schedules" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[80vh] overflow-y-auto">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Buat Jadwal Sesi Baru</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Kelas</label>
                                    <select name="course_id" required
                                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                        <option value="" disabled selected>-- Pilih Kelas --</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->title }} (Guru: {{ $course->teacher->name ?? 'N/A' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Materi (Opsional)</label>
                                    <select name="material_id"
                                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                        <option value="">-- Tanpa Materi / Hubungkan Nanti --</option>
                                        @foreach ($materials as $material)
                                            <option value="{{ $material->id }}">{{ $material->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Topik Sesi Pembelajaran</label>
                                <input type="text" name="topic" required placeholder="Contoh: Pertemuan 1 - Pembahasan Hiragana"
                                    class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Waktu Mulai Sesi</label>
                                    <input type="datetime-local" name="start_time" required
                                        class="w-full border-gray-300 rounded-lg text-sm p-2 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Waktu Selesai Sesi</label>
                                    <input type="datetime-local" name="end_time" required
                                        class="w-full border-gray-300 rounded-lg text-sm p-2 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Platform Video Conference</label>
                                    <select name="platform" required
                                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                        <option value="zoom">Zoom Meeting</option>
                                        <option value="google_meet">Google Meet</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Meeting ID (Opsional)</label>
                                    <input type="text" name="meeting_id" placeholder="Contall: 812 3456 7890"
                                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tautan Room Meeting (Link URL)</label>
                                    <input type="url" name="meeting_link" placeholder="https://zoom.us/j/... atau https://meet.google.com/..."
                                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Meeting Password (Opsional)</label>
                                    <input type="text" name="meeting_password" placeholder="Contoh: rahasia123"
                                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">Simpan Jadwal</button>
                        <button type="button" onclick="toggleModal('addScheduleModal')" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm mt-3 sm:mt-0">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editScheduleModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="toggleModal('editScheduleModal')" aria-hidden="true"></div>
        <div class="relative z-10 flex min-h-full items-end justify-center p-4 pb-24 sm:items-center sm:pb-4">
            <div class="max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-xl border border-gray-100 bg-white shadow-xl" onclick="event.stopPropagation()">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 max-h-[80vh] overflow-y-auto">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Modifikasi Data Jadwal Sesi</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Kelas</label>
                                    <select id="edit_course_id" name="course_id" required
                                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500">
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Materi (Opsional)</label>
                                    <select id="edit_material_id" name="material_id"
                                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500">
                                        <option value="">-- Tanpa Materi --</option>
                                        @foreach ($materials as $material)
                                            <option value="{{ $material->id }}">{{ $material->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Topik Sesi Pembelajaran</label>
                                <input type="text" id="edit_topic" name="topic" required class="w-full border-gray-300 rounded-lg text-sm p-2.5 border">
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Waktu Mulai Sesi</label>
                                    <input type="datetime-local" id="edit_start_time" name="start_time" required class="w-full border-gray-300 rounded-lg text-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Waktu Selesai Sesi</label>
                                    <input type="datetime-local" id="edit_end_time" name="end_time" required class="w-full border-gray-300 rounded-lg text-sm p-2 border">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Platform</label>
                                    <select id="edit_platform" name="platform" required class="w-full border-gray-300 rounded-lg text-sm p-2.5 border">
                                        <option value="zoom">Zoom Meeting</option>
                                        <option value="google_meet">Google Meet</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Meeting ID (Opsional)</label>
                                    <input type="text" id="edit_meeting_id" name="meeting_id" class="w-full border-gray-300 rounded-lg text-sm p-2.5 border">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tautan Room Meeting (Link URL)</label>
                                    <input type="url" id="edit_meeting_link" name="meeting_link" class="w-full border-gray-300 rounded-lg text-sm p-2.5 border">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Meeting Password (Opsional)</label>
                                    <input type="text" id="edit_meeting_password" name="meeting_password" class="w-full border-gray-300 rounded-lg text-sm p-2.5 border">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">Perbarui Jadwal</button>
                        <button type="button" onclick="toggleModal('editScheduleModal')" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm mt-3 sm:mt-0">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function handleOpenEditModal(button) {
            const scheduleData = JSON.parse(button.getAttribute('data-schedule'));
            openEditModal(scheduleData);
        }

        function toggleModal(modalId) {
            document.getElementById(modalId).classList.toggle('hidden');
        }

        function openEditModal(schedule) {
            document.getElementById('edit_course_id').value = schedule.course_id;
            document.getElementById('edit_material_id').value = schedule.material_id || '';
            document.getElementById('edit_topic').value = schedule.topic;

            // Formating data datetime-local (YYYY-MM-DDTHH:mm)
            if (schedule.start_time) {
                let startTime = schedule.start_time.includes(' ') ? schedule.start_time.replace(' ', 'T') : schedule.start_time;
                document.getElementById('edit_start_time').value = startTime.substring(0, 16);
            }
            if (schedule.end_time) {
                let endTime = schedule.end_time.includes(' ') ? schedule.end_time.replace(' ', 'T') : schedule.end_time;
                document.getElementById('edit_end_time').value = endTime.substring(0, 16);
            }

            document.getElementById('edit_platform').value = schedule.platform;
            document.getElementById('edit_meeting_id').value = schedule.meeting_id || '';
            document.getElementById('edit_meeting_link').value = schedule.meeting_link || '';
            document.getElementById('edit_meeting_password').value = schedule.meeting_password || '';

            const form = document.getElementById('editForm');
            form.action = `/schedules/${schedule.id}`;

            toggleModal('editScheduleModal');
        }
    </script>
@endsection