@extends('layout.master-app')
@section('content')
    <div x-data="{ activeTab: 'semua' }" class="space-y-8 animate-fade-in">

        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div>
                <h1 class="text-xl font-black text-gray-900 tracking-tight">
                    Selamat Datang Kembali, {{ Auth::user()->name ?? 'Siswa' }}! 👋
                </h1>
                <p class="text-xs text-gray-400 font-medium mt-1">
                    Yuk, lanjutkan progress belajarmu hari ini demi masa depan yang lebih cerah.
                </p>
            </div>
            <div>
                <a href="/tampil-kursus"
                    class="inline-flex items-center space-x-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Cari Kursus Baru</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
                <div
                    class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] text-gray-400 font-bold block uppercase tracking-wider">Kelas
                        Diikuti</span>
                    <span class="text-xl font-black text-gray-900 leading-tight">4 <span
                            class="text-xs font-normal text-gray-400">Kursus</span></span>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
                <div
                    class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] text-gray-400 font-bold block uppercase tracking-wider">Kelas
                        Selesai</span>
                    <span class="text-xl font-black text-gray-900 leading-tight">2 <span
                            class="text-xs font-normal text-gray-400">Kursus</span></span>
                </div>
            </div>

            <div
                class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4 sm:col-span-2 lg:col-span-1">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] text-gray-400 font-bold block uppercase tracking-wider">Sertifikat
                        Diraih</span>
                    <span class="text-xl font-black text-gray-900 leading-tight">2 <span
                            class="text-xs font-normal text-gray-400">E-Cert</span></span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-black text-gray-900 uppercase tracking-wide">
                        Lanjutkan Progress Belajar
                    </h2>
                    <a href="/my-courses" class="text-xs font-bold text-indigo-600 hover:underline">
                        Lihat Semua
                    </a>
                </div>

                <div class="space-y-3">
                    <div
                        class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:border-gray-200 transition-all">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start space-x-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-xs flex-shrink-0">
                                    LRV
                                </div>
                                <div>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-orange-50 text-orange-600 mb-1">
                                        Backend Development
                                    </span>
                                    <h3 class="text-sm font-bold text-gray-900">Kuasai Laravel 11 Eloquent & REST API
                                    </h3>
                                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">Mentor: Danu Wijaya</p>
                                </div>
                            </div>
                            <div
                                class="w-full sm:w-auto sm:text-right flex sm:flex-col justify-between sm:justify-center items-center sm:items-end gap-2 border-t sm:border-t-0 pt-3 sm:pt-0 border-gray-50">
                                <span class="text-xs font-black text-gray-800">75%</span>
                                <a href="/room-learn"
                                    class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-xs font-bold rounded-lg transition-all">
                                    Lanjutkan
                                </a>
                            </div>
                        </div>
                        <div class="w-full bg-gray-100 h-1.5 rounded-full mt-4 overflow-hidden">
                            <div class="bg-indigo-600 h-1.5 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>

                    <div
                        class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:border-gray-200 transition-all">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start space-x-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center font-black text-xs flex-shrink-0">
                                    KTL
                                </div>
                                <div>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-sky-50 text-sky-600 mb-1">
                                        Mobile Programming
                                    </span>
                                    <h3 class="text-sm font-bold text-gray-900">Android Advanced dengan Clean
                                        Architecture & Kotlin</h3>
                                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">Mentor: Rian Fitriadi</p>
                                </div>
                            </div>
                            <div
                                class="w-full sm:w-auto sm:text-right flex sm:flex-col justify-between sm:justify-center items-center sm:items-end gap-2 border-t sm:border-t-0 pt-3 sm:pt-0 border-gray-50">
                                <span class="text-xs font-black text-gray-800">40%</span>
                                <a href="/room-learn"
                                    class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-xs font-bold rounded-lg transition-all">
                                    Lanjutkan
                                </a>
                            </div>
                        </div>
                        <div class="w-full bg-gray-100 h-1.5 rounded-full mt-4 overflow-hidden">
                            <div class="bg-sky-500 h-1.5 rounded-full" style="width: 40%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <h2 class="text-sm font-black text-gray-900 uppercase tracking-wide">
                    Informasi & Jadwal Kelas
                </h2>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="flex border-b border-gray-100 bg-gray-50/50 p-1">
                        <button @click="activeTab = 'semua'"
                            :class="activeTab === 'semua' ? 'bg-white text-indigo-600 shadow-sm' :
                                'text-gray-400 hover:text-gray-900'"
                            class="flex-1 text-center py-2 text-xs font-bold rounded-xl transition-all">
                            Semua
                        </button>
                        <button @click="activeTab = 'live'"
                            :class="activeTab === 'live' ? 'bg-white text-indigo-600 shadow-sm' :
                                'text-gray-400 hover:text-gray-900'"
                            class="flex-1 text-center py-2 text-xs font-bold rounded-xl transition-all">
                            Live Meeting
                        </button>
                    </div>

                    <div class="p-4 space-y-4">
                        <div x-show="activeTab === 'semua' || activeTab === 'live'" class="flex items-start space-x-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0 text-xs font-bold">
                                🔴
                            </div>
                            <div class="flex-1 border-b border-gray-50 pb-3">
                                <span class="text-[10px] text-red-500 font-bold uppercase tracking-wider block">Hari
                                    Ini • 14:00 WITA</span>
                                <h4 class="text-xs font-bold text-gray-900">Live Mentoring: Review Proyek Akhir Laravel
                                </h4>
                                <p class="text-[10px] text-gray-400 mt-0.5">Via Google Meet</p>
                            </div>
                        </div>

                        <div x-show="activeTab === 'semua'" class="flex items-start space-x-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center flex-shrink-0 text-xs font-bold">
                                📝
                            </div>
                            <div class="flex-1 border-b border-gray-50 pb-3">
                                <span class="text-[10px] text-amber-600 font-bold uppercase tracking-wider block">Besok
                                    • 23:59 WITA</span>
                                <h4 class="text-xs font-bold text-gray-900">Batas Pengumpulan Tugas REST API</h4>
                                <p class="text-[10px] text-gray-400 mt-0.5">Modul 4: Eloquent Relationship</p>
                            </div>
                        </div>

                        <div x-show="activeTab === 'semua'" class="flex items-start space-x-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center flex-shrink-0 text-xs font-bold">
                                📢
                            </div>
                            <div class="flex-1">
                                <span class="text-[10px] text-indigo-600 font-bold uppercase tracking-wider block">3
                                    Hari Lalu</span>
                                <h4 class="text-xs font-bold text-gray-900">Pembaruan Silabus Android v4</h4>
                                <p class="text-[10px] text-gray-400 mt-0.5">Modul Kapt migrasi penuh ke KSP.</p>
                            </div>
                        </div>

                        <div x-show="activeTab === 'live' && false" class="text-center py-6">
                            <p class="text-xs text-gray-400 font-medium">Tidak ada jadwal meet lainnya.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
