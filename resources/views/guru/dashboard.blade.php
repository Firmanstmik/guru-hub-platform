@extends('layout.master-app')
@section('content')
    <div x-data="{ activeTab: 'semua' }" class="space-y-8 animate-fade-in">

        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div>
                <h1 class="text-xl font-black text-gray-900 tracking-tight">
                    Semangat Mengajar, Mentor {{ Auth::user()->name ?? 'Pengajar' }}! 🚀
                </h1>
                <p class="text-xs text-gray-400 font-medium mt-1">
                    Pantau perkembangan kelas, kelola materi, dan lihat interaksi dari siswa-siswamu hari ini.
                </p>
            </div>
            <div class="flex space-x-2">
                <a href="/kursus/create"
                    class="inline-flex items-center space-x-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Buat Kelas Baru</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
                <div
                    class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] text-gray-400 font-bold block uppercase tracking-wider">Pendapatan Bulan
                        Ini</span>
                    <span class="text-lg font-black text-gray-900 leading-tight">Rp 4.850.000</span>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
                <div
                    class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] text-gray-400 font-bold block uppercase tracking-wider">Siswa Aktif</span>
                    <span class="text-lg font-black text-gray-900 leading-tight">142 <span
                            class="text-xs font-normal text-gray-400">Orang</span></span>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] text-gray-400 font-bold block uppercase tracking-wider">Kursus
                        Dirilis</span>
                    <span class="text-lg font-black text-gray-900 leading-tight">3 <span
                            class="text-xs font-normal text-gray-400">Kelas</span></span>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] text-gray-400 font-bold block uppercase tracking-wider">Rating
                        Penilaian</span>
                    <span class="text-lg font-black text-gray-900 leading-tight">4.9 <span
                            class="text-xs font-normal text-amber-500">★</span></span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <div class="lg:col-span-2 space-y-6">

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-sm font-black text-gray-900 uppercase tracking-wide">Performa Kelas Anda</h2>
                        <a href="/kursus/kelola" class="text-xs font-bold text-indigo-600 hover:underline">Kelola
                            Kelas</a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-orange-50 text-orange-600 mb-2">Backend</span>
                            <h3 class="text-xs font-bold text-gray-900 line-clamp-1">Kuasai Laravel 11 Eloquent & REST
                                API</h3>
                            <div
                                class="mt-4 pt-4 border-t border-gray-50 flex justify-between items-center text-xs font-medium text-gray-400">
                                <span>👥 84 Siswa</span>
                                <span class="text-emerald-600 font-bold">Rp 2.400.000</span>
                            </div>
                        </div>
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-sky-50 text-sky-600 mb-2">Mobile</span>
                            <h3 class="text-xs font-bold text-gray-900 line-clamp-1">Android Advanced dengan Clean
                                Architecture & Kotlin</h3>
                            <div
                                class="mt-4 pt-4 border-t border-gray-50 flex justify-between items-center text-xs font-medium text-gray-400">
                                <span>👥 58 Siswa</span>
                                <span class="text-emerald-600 font-bold">Rp 2.450.000</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <h2 class="text-sm font-black text-gray-900 uppercase tracking-wide">Riwayat Aktivitas Siswa
                        </h2>

                        <div class="flex bg-gray-100 p-0.5 rounded-xl text-[11px] font-bold">
                            <button @click="activeTab = 'semua'"
                                :class="activeTab === 'semua' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-400'"
                                class="px-3 py-1 rounded-lg transition-all">Semua</button>
                            <button @click="activeTab = 'join'"
                                :class="activeTab === 'join' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-400'"
                                class="px-3 py-1 rounded-lg transition-all">Siswa Baru</button>
                            <button @click="activeTab = 'tugas'"
                                :class="activeTab === 'tugas' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-400'"
                                class="px-3 py-1 rounded-lg transition-all">Tugas</button>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden divide-y divide-gray-50">
                        <div x-show="activeTab === 'semua' || activeTab === 'join'"
                            class="p-4 flex items-center justify-between text-xs transition-all">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-[10px]">
                                    AH</div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Ahmad Hermawan</h4>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Membeli Kelas: <span
                                            class="text-gray-600 font-medium">Laravel REST API</span></p>
                                </div>
                            </div>
                            <span class="text-[10px] bg-emerald-50 text-emerald-600 font-bold px-2 py-0.5 rounded-md">Join
                                • Baru Saja</span>
                        </div>

                        <div x-show="activeTab === 'semua' || activeTab === 'tugas'"
                            class="p-4 flex items-center justify-between text-xs transition-all">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-[10px]">
                                    SR</div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Siti Rahmawati</h4>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Mengumpulkan Tugas: <span
                                            class="text-gray-600 font-medium">Modul 3 Eloquent Relationship</span></p>
                                </div>
                            </div>
                            <span class="text-[10px] bg-amber-50 text-amber-700 font-bold px-2 py-0.5 rounded-md">Tugas
                                • 15 Menit Lalu</span>
                        </div>

                        <div x-show="activeTab === 'semua' || activeTab === 'join'"
                            class="p-4 flex items-center justify-between text-xs transition-all">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-[10px]">
                                    BP</div>
                                <div>
                                    <h4 class="font-bold text-gray-900">Bagus Pratama</h4>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Membeli Kelas: <span
                                            class="text-gray-600 font-medium">Android Kotlin Advanced</span></p>
                                </div>
                            </div>
                            <span class="text-[10px] bg-emerald-50 text-emerald-600 font-bold px-2 py-0.5 rounded-md">Join
                                • 2 Jam Lalu</span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="space-y-4">
                <h2 class="text-sm font-black text-gray-900 uppercase tracking-wide">Jadwal Live Mentoring</h2>

                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm space-y-4">
                    <div class="p-4 bg-indigo-50/40 rounded-xl border border-indigo-50 flex items-start space-x-3">
                        <span class="text-base mt-0.5">📹</span>
                        <div>
                            <span
                                class="text-[9px] bg-indigo-600 text-white font-bold px-1.5 py-0.5 rounded-sm uppercase tracking-wider">Hari
                                Ini • 14:00 WITA</span>
                            <h4 class="text-xs font-bold text-gray-900 mt-2">Review Proyek Akhir & Sesi QnA Laravel
                            </h4>
                            <p class="text-[10px] text-gray-400 mt-1">Status: <span class="text-indigo-600 font-bold">Siap
                                    Dimulai</span></p>
                            <a href="https://meet.google.com" target="_blank"
                                class="inline-flex mt-3 text-[10px] font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 rounded-lg shadow-sm transition-all">
                                Masuk Google Meet
                            </a>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-start space-x-3">
                        <span class="text-base mt-0.5">🗓️</span>
                        <div>
                            <span
                                class="text-[9px] bg-gray-400 text-white font-bold px-1.5 py-0.5 rounded-sm uppercase tracking-wider">Kamis,
                                04 Juni</span>
                            <h4 class="text-xs font-bold text-gray-800 mt-2">Pengenalan KSP & Clean Architecture
                                Android</h4>
                            <p class="text-[10px] text-gray-400 mt-1">Kelas: Kotlin Advanced</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-indigo-900 to-indigo-950 p-5 rounded-2xl text-white shadow-md">
                    <span class="text-xs font-bold tracking-widest text-indigo-300 uppercase block">💡 Tips
                        Mentor</span>
                    <p class="text-xs text-indigo-100 font-medium mt-2 leading-relaxed">
                        Siswa yang mendapatkan respon ulasan tugas kurang dari 24 jam memiliki tingkat kelulusan proyek
                        85% lebih tinggi. Yuk, periksa tab tugasmu!
                    </p>
                </div>
            </div>

        </div>
    </div>
@endsection
