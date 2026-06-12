@extends('layout.master-app')
@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div>
                <h3 class="text-gray-700 text-3xl font-semibold">Manajemen Jadwal Sesi Kelas</h3>
                <p class="text-sm text-gray-500 mt-1">Monitoring dan kelola sinkronisasi link Zoom / Google Meet untuk live class.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <form action="/schedules" method="GET" class="flex gap-2 w-full sm:w-auto">
                    <select name="platform" onchange="this.form.submit()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                        <option value="">Semua Platform</option>
                        <option value="zoom" {{ request('platform') == 'zoom' ? 'selected' : '' }}>Zoom</option>
                        <option value="google_meet" {{ request('platform') == 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                    </select>
                    <select name="time_status" onchange="this.form.submit()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                        <option value="">Semua Waktu</option>
                        <option value="upcoming" {{ request('time_status') == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                        <option value="past" {{ request('time_status') == 'past' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </form>

                <button onclick="toggleModal('addScheduleModal')"
                    class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm shadow-xs transition flex items-center justify-center gap-2 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Sesi Baru
                </button>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($schedules as $sched)
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-xs hover:border-gray-200 transition duration-150">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

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
                                            <span class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-100 text-blue-800">Zoom</span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800">Google Meet</span>
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
                                    <a href="{{ $sched->meeting_link }}" target="_blank"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 px-3 py-2 rounded-xl hover:bg-indigo-100 transition shadow-2xs">
                                        <span>Gabung Sesi</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic bg-gray-50 px-3 py-2 rounded-xl border border-gray-100 block">Belum diset</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-2">
                                <button data-schedule='@json($sched)' onclick="handleOpenEditModal(this)"
                                    class="px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-bold rounded-xl transition">
                                    Edit
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-100 rounded-xl p-12 text-center space-y-3 shadow-xs">
                    <div class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto text-lg">📆</div>
                    <div class="space-y-1">
                        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Jadwal Sesi Kosong</h3>
                        <p class="text-xs text-gray-400 max-w-xs mx-auto">Tidak ditemukan jadwal kelas aktif atau pertemuan sinkronus yang terdaftar dalam database saat ini.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($schedules->hasPages())
            <div class="mt-4 p-4 bg-white border border-gray-100 rounded-xl shadow-xs">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>

    <div id="addScheduleModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
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
                        <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">Simpan Jadwal</button>
                        <button type="button" onclick="toggleModal('addScheduleModal')" class="mt-3 sm:mt-0 w-full sm:w-auto bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editScheduleModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
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
                        <button type="submit" class="w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">Perbarui Jadwal</button>
                        <button type="button" onclick="toggleModal('editScheduleModal')" class="mt-3 sm:mt-0 w-full sm:w-auto bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition">Batal</button>
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