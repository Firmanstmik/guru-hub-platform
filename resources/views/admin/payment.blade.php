@extends('layout.template')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
        <div>
            <h3 class="text-gray-700 text-3xl font-semibold">Verifikasi Pembayaran Kursus</h3>
            <p class="text-sm text-gray-500 mt-1">Periksa bukti transfer manual masuk untuk membuka akses kelas siswa Guru Hub.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
            <!-- PENGUBAHAN: Menggunakan hardcoded URL langsung pada form index filter -->
            <form action="/payments" method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice / siswa..." class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending (Butuh Verifikasi)</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success (Lunas)</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed (Ditolak)</option>
                </select>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">No. Invoice</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Nama Siswa</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Program Kelas</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Total Nominal</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Bukti Transfer</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-xs font-bold text-gray-900">
                            {{ $payment->invoice_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700">
                            {{ $payment->student->name }}
                        </td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                            {{ $payment->course->title }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($payment->payment_proof_path)
                                <button onclick="openProofModal('{{ asset('storage/' . $payment->payment_proof_path) }}')" class="inline-flex items-center gap-1 text-xs text-indigo-600 font-medium hover:underline bg-indigo-50 px-2 py-1 rounded">
                                    Lihat Gambar
                                </button>
                            @else
                                <span class="text-xs text-gray-400 italic">Belum unggah</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($payment->status === 'success')
                                <span class="px-2.5 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full">Success</span>
                            @elseif($payment->status === 'pending')
                                <span class="px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 rounded-full animate-pulse">Pending</span>
                            @else
                                <span class="px-2.5 py-0.5 text-xs font-medium bg-rose-100 text-rose-800 rounded-full">Failed</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium flex justify-end gap-2 mt-0.5">
                            @if($payment->status === 'pending')
                                <!-- PENGUBAHAN: Menggunakan hardcoded URL untuk aksi pengesahan pembayaran -->
                                <form action="/payments/{{ $payment->id }}/approve" method="POST" onsubmit="return confirm('Apakah Anda yakin bukti transfer ini sah?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-md shadow-xs">Sahkan</button>
                                </form>
                                
                                <button onclick="openRejectModal({{ $payment->id }}, '{{ $payment->invoice_number }}')" class="bg-rose-100 hover:bg-rose-200 text-rose-700 px-3 py-1.5 rounded-md">
                                    Tolak
                                </button>
                            @else
                                <div class="text-left text-xxs text-gray-400 leading-tight">
                                    Oleh: {{ $payment->verifier->name ?? 'System' }}<br>
                                    <span>{{ $payment->verified_at ? $payment->verified_at->format('d/m/Y H:i') : '-' }}</span>
                                    @if($payment->rejection_reason)
                                        <p class="text-rose-500 mt-0.5 font-sans italic max-w-[150px] truncate" title="{{ $payment->rejection_reason }}">Ket: {{ $payment->rejection_reason }}</p>
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">Tidak ada riwayat rekaman transaksi ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $payments->links() }}
        </div>
    </div>
</div>

<div id="proofModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 transition-opacity" onclick="closeModal('proofModal')"><div class="absolute inset-0 bg-gray-900 opacity-75"></div></div>
        <div class="bg-white rounded-xl overflow-hidden shadow-xl transform transition-all max-w-lg w-full z-10 p-4">
            <div class="flex justify-between items-center mb-2">
                <h4 class="text-sm font-bold text-gray-900">Berkas Bukti Transfer Pembayaran</h4>
                <button onclick="closeModal('proofModal')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <img id="proofImage" src="" alt="Bukti Transfer" class="w-full h-auto max-h-[70vh] object-contain bg-gray-50 rounded border">
        </div>
    </div>
</div>

<div id="rejectModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 transition-opacity" onclick="closeModal('rejectModal')"><div class="absolute inset-0 bg-gray-500 opacity-75"></div></div>
        <div class="bg-white rounded-xl overflow-hidden shadow-xl transform transition-all max-w-md w-full z-10">
            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Tolak Transaksi Pembayaran</h3>
                    <p class="text-xs text-gray-500 mb-4">Berikan alasan konkret pembatalan untuk invoice: <span id="rejectInvoiceTarget" class="font-mono font-bold text-indigo-600"></span></p>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan Penolakan Berkas</label>
                        <textarea name="rejection_reason" rows="3" required placeholder="Contoh: Bukti buram, nominal transfer kurang, atau salah mengunggah struk atm..." class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-rose-500 focus:ring-1 focus:ring-rose-500"></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse gap-2">
                    <button type="submit" class="w-full sm:w-auto bg-rose-600 hover:bg-rose-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">Tolak Pembayaran</button>
                    <button type="button" onclick="closeModal('rejectModal')" class="mt-3 sm:mt-0 w-full sm:w-auto bg-white border border-gray-300 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openProofModal(imgUrl) {
        document.getElementById('proofImage').src = imgUrl;
        document.getElementById('proofModal').classList.remove('hidden');
    }

    function openRejectModal(paymentId, invoiceNum) {
        document.getElementById('rejectInvoiceTarget').innerText = invoiceNum;
        const form = document.getElementById('rejectForm');
        // PENGUBAHAN: Penyesuaian hardcoded URL untuk rute penolakan pembayaran di JavaScript JavaScript
        form.action = `/payments/${paymentId}/reject`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
@endsection