@extends('layout.template')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
        <div>
            <h3 class="text-gray-700 text-3xl font-semibold">Manajemen Jadwal Sesi Kelas</h3>
            <p class="text-sm text-gray-500 mt-1">Monitoring dan kelola sinkronisasi link Zoom / Google Meet untuk live class.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
            <form action="/schedules" method="GET" class="flex gap-2 w-full sm:w-auto">
                <select name="platform" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">Semua Platform</option>
                    <option value="zoom" {{ request('platform') == 'zoom' ? 'selected' : '' }}>Zoom</option>
                    <option value="google_meet" {{ request('platform') == 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                </select>
                <select name="time_status" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">Semua Waktu</option>
                    <option value="upcoming" {{ request('time_status') == 'upcoming' ? 'selected' : '' }}>Akan Datang</option>
                    <option value="past" {{ request('time_status') == 'past' ? 'selected' : '' }}>Selesai</option>
                </select>
            </form>

            <button onclick="toggleModal('addScheduleModal')" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm shadow-xs transition flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Sesi Baru
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Kelas / Kursus</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Topik Sesi</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Waktu Pelaksanaan</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Platform</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Akses Ruang</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @forelse($schedules as $sched)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $sched->course->title }}</div>
                            <div class="text-xs text-gray-400">Guru: {{ $sched->course->teacher->name }}</div>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">{{ $sched->topic }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600 leading-relaxed">
                            <span class="font-medium text-gray-900">{{ $sched->start_time->isoFormat('dddd, D MMMM YYYY') }}</span><br>
                            {{ $sched->start_time->format('H:i') }} - {{ $sched->end_time->format('H:i') }} WIB
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($sched->platform === 'zoom')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Zoom</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Google Meet</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs">
                            @if($sched->meeting_link)
                                <a href="{{ $sched->meeting_link }}" target="_blank" class="inline-flex items-center gap-1 font-semibold text-indigo-600 hover:underline">
                                    Gabung Sesi
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @else
                                <span class="text-gray-400 italic">Belum diset</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium flex justify-end gap-3">
                            <button onclick="openEditModal({{ $sched }})" class="text-amber-600 hover:text-amber-900">Edit</button>
                            
                            <form action="/schedules/{{ $sched->id }}" method="POST" onsubmit="return confirm('Hapus jadwal sesi live ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-900">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">Tidak ditemukan jadwal kelas aktif saat ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $schedules->links() }}
        </div>
    </div>
</div>

<div id="addScheduleModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true"><div class="absolute inset-0 bg-gray-500 opacity-75"></div></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
            <form action="/schedules" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Buat Jadwal Sesi Baru</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Kelas</label>
                            <select name="course_id" required class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }} (Guru: {{ $course->teacher->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Topik Sesi Pembelajaran</label>
                            <input type="text" name="topic" required placeholder="Contoh: Pertemuan 1 - Pembahasan Hiragana" class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Waktu Mulai Sesi</label>
                                <input type="datetime-local" name="start_time" required class="w-full border-gray-300 rounded-lg text-sm p-2 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Waktu Selesai Sesi</label>
                                <input type="datetime-local" name="end_time" required class="w-full border-gray-300 rounded-lg text-sm p-2 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Platform Video Conference</label>
                            <select name="platform" required class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="zoom">Zoom Meeting</option>
                                <option value="google_meet">Google Meet</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tautan Room Meeting (Link URL)</label>
                            <input type="url" name="meeting_link" placeholder="https://zoom.us/j/... atau https://meet.google.com/..." class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
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
        <div class="fixed inset-0 transition-opacity" aria-hidden="true"><div class="absolute inset-0 bg-gray-500 opacity-75"></div></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Modifikasi Data Jadwal Sesi</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Kelas</label>
                            <select id="edit_course_id" name="course_id" required class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500">
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
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
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Platform</label>
                            <select id="edit_platform" name="platform" required class="w-full border-gray-300 rounded-lg text-sm p-2.5 border">
                                <option value="zoom">Zoom Meeting</option>
                                <option value="google_meet">Google Meet</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Tautan Room Meeting (Link URL)</label>
                            <input type="url" id="edit_meeting_link" name="meeting_link" class="w-full border-gray-300 rounded-lg text-sm p-2.5 border">
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
    function toggleModal(modalId) {
        document.getElementById(modalId).classList.toggle('hidden');
    }

    function openEditModal(schedule) {
        document.getElementById('edit_course_id').value = schedule.course_id;
        document.getElementById('edit_topic').value = schedule.topic;
        
        if(schedule.start_time) {
            document.getElementById('edit_start_time').value = schedule.start_time.substring(0, 16);
        }
        if(schedule.end_time) {
            document.getElementById('edit_end_time').value = schedule.end_time.substring(0, 16);
        }
        
        document.getElementById('edit_platform').value = schedule.platform;
        document.getElementById('edit_meeting_link').value = schedule.meeting_link || '';

        const form = document.getElementById('editForm');
        // PENGUBAHAN: Penyesuaian ke hardcoded URL untuk rute pembaruan jadwal di JavaScript
        form.action = `/schedules/${schedule.id}`;

        toggleModal('editScheduleModal');
    }
</script>
@endsection