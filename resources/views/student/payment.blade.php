@extends('layout.master-app')
@section('content')
    <div class="p-6 max-w-4xl mx-auto space-y-6">

        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex gap-3 items-center">
                <div class="p-3 bg-amber-500 text-white rounded-xl shrink-0 animate-pulse">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-base font-bold text-amber-900">Menunggu Pembayaran</h1>
                    <p class="text-xs text-amber-700">Segera selesaikan pembayaran Anda agar slot kelas segera aktif.</p>
                </div>
            </div>
            <div class="text-xs font-mono bg-white px-3 py-1.5 rounded-lg border border-amber-200 text-amber-800 self-stretch md:self-auto text-center shadow-2xs">
                ID Invoice: <span class="font-bold">{{ $booking->transaction_code }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <div class="md:col-span-3 space-y-6">
                <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-2xs space-y-5">
                    <h2 class="text-sm font-bold text-gray-900 tracking-wide uppercase border-b border-gray-50 pb-3">Metode Transfer Bank Manual</h2>
                    
                    <p class="text-xs text-gray-500 leading-relaxed">Silakan transfer nominal tagihan Anda ke salah satu rekening resmi pengelola di bawah ini:</p>

                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between gap-4">
                        <div class="space-y-1">
                            <span class="text-xs font-bold text-blue-800 tracking-wider">BANK MANDIRI</span>
                            <p class="text-sm font-mono font-bold text-gray-800 tracking-wide" id="norek-mandiri">1440022334455</p>
                            <p class="text-[11px] text-gray-400">Atas Nama: <span class="font-semibold text-gray-600">Guru Hub Media</span></p>
                        </div>
                        <button onclick="copyToClipboard('norek-mandiri', this)" class="px-3 py-1.5 bg-white border border-gray-200 hover:bg-gray-100 rounded-lg text-xs font-semibold text-gray-600 shadow-2xs transition flex items-center gap-1">
                            Salin
                        </button>
                    </div>

                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl flex items-center justify-between gap-4">
                        <div class="space-y-1">
                            <span class="text-xs font-bold text-blue-600 tracking-wider">BANK BCA</span>
                            <p class="text-sm font-mono font-bold text-gray-800 tracking-wide" id="norek-bca">0561122334</p>
                            <p class="text-[11px] text-gray-400">Atas Nama: <span class="font-semibold text-gray-600">Guru Hub Media</span></p>
                        </div>
                        <button onclick="copyToClipboard('norek-bca', this)" class="px-3 py-1.5 bg-white border border-gray-200 hover:bg-gray-100 rounded-lg text-xs font-semibold text-gray-600 shadow-2xs transition flex items-center gap-1">
                            Salin
                        </button>
                    </div>

                    <div class="space-y-2 pt-2">
                        <h3 class="text-xs font-bold text-gray-700">Langkah Penyelesaian:</h3>
                        <ol class="text-xs text-gray-500 list-decimal list-inside space-y-1.5 pl-1 leading-relaxed">
                            <li>Gunakan Mobile Banking, ATM, atau Internet Banking pilihan Anda.</li>
                            <li>Masukkan nomor rekening tujuan di atas dengan nominal transfer <strong class="text-gray-900">harus persis sama</strong> hingga digit terakhir.</li>
                            <li>Simpan resi / struk bukti transfer setelah transaksi berhasil.</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 space-y-4">
                <div class="bg-white border border-gray-100 p-5 rounded-2xl shadow-2xs space-y-4">
                    <h2 class="text-sm font-bold text-gray-900 border-b border-gray-50 pb-3">Rincian Pembelian</h2>
                    
                    <div class="space-y-1">
                        <span class="text-[10px] uppercase font-bold text-indigo-600 tracking-wider bg-indigo-50 px-2 py-0.5 rounded-sm">
                            {{ $booking->course->category->name ?? 'Program Kelas' }}
                        </span>
                        <h3 class="text-sm font-bold text-gray-800 leading-snug mt-1">{{ $booking->course->title }}</h3>
                        <p class="text-xs text-gray-400">Mentor: {{ $booking->course->teacher->name ?? 'Instruktur' }}</p>
                    </div>

                    <hr class="border-gray-100">

                    <div class="bg-indigo-600 text-white p-4 rounded-xl space-y-1 shadow-sm">
                        <span class="text-[10px] text-indigo-200 font-semibold uppercase tracking-wider">Total yang Harus Dibayar</span>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-extrabold font-mono" id="total-tagihan">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                            <button onclick="copyToClipboardValue('{{ $booking->total_amount }}', this)" class="px-2 py-1 bg-indigo-500/50 hover:bg-indigo-500 border border-indigo-400/30 text-[11px] font-semibold rounded-md transition">
                                Salin Angka
                            </button>
                        </div>
                    </div>

                    <div class="pt-2 space-y-2">
                        <a href="https://wa.me/628123456789?text=Halo%20Admin,%20saya%20ingin%20konfirmasi%20pembayaran%20dengan%20Kode%20Invoice:%20{{ $booking->transaction_code }}" 
                           target="_blank"
                           class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2.5 px-4 rounded-xl transition duration-150 flex items-center justify-center gap-2 shadow-xs">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.455L0 24zm6.59-4.846c1.66.986 3.296 1.48 4.905 1.481 5.482 0 9.94-4.461 9.943-9.94 0-2.654-1.033-5.15-2.908-7.028-1.876-1.877-4.374-2.909-7.03-2.91-5.485 0-9.942 4.46-9.944 9.941-.001 1.764.484 3.42 1.4 4.898L1.15 22.882l4.497-1.179z"/>
                            </svg>
                            Konfirmasi via WhatsApp
                        </a>

                        <a href="/student/catalog" class="w-full bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-xs font-semibold py-2.5 px-4 rounded-xl transition duration-150 block text-center shadow-2xs">
                            Kembali ke Katalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 1. Salin Berdasarkan ID Elemen HTML
        function copyToClipboard(elementId, buttonElement) {
            const textToCopy = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(textToCopy).then(() => {
                const originalText = buttonElement.innerText;
                buttonElement.innerText = 'Tersalin!';
                buttonElement.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
                
                setTimeout(() => {
                    buttonElement.innerText = originalText;
                    buttonElement.classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
                }, 2000);
            });
        }

        // 2. Salin Nilai Angka Murni (Khusus Nominal Angka tanpa 'Rp')
        function copyToClipboardValue(rawValue, buttonElement) {
            navigator.clipboard.writeText(rawValue).then(() => {
                const originalText = buttonElement.innerText;
                buttonElement.innerText = 'Tersalin!';
                
                setTimeout(() => {
                    buttonElement.innerText = originalText;
                }, 2000);
            });
        }
    </script>
@endsection