@extends('layout.master-app')
@section('content')
    {{-- @dd($bookings) --}}
    <div class="p-6 max-w-5xl mx-auto space-y-6">

        <div>
            <h1 class="text-xl font-bold text-gray-900">Riwayat Pendaftaran Kelas</h1>
            <p class="text-xs text-gray-500">Pantau status pembayaran dan akses program kelas premium Anda di sini.</p>
        </div>

        @if (session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-xs font-semibold text-emerald-800 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

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
                    <p class="text-xs text-gray-400 max-w-xs mx-auto">Anda belum pernah melakukan pendaftaran atau pemesanan
                        program kelas premium kami.</p>
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
                    <div
                        class="bg-white border border-gray-100 rounded-2xl p-5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-5 transition hover:border-gray-200">

                        <div class="space-y-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <span
                                    class="font-mono text-xs font-bold text-gray-800 bg-gray-100 px-2.5 py-1 rounded-lg border border-gray-200/60 flex items-center gap-1.5">
                                    <span id="inv-{{ $booking->id }}">{{ $booking->transaction_code }}</span>
                                    <button onclick="copyInvoice('inv-{{ $booking->id }}', this)"
                                        class="text-gray-400 hover:text-indigo-600 transition" title="Salin Kode">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                        </svg>
                                    </button>
                                </span>

                                <span class="text-[11px] font-medium text-gray-400">
                                    {{ $booking->created_at->translatedFormat('d M Y, H:i') }} WIB
                                </span>

                                @if ($booking->payment && !empty($booking->payment->status))
                                    {{-- JIKA SISWA SUDAH UNGGAH BUKTI (MENGIKUTI TABEL PAYMENT) --}}
                                    @if (strtolower($booking->payment->status) === 'pending')
                                        <span
                                            class="text-[10px] font-bold tracking-wide uppercase px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-md animate-pulse">
                                            Menunggu Verifikasi Admin
                                        </span>
                                    @elseif(strtolower($booking->payment->status) === 'approved')
                                        <span
                                            class="text-[10px] font-bold tracking-wide uppercase px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md">
                                            Pembayaran Diterima / Aktif
                                        </span>
                                    @elseif(strtolower($booking->payment->status) === 'rejected')
                                        <span
                                            class="text-[10px] font-bold tracking-wide uppercase px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-md">
                                            Pembayaran Ditolak
                                        </span>
                                    @else
                                        <span
                                            class="text-[10px] font-bold tracking-wide uppercase px-2 py-0.5 bg-gray-100 text-gray-700 border border-gray-200 rounded-md">
                                            {{ strtoupper($booking->payment->status) }}
                                        </span>
                                    @endif
                                @else
                                    {{-- JIKA SISWA BELUM UNGGAH BUKTI (MENGIKUTI TABEL BOOKING) --}}
                                    @if (strtolower($booking->status) === 'pending')
                                        <span
                                            class="text-[10px] font-bold tracking-wide uppercase px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-md">
                                            Menunggu Pembayaran
                                        </span>
                                    @elseif(in_array(strtolower($booking->status), ['success', 'completed', 'active']))
                                        <span
                                            class="text-[10px] font-bold tracking-wide uppercase px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-md">
                                            Selesai / Aktif
                                        </span>
                                    @else
                                        <span
                                            class="text-[10px] font-bold tracking-wide uppercase px-2 py-0.5 bg-gray-50 text-gray-500 border border-gray-200 rounded-md">
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
                                <p class="text-xs text-gray-400">Mentor Pengajar: <span
                                        class="text-gray-600 font-medium">{{ $booking->course->teacher->name ?? 'Instruktur' }}</span>
                                </p>
                            </div>
                        </div>

                        <div
                            class="flex md:flex-col items-between md:items-end justify-between md:justify-center gap-3 pt-3 md:pt-0 border-t md:border-t-0 border-gray-50 shrink-0">
                            <div class="space-y-0.5">
                                <span class="text-[10px] text-gray-400 block md:text-right">Total Pembayaran</span>
                                <span class="text-sm font-extrabold font-mono text-indigo-600">
                                    Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                </span>
                            </div>

                            <div>
                                {{-- KONDISI 1: Belum ada record payment & booking masih pending (Wajib Upload) --}}
                                @if (!$booking->payment && strtolower($booking->status) === 'pending')
                                    <div class="flex flex-wrap md:justify-end gap-2">
                                        <a href="/payments-class/{{ $booking->transaction_code }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-2xs transition">
                                            <span>Upload Bukti</span>
                                        </a>

                                        <a href="https://wa.me/6287728893916?text=Halo%20Admin,%20saya%20ingin%20konfirmasi%20pembayaran%20kelas%20untuk%20invoice:%20{{ $booking->transaction_code }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-xl transition">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.455L0 24zm6.59-4.846c1.66.986 3.296 1.48 4.905 1.481 5.482 0 9.94-4.461 9.943-9.94 0-2.654-1.033-5.15-2.908-7.028-1.876-1.877-4.374-2.909-7.03-2.91-5.485 0-9.942 4.46-9.944 9.941-.001 1.764.484 3.42 1.4 4.898L1.15 22.882l4.497-1.179z" />
                                            </svg>
                                            Konfirmasi WA
                                        </a>
                                    </div>

                                    {{-- KONDISI 2: Sudah upload tapi status payment ditolak oleh admin (Bisa upload ulang) --}}
                                @elseif($booking->payment && strtolower($booking->payment->status) === 'rejected')
                                    <a href="/payments-class/{{ $booking->transaction_code }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-2xs transition">
                                        <span>Upload Ulang Bukti</span>
                                    </a>

                                    {{-- KONDISI 3: Pembayaran approved ATAU status booking telah sukses/aktif --}}
                                @elseif(
                                    ($booking->payment && strtolower($booking->payment->status) === 'approved') ||
                                        in_array(strtolower($booking->status), ['success', 'completed', 'active']))
                                    <a href="/my-courses"
                                        class="inline-flex items-center gap-1 px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition">
                                        <span>Masuk Kelas</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>

                                    {{-- KONDISI 4: Fallback Utama - Sudah upload bukti dan status payment bernilai 'pending' --}}
                                @else
                                    <button disabled
                                        class="px-3 py-2 bg-gray-50 border border-gray-200 text-gray-400 text-xs font-semibold rounded-xl cursor-not-allowed">
                                        ⏳ Menunggu Validasi
                                    </button>

                                    <a href="https://wa.me/6287728893916?text=Halo%20Admin,%20saya%20ingin%20konfirmasi%20pembayaran%20kelas%20untuk%20invoice:%20{{ $booking->transaction_code }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-xl transition">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.455L0 24zm6.59-4.846c1.66.986 3.296 1.48 4.905 1.481 5.482 0 9.94-4.461 9.943-9.94 0-2.654-1.033-5.15-2.908-7.028-1.876-1.877-4.374-2.909-7.03-2.91-5.485 0-9.942 4.46-9.944 9.941-.001 1.764.484 3.42 1.4 4.898L1.15 22.882l4.497-1.179z" />
                                        </svg>
                                        Konfirmasi WA
                                    </a>
                                @endif
                            </div>
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
