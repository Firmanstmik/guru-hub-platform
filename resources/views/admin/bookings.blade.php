@extends('layout.template')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h3 class="text-gray-700 text-3xl font-semibold">Monitoring Booking Kelas</h3>
            <p class="text-sm text-gray-500 mt-1">Pantau transaksi pemesanan kelas dan status pembayaran murid.</p>
        </div>
        
        <form action="/bookings" method="GET" class="flex items-center gap-2 w-full md:w-auto">
            <select name="status" onchange="this.form.submit()" class="w-full md:w-48 bg-white border border-gray-300 rounded-lg shadow-sm px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success (Berhasil)</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed (Gagal)</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired (Kadaluarsa)</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled (Batal)</option>
            </select>
        </form>
    </div>

    <div class="space-y-3">
        @forelse($bookings as $booking)
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-xs hover:border-gray-200 transition duration-150">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    
                    <div class="flex-1 min-w-0 space-y-2">
                        <div class="flex items-start gap-3">
                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center font-bold text-indigo-700 uppercase flex-shrink-0 text-xs mt-0.5">
                                {{ substr($booking->student->name ?? 'NN', 0, 2) }}
                            </div>
                            <div class="min-w-0 space-y-0.5">
                                <span class="inline-flex text-[10px] font-mono bg-gray-100 text-gray-700 px-2 py-0.5 rounded-md font-bold tracking-wider mb-1">
                                    {{ $booking->transaction_code }}
                                </span>
                                <h4 class="text-sm font-bold text-gray-900 truncate">
                                    {{ $booking->student->name ?? 'Siswa Non-Aktif' }}
                                </h4>
                                @if($booking->note)
                                    <p class="text-xs text-gray-400 italic truncate" title="{{ $booking->note }}">
                                        "{{ $booking->note }}"
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        @if($booking->course)
                            <h5 class="text-sm font-bold text-gray-800 truncate">
                                📖 {{ $booking->course->title }}
                            </h5>
                            <p class="text-[11px] text-gray-400 mt-1">
                                👨‍🏫 Guru: <span class="font-medium text-gray-600">{{ $booking->course->teacher->name ?? 'Tidak Ada' }}</span>
                            </p>
                        @else
                            <h5 class="text-sm font-bold text-rose-600 italic">
                                ⚠️ Kelas Telah Dihapus
                            </h5>
                        @endif
                    </div>

                    <div class="text-xs text-gray-600 leading-relaxed min-w-[150px] border-t lg:border-t-0 pt-2 lg:pt-0 border-gray-50">
                        <span class="block text-[10px] text-gray-400 uppercase tracking-wider font-bold mb-0.5">Total Pembayaran</span>
                        <span class="font-bold text-gray-900 text-sm block text-indigo-600">
                            Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                        </span>
                        <span class="text-gray-400 text-[10px] block mt-0.5">
                            📅 {{ $booking->created_at->isoFormat('D MMMM YYYY HH:mm') }}
                        </span>
                    </div>

                    <div class="flex flex-wrap sm:flex-nowrap items-center justify-between lg:justify-end gap-3 border-t lg:border-t-0 pt-3 lg:pt-0 border-gray-50 flex-shrink-0">
                        <div class="w-24 text-center sm:text-left">
                            @if($booking->status === 'pending')
                                <span class="inline-block w-full text-center px-2.5 py-1 text-xs font-bold bg-amber-50 text-amber-700 rounded-lg border border-amber-100">Pending</span>
                            @elseif($booking->status === 'success')
                                <span class="inline-block w-full text-center px-2.5 py-1 text-xs font-bold bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-100">Success</span>
                            @elseif($booking->status === 'failed')
                                <span class="inline-block w-full text-center px-2.5 py-1 text-xs font-bold bg-rose-50 text-rose-700 rounded-lg border border-rose-100">Failed</span>
                            @elseif($booking->status === 'expired')
                                <span class="inline-block w-full text-center px-2.5 py-1 text-xs font-bold bg-orange-50 text-orange-700 rounded-lg border border-orange-100">Expired</span>
                            @else
                                <span class="inline-block w-full text-center px-2.5 py-1 text-xs font-bold bg-gray-50 text-gray-600 rounded-lg border border-gray-200">Cancelled</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2">
                            <button onclick="openStatusModal({{ $booking }})" 
                                class="px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition">
                                Kelola Status
                            </button>
                            
                            <form action="/bookings/{{ $booking->id }}" method="POST" onsubmit="return confirm('Hapus riwayat booking ini dari sistem?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-2 bg-gray-50 hover:bg-rose-50 text-gray-400 hover:text-rose-600 text-xs font-bold rounded-xl transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="bg-white border border-gray-100 rounded-xl p-12 text-center space-y-3 shadow-xs">
                <div class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto text-lg">
                    📋
                </div>
                <div class="space-y-1">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Riwayat Kosong</h3>
                    <p class="text-xs text-gray-400 max-w-xs mx-auto">Tidak ada transaksi booking kelas yang sesuai dengan kriteria filter saat ini.</p>
                </div>
            </div>
        @endforelse
    </div>

    @if($bookings->hasPages())
        <div class="mt-4 p-4 bg-white border border-gray-100 rounded-xl shadow-xs">
            {{ $bookings->links() }}
        </div>
    @endif
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
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Pembaruan Status Transaksi</h3>
                    <p class="text-xs text-gray-500 mb-4">Ubah status verifikasi pembayaran atau pemesanan kelas murid secara manual.</p>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Pilih Status Baru</label>
                            <select id="booking_status" name="status" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm p-2.5 border focus:outline-none">
                                <option value="pending">Pending (Menunggu Pembayaran)</option>
                                <option value="success">Success (Pembayaran Berhasil / Aktif)</option>
                                <option value="failed">Failed (Pembayaran Gagal Sistem)</option>
                                <option value="expired">Expired (Batas Waktu Habis)</option>
                                <option value="cancelled">Cancelled (Transaksi Dibatalkan)</option>
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
        document.getElementById('booking_status').value = booking.status;
        const form = document.getElementById('statusForm');
        form.action = `/bookings/${booking.id}`;
        document.getElementById('statusBookingModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('statusBookingModal').classList.add('hidden');
    }
</script>
@endsection