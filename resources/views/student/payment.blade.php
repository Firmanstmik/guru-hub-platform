@extends('layout.master-app')
@section('content')
    <div class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner space-y-4">
            <div class="gh-app-card border-amber-200 bg-amber-50/80 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="flex gap-3 items-center">
                <div class="p-3 bg-amber-500 text-white rounded-xl shrink-0 animate-pulse">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-base font-bold text-amber-900">Menunggu Pembayaran</h1>
                    <p class="text-xs text-amber-700">Segera selesaikan pembayaran Anda agar slot kelas segera aktif.</p>
                </div>
            </div>
            <div
                class="text-xs font-mono bg-white px-3 py-1.5 rounded-lg border border-amber-200 text-amber-800 self-stretch md:self-auto text-center shadow-2xs">
                ID Invoice: <span class="font-bold">{{ $booking->transaction_code }}</span>
            </div>
        </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-5">
            <div class="md:col-span-3 space-y-4">
                <div class="gh-app-card space-y-3">
                    <h2 class="text-sm font-bold text-gray-900 tracking-wide uppercase border-b border-gray-50 pb-3">Metode
                        Transfer Bank Manual</h2>

                    <p class="text-xs text-gray-500 leading-relaxed">Silakan transfer nominal tagihan Anda ke salah satu
                        rekening resmi pengelola di bawah ini:</p>
                    @foreach ($banks as $bank)
                        <div class="gh-app-card flex items-center justify-between gap-4">
                            <div class="space-y-1">
                                <span class="text-xs font-bold text-blue-800 tracking-wider">{{ $bank->bank_name }}</span>
                                <p class="text-sm font-mono font-bold text-gray-800 tracking-wide">
                                    {{ $bank->account_number }}
                                </p>
                                <p class="text-[11px] text-gray-600">Atas Nama:
                                    <span class="font-semibold text-gray-600">{{ $bank->account_name }}</span>
                                </p>
                            </div>
                            <button type="button" onclick="copyToClipboardValue('{{ $bank->account_number }}', this)"
                                class="px-3 py-1.5 bg-white border border-gray-200 hover:bg-gray-100 rounded-lg text-xs font-semibold text-gray-600 shadow-2xs transition flex items-center gap-1">
                                Salin
                            </button>
                        </div>
                    @endforeach

                    <div class="space-y-2 pt-2">
                        <h3 class="text-xs font-bold text-gray-700">Langkah Penyelesaian:</h3>
                        <ol class="text-xs text-gray-500 list-decimal list-inside space-y-1.5 pl-1 leading-relaxed">
                            <li>Gunakan Mobile Banking, ATM, atau Internet Banking pilihan Anda.</li>
                            <li>Masukkan nomor rekening tujuan di atas dengan nominal transfer <strong
                                    class="text-gray-900">harus persis sama</strong> hingga digit terakhir.</li>
                            <li>Simpan resi / struk bukti transfer setelah transaksi berhasil.</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 space-y-4">
                <div class="gh-app-card space-y-4">
                    <h2 class="text-sm font-bold text-gray-900 border-b border-gray-50 pb-3">Rincian Pembelian</h2>

                    <div class="space-y-1">
                        <span
                            class="text-[10px] uppercase font-bold text-indigo-600 tracking-wider bg-indigo-50 px-2 py-0.5 rounded-sm">
                            {{ $booking->course->category->name ?? 'Program Kelas' }}
                        </span>
                        <h3 class="text-sm font-bold text-gray-800 leading-snug mt-1">{{ $booking->course->title }}</h3>
                        <p class="text-xs text-gray-400">Mentor: {{ $booking->course->teacher->name ?? 'Instruktur' }}</p>
                    </div>

                    <hr class="border-gray-100">

                    <div class="gh-app-card p-4 text-white" style="background: linear-gradient(135deg, #3b82f6, #14b8a6); border: none;">
                        <span class="text-[10px] text-indigo-200 font-semibold uppercase tracking-wider">Total yang Harus
                            Dibayar</span>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-extrabold font-mono" id="total-tagihan">Rp
                                {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                            <button onclick="copyToClipboardValue('{{ $booking->total_amount }}', this)"
                                class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm">
                                Salin Angka
                            </button>
                        </div>
                    </div>

                    <div class="pt-2 space-y-2">
                        <a href="https://wa.me/628123456789?text=Halo%20Admin,%20saya%20ingin%20konfirmasi%20pembayaran%20dengan%20Kode%20Invoice:%20{{ $booking->transaction_code }}"
                            target="_blank"
                            class="gh-app-btn gh-app-btn-primary gh-app-btn-block">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.513 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.455L0 24zm6.59-4.846c1.66.986 3.296 1.48 4.905 1.481 5.482 0 9.94-4.461 9.943-9.94 0-2.654-1.033-5.15-2.908-7.028-1.876-1.877-4.374-2.909-7.03-2.91-5.485 0-9.942 4.46-9.944 9.941-.001 1.764.484 3.42 1.4 4.898L1.15 22.882l4.497-1.179z" />
                            </svg>
                            Konfirmasi via WhatsApp
                        </a>

                        <a href="/student/catalog"
                            class="gh-app-btn gh-app-btn-secondary gh-app-btn-block text-center">
                            Kembali ke Katalog
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="gh-app-card">
            <h2 class="text-sm font-bold text-gray-900 tracking-wide uppercase border-b border-gray-50 pb-3 mb-4">Formulir
                Upload Bukti Transfer</h2>

            <form action="/payments-class/{{ $booking->transaction_code }}" method="POST" enctype="multipart/form-data"
                class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Kelas Yang
                            Diikuti</label>
                        <div
                            class="w-full gh-app-input bg-gray-50">
                            <span>📖</span> <span class="truncate">{{ $booking->course->title }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Total Tagihan
                            Pembayaran</label>
                        <div
                            class="w-full gh-app-input font-mono font-bold text-[#0E7490]">
                            Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Unggah Gambar
                        Bukti Struk</label>
                    <div
                        class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-indigo-500 transition relative bg-gray-50/50 group">
                        <input type="file" name="payment_proof_path" id="proof_input" accept="image/*" required
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                            onchange="previewImage(this)">

                        <div id="upload_placeholder" class="space-y-2 py-4">
                            <div class="text-3xl group-hover:scale-110 transition duration-150">📸</div>
                            <p class="text-xs font-semibold text-gray-700">Klik atau seret file gambar struk ke sini</p>
                            <p class="text-[10px] text-gray-400">Mendukung file JPEG, JPG, atau PNG (Maksimal 2MB)</p>
                        </div>

                        <div id="preview_container" class="hidden space-y-2">
                            <img id="image_preview"
                                class="max-h-48 mx-auto rounded-lg shadow-md border border-gray-200 object-contain">
                            <p class="text-[11px] text-indigo-600 font-medium">Klik atau seret kembali untuk mengganti
                                gambar</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2 border-t border-gray-50">
                    <button type="submit"
                        class="gh-app-btn gh-app-btn-primary gh-app-btn-block">
                        Kirim Konfirmasi Pembayaran
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>

    <script>
        // Fungsionalitas Salin Clipboard
        function copyToClipboardValue(rawValue, buttonElement) {
            // Menyalin string langsung tanpa perlu mencari ID elemen di HTML
            navigator.clipboard.writeText(rawValue).then(() => {
                const originalText = buttonElement.innerText;

                // Berikan feedback visual sukses (warna hijau)
                buttonElement.innerText = 'Tersalin!';
                buttonElement.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
                buttonElement.classList.remove('bg-white', 'text-gray-600');

                // Kembalikan ke tombol semula setelah 2 detik
                setTimeout(() => {
                    buttonElement.innerText = originalText;
                    buttonElement.classList.remove('bg-emerald-50', 'text-emerald-700',
                        'border-emerald-200');
                    buttonElement.classList.add('bg-white', 'text-gray-600');
                }, 2000);
            }).catch(err => {
                console.error('Gagal menyalin teks: ', err);
            });
        }

        // Fungsionalitas Live Preview Gambar Struk
        function previewImage(input) {
            const preview = document.getElementById('image_preview');
            const container = document.getElementById('preview_container');
            const placeholder = document.getElementById('upload_placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = "";
                container.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        }
    </script>
@endsection
