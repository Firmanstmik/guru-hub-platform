@extends('layout.master')

@section('content')
<div class="bg-white min-h-screen">

    <!-- HERO SECTION: Menyapa Pengunjung -->
    <section class="relative bg-gradient-to-b from-indigo-50/70 via-white to-white overflow-hidden py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            <!-- Sisi Kiri: Value Proposition -->
            <div class="space-y-6 text-center lg:text-left">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 animate-pulse">
                    🚀 Raih Masa Depanmu Bersama Kami
                </span>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-gray-900 tracking-tight leading-tight">
                    Hubungkan Bakatmu dengan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-emerald-600">Instruktur Terbaik</span>
                </h1>
                <p class="text-sm sm:text-base text-gray-500 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Guru Hub adalah platform ekosistem pembelajaran modern tempat siswa menemukan kelas unggulan dan para profesional membagikan ilmu terbaiknya. Mulai langkah belajarmu hari ini!
                </p>
                
                <!-- CTA Buttons: Terintegrasi dengan Sistem Registrasi Terpisah -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                    <a href="{{ url('register/student') }}" class="w-full sm:w-auto px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-md shadow-indigo-600/10 transition text-center flex items-center justify-center gap-2 group">
                        <span>Mulai Belajar</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="{{ url('register/teacher') }}" class="w-full sm:w-auto px-6 py-3.5 bg-white border border-gray-200 hover:border-emerald-500 text-gray-700 hover:text-emerald-700 font-bold text-sm rounded-xl shadow-xs transition text-center flex items-center justify-center gap-2">
                        <span>Bergabung Sebagai Guru</span>
                        <span class="text-emerald-600">🤝</span>
                    </a>
                </div>
            </div>

            <!-- Sisi Kanan: Ilustrasi Visual Interaktif -->
            <div class="relative flex justify-center lg:justify-end">
                <div class="absolute -top-12 -left-12 w-64 h-64 bg-emerald-100/40 rounded-full blur-3xl -z-10"></div>
                <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-indigo-100/40 rounded-full blur-3xl -z-10"></div>
                
                <!-- Mockup Card Dashboard Edukasi -->
                <div class="bg-gray-900 text-white p-6 rounded-2xl shadow-2xl border border-gray-800 max-w-md w-full space-y-4 transform hover:scale-[1.02] transition duration-300">
                    <div class="flex items-center justify-between border-b border-gray-800 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                            <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        </div>
                        <span class="text-[10px] font-mono text-gray-500">GuruHub_Live_Stream.mp4</span>
                    </div>
                    <div class="aspect-video bg-gray-800 rounded-xl relative flex items-center justify-center overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-indigo-900/40 to-transparent"></div>
                        <span class="text-xs bg-black/60 px-2 py-1 rounded-md text-emerald-400 font-bold absolute top-3 left-3 flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span> Live Coding
                        </span>
                        <div class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center text-lg shadow-lg cursor-pointer hover:bg-indigo-500 transition">▶</div>
                    </div>
                    <div class="space-y-2">
                        <h4 class="text-sm font-bold text-gray-100">Advanced Laravel & Clean Architecture</h4>
                        <p class="text-xs text-gray-400">Dipandu oleh instruktur berpengalaman berstandar industri.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- METRIC STATS SECTION -->
    <section class="max-w-7xl mx-auto px-6 py-8 border-y border-gray-100 bg-gray-50/50 sm:rounded-2xl">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="space-y-1">
                <span class="block text-2xl sm:text-3xl font-black text-indigo-600 font-mono">15+</span>
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Pilihan Kategori</span>
            </div>
            <div class="space-y-1">
                <span class="block text-2xl sm:text-3xl font-black text-gray-900 font-mono">2,400+</span>
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Siswa Aktif</span>
            </div>
            <div class="space-y-1">
                <span class="block text-2xl sm:text-3xl font-black text-emerald-600 font-mono">180+</span>
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Mitra Guru</span>
            </div>
            <div class="space-y-1">
                <span class="block text-2xl sm:text-3xl font-black text-gray-900 font-mono">98%</span>
                <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Tingkat Kepuasan</span>
            </div>
        </div>
    </section>

    <!-- PORTAL JALUR PERAN (ROLE ADVANTAGE) -->
    <section class="max-w-7xl mx-auto px-6 py-20 space-y-12">
        <div class="text-center max-w-xl mx-auto space-y-2">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">Satu Platform, Dua Solusi Hebat</h2>
            <p class="text-xs sm:text-sm text-gray-400">Pilih peran Anda dan nikmati keunggulan fitur kustomisasi sistem pembelajaran terintegrasi.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Pilihan Jalur Siswa -->
            <div class="bg-white border border-gray-100 p-8 rounded-2xl shadow-xs hover:shadow-md transition duration-200 flex flex-col justify-between group">
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl font-bold">🎓</div>
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition">Untuk Siswa / Pendaftar</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Akses ratusan modul materi bermutu, tonton video pengajaran langsung dari storage atau streaming YouTube, dan bayar pendaftaran dengan sistem verifikasi transaksi manual yang instan.
                    </p>
                </div>
                <div class="pt-6 border-t border-gray-50 mt-6 flex justify-between items-center">
                    <a href="{{ url('register/student') }}" class="text-xs font-bold text-indigo-600 hover:underline">Buat Akun Siswa ➔</a>
                    <span class="text-[10px] uppercase font-mono px-2 py-0.5 bg-gray-100 text-gray-500 rounded-md">Pendaftaran Gratis</span>
                </div>
            </div>

            <!-- Pilihan Jalur Guru -->
            <div class="bg-white border border-gray-100 p-8 rounded-2xl shadow-xs hover:shadow-md transition duration-200 flex flex-col justify-between group">
                <div class="space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">💼</div>
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-emerald-600 transition">Untuk Guru / Instruktur</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Kelola kurikulum pengajaran secara mandiri, unggah materi PDF, serta tautkan video belajar Anda. Pantau perkembangan siswa secara berkala melalui dasbor interaktif.
                    </p>
                </div>
                <div class="pt-6 border-t border-gray-50 mt-6 flex justify-between items-center">
                    <a href="{{ url('register/teacher') }}" class="text-xs font-bold text-emerald-600 hover:underline">Mulai Jadi Mitra ➔</a>
                    <span class="text-[10px] uppercase font-mono px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-md">Verifikasi Berkas</span>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES HIGHLIGHT SECTION -->
    <section class="bg-gray-900 text-white py-20 sm:rounded-t-[2.5rem]">
        <div class="max-w-7xl mx-auto px-6 space-y-16">
            
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-2">
                    <span class="text-xs font-bold uppercase tracking-widest text-indigo-400 font-mono">Kenapa Guru Hub?</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Kelebihan Ekosistem Kelas Kami</h2>
                </div>
                <p class="text-xs sm:text-sm text-gray-400 max-w-md">Kami membangun teknologi manajemen kelas digital demi memberikan efisiensi tinggi bagi pengajar maupun pelajar.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Fitur 1 -->
                <div class="space-y-2 p-4 rounded-xl border border-gray-800 hover:bg-gray-800/40 transition">
                    <div class="text-xl">💳</div>
                    <h4 class="text-base font-bold text-gray-100">Verifikasi Transaksi Aman</h4>
                    <p class="text-xs text-gray-400 leading-relaxed">Sistem pembayaran manual terikat DB Transaction menjamin keandalan data keuangan Anda aman tanpa resiko salah kirim.</p>
                </div>
                <!-- Fitur 2 -->
                <div class="space-y-2 p-4 rounded-xl border border-gray-800 hover:bg-gray-800/40 transition">
                    <div class="text-xl">📺</div>
                    <h4 class="text-base font-bold text-gray-100">Multi-source Video Player</h4>
                    <p class="text-xs text-gray-400 leading-relaxed">Nonton materi lancar baik yang bersumber dari internal penyimpanan Docker/Storage lokal maupun sematan tautan YouTube.</p>
                </div>
                <!-- Fitur 3 -->
                <div class="space-y-2 p-4 rounded-xl border border-gray-800 hover:bg-gray-800/40 transition">
                    <div class="text-xl">📄</div>
                    <h4 class="text-base font-bold text-gray-100">Canvas PDF Terintegrasi</h4>
                    <p class="text-xs text-gray-400 leading-relaxed">Baca rangkuman materi, silabus, dan buku modul pengajaran secara instan langsung di browser Anda tanpa perlu mengunduh berkas.</p>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection