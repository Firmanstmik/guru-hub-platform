@extends('layout.master-app')
@section('content')
    <div class="p-6 max-w-5xl mx-auto space-y-6">

        <div>
            <h1 class="text-xl font-bold text-gray-900">Riwayat Pendaftaran Kelas</h1>
            <p class="text-xs text-gray-500">Pantau status pembayaran dan akses program kelas premium Anda di sini.</p>
        </div>

        @if ($bookings->isEmpty())
            <div class="bg-white border border-gray-100 rounded-2xl p-12 text-center space-y-3">
                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mx-auto text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <h3 class="text-sm font-bold text-gray-900">Belum Ada Transaksi</h3>
                    <p class="text-xs text-gray-400 max-w-xs mx-auto">Anda belum pernah melakukan pendaftaran atau pemesanan program kelas premium kami.</p>
                </div>
                <div class="pt-2">
                    <a href="/tampil-kursus"
                        class="inline-flex text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-xl shadow-2xs transition">
                        Cari Program Kelas
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($bookings as $booking)
                    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-5 transition hover:border-gray-200">

                        <div class="space-y-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="font-mono text-xs font-bold text-gray-800 bg-gray-100 px-2.5 py-1 rounded-lg border border-gray-200/60 flex items-center gap-1.5">
                                    <span id="inv-{{ $booking->id }}">{{ $booking->transaction_code }}</span>
                                    <button onclick="copyInvoice('inv-{{ $booking->id }}', this)"
                                        class="text-gray-400 hover:text-indigo-600 transition" title="Salin Kode">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                        </svg>
                                    </button>
                                </span>

                                <span class="text-[11px] font-medium text-gray-400">
                                    {{ $booking->created_at->translatedFormat('d M Y, H:i') }} WIB
                                </span>

                                {{-- 🔥 FIX BADGE STATUS: Menggunakan optional() untuk menghindari error trying to get property of non-object --}}
                                @if ($booking->payment && optional($booking->payment)->student_id === Auth::id())
                                    {{-- JIKA SISWA SUDAH UNGGAH BUKTI --}}
                                    @if (strtolower($booking->payment->status) === 'pending')
                                        <span class="text-[10px] font-bold tracking-wide uppercase px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-md animate-pulse">
                                            Menunggu Verifikasi Admin
                                        </span>
                                    @elseif(strtolower($booking->payment->status) === 'approved')
                                        <span class="text-[10px] font-bold tracking-wide uppercase px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md">
                                            Pembayaran Diterima / Aktif
                                        </span>
                                    @elseif(strtolower($booking->payment->status) === 'rejected')
                                        <span class="text-[10px] font-bold tracking-wide uppercase px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-md">
                                            Pembayaran Ditolak
                                        </span>
                                    @else
                                        <span class="text-[10px] font-bold tracking-wide uppercase px-2 py-0.5 bg-gray-100 text-gray-700 border border-gray-200 rounded-md">
                                            {{ strtoupper($booking->payment->status) }}
                                        </span>
                                    @endif
                                @else
                                    {{-- JIKA SISWA BELUM UNGGAH BUKTI (MENGIKUTI TABEL BOOKING) --}}
                                    @if (strtolower($booking->status) === 'pending')
                                        <span class="text-[10px] font-bold tracking-wide uppercase px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-md">
                                            Menunggu Pembayaran
                                        </span>
                                    @elseif(in_array(strtolower($booking->status), ['success', 'completed', 'active']))
                                        <span class="text-[10px] font-bold tracking-wide uppercase px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md">
                                            Selesai / Aktif
                                        </span>
                                    @else
                                        <span class="text-[10px] font-bold tracking-wide uppercase px-2 py-0.5 bg-gray-50 text-gray-500 border border-gray-200 rounded-md">
                                            {{ strtoupper($booking->status) }}
                                        </span>
                                    @endif
                                @endif
                            </div>

                            <div class="space-y-0.5">
                                <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">
                                    {{ $booking->course->category->name ?? 'Program Kelas' }}
                                </span>
                                <h2 class="text-sm font-bold text-gray-900 leading-snug">{{ $booking->course->title }}</h2>
                                <p class="text-xs text-gray-400">Mentor Pengajar: <span class="text-gray-600 font-medium">{{ $booking->course->teacher->name ?? 'Instruktur' }}</span></p>
                            </div>
                        </div>

                        <div class="flex md:flex-col items-between md:items-end justify-between md:justify-center gap-3 pt-3 md:pt-0 border-t md:border-t-0 border-gray-50 shrink-0">
                            <div class="space-y-0.5">
                                <span class="text-[10px] text-gray-400 block md:text-right">Total Pembayaran</span>
                                <span class="text-sm font-extrabold font-mono text-indigo-600">
                                    Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                </span>
                            </div>

                            {{-- ========================================================================= --}}
                            {{-- BLOK TOMBOL AKSI LOGIKA UTAMA --}}
                            {{-- ========================================================================= --}}
                            
                            {{-- KONDISI 1: Pembayaran Sukses / Kelas Selesai (Langsung Masuk Kelas) --}}
                            @if (in_array(strtolower($booking->status), ['success', 'completed']))
                                <a href="/my-courses"
                                    class="inline-flex items-center gap-1 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition">
                                    <span>Masuk Kelas</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>

                            {{-- KONDISI 2: Deteksi Dini Jika Bukti Transfer Ditolak Admin ATAU Status Transaksi Sengaja di-Failed --}}
                            @elseif (optional($booking->payment)->status === 'rejected' || $booking->status === 'failed')
                                <div class="flex flex-col gap-2 items-end">
                                    <div class="flex flex-wrap md:justify-end gap-2">
                                        <a href="/payments-class/{{ $booking->transaction_code }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-2xs transition">
                                            <span>Upload Ulang Bukti</span>
                                        </a>
                                        <a href="https://wa.me/6287728893916?text=Halo%20Admin,%20saya%20ingin%20konfirmasi%20pembayaran%20kelas%20untuk%20invoice:%20{{ $booking->transaction_code }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-xl transition">
                                            <span>Konfirmasi WA</span>
                                        </a>
                                    </div>
                                </div>

                            {{-- KONDISI 3: Jika Booking Masih Pending / Menunggu Tindakan --}}
                            @elseif ($booking->status === 'pending')
                                
                                {{-- Kasus 3.1: Benar-benar BELUM ada record payment (Wajib Upload Pertama Kali) --}}
                                @if (!$booking->payment)
                                    <div class="flex flex-wrap md:justify-end gap-2">
                                        <a href="/payments-class/{{ $booking->transaction_code }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-2xs transition">
                                            <span>Upload Bukti</span>
                                        </a>
                                        <a href="https://wa.me/6287728893916?text=Halo%20Admin,%20saya%20ingin%20konfirmasi%20pembayaran%20kelas%20untuk%20invoice:%20{{ $booking->transaction_code }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-xl transition">
                                            <span>Konfirmasi WA</span>
                                        </a>
                                    </div>

                                {{-- Kasus 3.2: Sudah upload dan status payment sedang ditinjau admin --}}
                                @elseif ($booking->payment->status === 'pending')
                                    <div class="flex flex-wrap md:justify-end gap-2">
                                        <button disabled
                                            class="px-3 py-2 bg-gray-50 border border-gray-200 text-gray-400 text-xs font-semibold rounded-xl cursor-not-allowed">
                                            ⏳ Proses Verifikasi
                                        </button>
                                        <a href="https://wa.me/6287728893916?text=Halo%20Admin,%20saya%20ingin%20konfirmasi%20pembayaran%20kelas%20untuk%20invoice:%20{{ $booking->transaction_code }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-xl transition">
                                            <span>Konfirmasi WA</span>
                                        </a>
                                    </div>

                                {{-- Kasus 3.3: Payment disetujui (Approved) tetapi antrean status booking belum berubah sukses --}}
                                @elseif ($booking->payment->status === 'approved')
                                    <a href="/my-courses"
                                        class="inline-flex items-center gap-1 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition">
                                        <span>Masuk Kelas</span>
                                    </a>
                                @endif

                            {{-- KONDISI 4: Jika Transaksi Kedaluwarsa / Dibatalkan Sistem --}}
                            @elseif (in_array($booking->status, ['expired', 'cancelled']))
                                <span class="text-xs font-medium text-gray-400 italic">Transaksi Selesai/Dibatalkan</span>
                            @endif
                        </div>

                    </div>
                @endforeach

                <div class="pt-2">
                    {{ $bookings->links() }}
                </div>
            </div>
        @endif
    </div>

    <script>
        function copyInvoice(elementId, buttonElement) {
            const codeText = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(codeText).then(() => {
                const originalHTML = buttonElement.innerHTML;
                buttonElement.innerHTML = `<span class="text-[10px] text-emerald-600 font-bold">Tersalin</span>`;
                setTimeout(() => {
                    buttonElement.innerHTML = originalHTML;
                }, 1500);
            });
        }
    </script>
@endsection