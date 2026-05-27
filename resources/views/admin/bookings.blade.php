@extends('layout.template')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h3 class="text-gray-700 text-3xl font-semibold">Monitoring Booking Kelas</h3>
            <p class="text-sm text-gray-500 mt-1">Pantau pemesanan sesi live class dan status kehadiran murid.</p>
        </div>
        
        <!-- PENGUBAHAN: Menggunakan hardcoded URL langsung pada form index filter -->
        <form action="/bookings" method="GET" class="flex items-center gap-2 w-full md:w-auto">
            <select name="status" onchange="this.form.submit()" class="w-full md:w-48 bg-white border border-gray-300 rounded-lg shadow-sm px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                <option value="">Semua Status</option>
                <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Booked (Dipesan)</option>
                <option value="attended" {{ request('status') == 'attended' ? 'selected' : '' }}>Attended (Hadir)</option>
                <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent (Alpa)</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled (Batal)</option>
            </select>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Murid</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Kelas & Sesi</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Guru Pengajar</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Waktu Sesi</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center font-semibold text-indigo-700 uppercase">
                                    {{ substr($booking->student->name, 0, 2) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $booking->student->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->student->phone_number ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $booking->schedule->course->title }}</div>
                            <div class="text-xs text-indigo-600 font-medium mt-0.5">Topik: {{ $booking->schedule->topic }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                            {{ $booking->schedule->course->teacher->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600 text-xs leading-relaxed">
                            {{ $booking->schedule->start_time->isoFormat('D MMMM YYYY') }}<br>
                            <span class="text-gray-400">{{ $booking->schedule->start_time->format('H:i') }} - {{ $booking->schedule->end_time->format('H:i') }} WIB</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($booking->status === 'booked')
                                <span class="px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-full border border-blue-200">Booked</span>
                            @elseif($booking->status === 'attended')
                                <span class="px-2.5 py-1 text-xs font-medium bg-emerald-50 text-emerald-700 rounded-full border border-emerald-200">Attended</span>
                            @elseif($booking->status === 'absent')
                                <span class="px-2.5 py-1 text-xs font-medium bg-rose-50 text-rose-700 rounded-full border border-rose-200">Absent</span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-medium bg-gray-50 text-gray-700 rounded-full border border-gray-200">Cancelled</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium flex justify-end gap-3 mt-1">
                            <button onclick="openStatusModal({{ $booking }})" class="text-indigo-600 hover:text-indigo-900">Kelola Status</button>
                            
                            <!-- PENGUBAHAN: Menggunakan hardcoded URL untuk aksi menghapus data booking -->
                            <form action="/bookings/{{ $booking->id }}" method="POST" onsubmit="return confirm('Hapus riwayat booking ini dari sistem?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-rose-600 transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">Tidak ada riwayat booking jadwal ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $bookings->links() }}
        </div>
    </div>
</div>

<div id="statusBookingModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
            <form id="statusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Pembaruan Dokumen Booking</h3>
                    <p class="text-xs text-gray-500 mb-4">Ubah status kehadiran atau pemesanan kelas murid secara manual.</p>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Pilih Status Baru</label>
                            <select id="booking_status" name="status" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm p-2.5 border">
                                <option value="booked">Booked (Siswa Memesan Slot)</option>
                                <option value="attended">Attended (Siswa Hadir di Sesi)</option>
                                <option value="absent">Absent (Siswa Mangkir/Tidak Hadir)</option>
                                <option value="cancelled">Cancelled (Sesi Dibatalkan)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="submit" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition shadow-xs">Simpan Perubahan</button>
                    <button type="button" onclick="closeModal()" class="mt-3 sm:mt-0 w-full sm:w-auto bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openStatusModal(booking) {
        // Set nilai dropdown status saat ini
        document.getElementById('booking_status').value = booking.status;
        
        // PENGUBAHAN: Penyesuaian ke hardcoded URL untuk rute pembaruan status booking di JavaScript
        const form = document.getElementById('statusForm');
        form.action = `/bookings/${booking.id}`;
        
        // Munculkan modal
        document.getElementById('statusBookingModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('statusBookingModal').classList.add('hidden');
    }
</script>
@endsection