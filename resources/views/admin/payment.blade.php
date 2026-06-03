@extends('layout.template')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div>
                <h3 class="text-gray-700 text-3xl font-semibold">Verifikasi Pembayaran Manual</h3>
                <p class="text-sm text-gray-500 mt-1">Periksa bukti transfer dan lakukan konfirmasi persetujuan akses kelas
                    siswa.</p>
            </div>

            <div class="w-full lg:w-auto">
                <form action="/admin/payments" method="GET" class="flex flex-col sm:flex-row items-center gap-2 w-full">
                    <div class="relative w-full sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari invoice / nama..."
                            class="w-full bg-white border border-gray-300 rounded-lg pl-9 pr-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            🔍
                        </div>
                    </div>

                    <select name="status" onchange="this.form.submit()"
                        class="w-full sm:w-44 bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>

                    @if (request('search') || request('status'))
                        <a href="/admin/payments"
                            class="w-full sm:w-auto text-center text-xs text-rose-600 font-semibold hover:underline px-2">Reset</a>
                    @endif
                </form>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($payments as $payment)
                <div
                    class="bg-white p-5 rounded-xl border border-gray-100 shadow-xs hover:border-gray-200 transition duration-150">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                        <div class="flex-1 min-w-0 space-y-2">
                            <div class="flex items-start gap-3">
                                <div class="relative group cursor-pointer flex-shrink-0"
                                    onclick="openImageLightbox('{{ asset('storage/' . $payment->payment_proof_path) }}')">
                                    <img src="{{ asset('storage/' . $payment->payment_proof_path) }}" alt="Bukti"
                                        class="w-12 h-12 rounded-lg object-cover border border-gray-200 group-hover:opacity-80 transition">
                                    <div
                                        class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 flex items-center justify-center rounded-lg text-white text-[10px] font-bold transition">
                                        🔎</div>
                                </div>

                                <div class="min-w-0 space-y-0.5">
                                    <span
                                        class="inline-flex text-[10px] font-mono bg-gray-100 text-gray-700 px-2 py-0.5 rounded-md font-bold tracking-wider mb-1">
                                        {{ $payment->invoice_number }}
                                    </span>
                                    <h4 class="text-sm font-bold text-gray-900 truncate">
                                        {{ $payment->student->name ?? 'Siswa Non-Aktif' }}
                                    </h4>
                                    <p class="text-[11px] text-gray-400">
                                        📅 {{ \Carbon\Carbon::parse($payment->created_at)->isoFormat('D MMMM YYYY HH:mm') }} WIB
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex-1 min-w-0 space-y-1">
                            <h5 class="text-sm font-bold text-gray-800 truncate">
                                📖 {{ $payment->course->title ?? 'Kelas Telah Dihapus' }}
                            </h5>
                            <p class="text-sm font-black text-indigo-600">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </p>
                        </div>

                        <div
                            class="flex-1 min-w-[200px] text-xs text-gray-500 leading-relaxed border-t lg:border-t-0 pt-2 lg:pt-0 border-gray-50">
                            @if ($payment->status === 'approved')
                                <span
                                    class="block text-[10px] text-emerald-600 uppercase tracking-wider font-bold">Diverifikasi
                                    Oleh:</span>
                                <span
                                    class="font-semibold text-gray-800 block">{{ $payment->verifier->name ?? 'System' }}</span>
                                <span
                                    class="text-gray-400 text-[11px]">{{ \Carbon\Carbon::parse($payment->verified_at)->isoFormat('D MMMM YYYY') }}</span>
                            @elseif($payment->status === 'rejected')
                                <span class="block text-[10px] text-rose-600 uppercase tracking-wider font-bold">Alasan
                                    Penolakan:</span>
                                <span class="text-rose-700 italic font-medium block truncate max-w-xs"
                                    title="{{ $payment->rejection_reason }}">
                                    "{{ $payment->rejection_reason }}"
                                </span>
                                <span class="text-[11px] text-gray-400">Oleh:
                                    {{ $payment->verifier->name ?? 'Admin' }}</span>
                            @else
                                <span class="text-gray-400 italic flex items-center gap-1">
                                    ⏳ Menunggu pengecekan berkas transfer...
                                </span>
                            @endif
                        </div>

                        <div
                            class="flex flex-wrap sm:flex-nowrap items-center justify-between lg:justify-end gap-3 border-t lg:border-t-0 pt-3 lg:pt-0 border-gray-50 flex-shrink-0">
                            <div class="w-24">
                                @if ($payment->status === 'pending')
                                    <span
                                        class="block w-full text-center px-2.5 py-1 text-xs font-bold bg-amber-50 text-amber-700 rounded-lg border border-amber-100">Pending</span>
                                @elseif($payment->status === 'approved')
                                    <span
                                        class="block w-full text-center px-2.5 py-1 text-xs font-bold bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-100">Approved</span>
                                @else
                                    <span
                                        class="block w-full text-center px-2.5 py-1 text-xs font-bold bg-rose-50 text-rose-700 rounded-lg border border-rose-100">Rejected</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-1.5">
                                @if ($payment->status === 'pending')
                                    <form action="/payments/{{ $payment->id }}/approve" method="POST"
                                        onsubmit="return confirm('Setujui pembayaran invoice ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition shadow-xs">
                                            Terima
                                        </button>
                                    </form>

                                    <button
                                        onclick="openRejectModal({{ $payment->id }}, '{{ $payment->invoice_number }}')"
                                        class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-lg transition">
                                        Tolak
                                    </button>
                                @endif

                                <form action="/admin/payments/{{ $payment->id }}" method="POST"
                                    onsubmit="return confirm('Hapus permanen log transaksi pembayaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-gray-400 hover:text-rose-600 transition hover:bg-gray-50 rounded-lg">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-100 rounded-xl p-12 text-center space-y-3 shadow-xs">
                    <div
                        class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto text-lg">
                        💳
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Tidak Ada Data</h3>
                        <p class="text-xs text-gray-400 max-w-xs mx-auto">Tidak ditemukan berkas konfirmasi pembayaran
                            manual yang sesuai.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($payments->hasPages())
            <div class="mt-4 p-4 bg-white border border-gray-100 rounded-xl shadow-xs">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

    <div id="rejectPaymentModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <form id="rejectForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">Tolak Pembayaran</h3>
                        <p id="rejectModalInvoice" class="text-xs text-indigo-600 font-mono font-bold mb-4"></p>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Alasan Penolakan Bukti
                                    Transfer</label>
                                <textarea name="rejection_reason" required rows="3"
                                    placeholder="Contoh: Gambar bukti transfer buram / nominal tidak sesuai."
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-rose-500 focus:ring-1 focus:ring-rose-500 text-sm p-2.5 border focus:outline-none"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full sm:w-auto bg-rose-600 hover:bg-rose-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition shadow-xs">Kirim
                            Penolakan</button>
                        <button type="button" onclick="closeRejectModal()"
                            class="mt-3 sm:mt-0 w-full sm:w-auto bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="imageLightboxModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4 hidden"
        onclick="closeImageLightbox()">
        <div class="relative max-w-3xl max-h-full">
            <button
                class="absolute -top-10 right-0 text-white text-sm font-bold bg-white/10 px-3 py-1 rounded-lg hover:bg-white/20">Tutup
                (X)</button>
            <img id="lightboxImage" src="" alt="Zoomed Bukti"
                class="max-w-full max-h-[85vh] rounded-lg shadow-2xl border border-white/10 object-contain mx-auto"
                onclick="event.stopPropagation()">
        </div>
    </div>

    <script>
        // Fungsional Operasional Modal Reject
        function openRejectModal(id, invoiceNumber) {
            document.getElementById('rejectModalInvoice').innerText = "Invoice No: " + invoiceNumber;
            const form = document.getElementById('rejectForm');
            form.action = `/payments/${id}/reject`; // URL Handler terhubung ke Controller Anda
            document.getElementById('rejectPaymentModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectPaymentModal').classList.add('hidden');
        }

        // Fungsional Lightbox Klik Gambar Bukti Transfer
        function openImageLightbox(imageSrc) {
            document.getElementById('lightboxImage').src = imageSrc;
            document.getElementById('imageLightboxModal').classList.remove('hidden');
        }

        function closeImageLightbox() {
            document.getElementById('imageLightboxModal').classList.add('hidden');
        }
    </script>
@endsection
