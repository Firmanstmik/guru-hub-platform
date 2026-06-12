@extends('layout.master-app')

@section('content')
    <div x-data="{ activeTab: 'semua' }" class="p-4 sm:p-6 max-w-7xl mx-auto space-y-6 sm:space-y-8 animate-fade-in">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl border border-gray-100 shadow-xs">
            <div>
                <h1 class="text-lg sm:text-xl font-black text-gray-900 tracking-tight">
                    Selamat Datang Kembali, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-xs text-gray-400 font-medium mt-0.5">
                    Yuk, lanjutkan progress belajarmu hari ini demi masa depan yang lebih cerah.
                </p>
            </div>
            <div class="w-full sm:w-auto">
                <a href="/tampil-kursus"
                    class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white text-xs font-bold rounded-xl shadow-xs transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Cari Kursus Baru</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] text-gray-400 font-bold block uppercase tracking-wider">Kelas Diikuti</span>
                    <span class="text-xl font-black text-gray-900 leading-tight">
                        {{ $enrolledCoursesCount }} <span class="text-xs font-normal text-gray-400">Kursus</span>
                    </span>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] text-gray-400 font-bold block uppercase tracking-wider">Kelas Selesai</span>
                    <span class="text-xl font-black text-gray-900 leading-tight">
                        {{ $completedCoursesCount }} <span class="text-xs font-normal text-gray-400">Kursus</span>
                    </span>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center space-x-4 sm:col-span-2 lg:col-span-1">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] text-gray-400 font-bold block uppercase tracking-wider">Sertifikat Diraih</span>
                    <span class="text-xl font-black text-gray-900 leading-tight">
                        {{ $certificatesCount }} <span class="text-xs font-normal text-gray-400">E-Cert</span>
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center justify-between gap-4">
                <div class="space-y-0.5">
                    <h4 class="text-gray-900 font-bold text-sm">Bahan Ajar & Materi</h4>
                    <p class="text-xs text-gray-400">Buka dan akses ruang baca materi kelas Anda.</p>
                </div>
                <a href="/my-courses" class="px-3 py-2 bg-indigo-50 text-indigo-600 font-bold text-xs rounded-xl hover:bg-indigo-100 transition whitespace-nowrap shrink-0">
                    Buka Materi 📖
                </a>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center justify-between gap-4">
                <div class="space-y-0.5">
                    <h4 class="text-gray-900 font-bold text-sm">Riwayat & Nilai Kuis</h4>
                    <p class="text-xs text-gray-400">Lihat rekap skor ujian dan koreksi guru.</p>
                </div>
                <a href="{{ url('/quiz/history ') }}" class="px-3 py-2 bg-emerald-50 text-emerald-600 font-bold text-xs rounded-xl hover:bg-emerald-100 transition whitespace-nowrap shrink-0">
                    Lihat Nilai 📊
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-black text-gray-900 uppercase tracking-wide">Lanjutkan Progress Belajar</h2>
                    <a href="/my-courses" class="text-xs font-bold text-indigo-600 hover:underline">Lihat Semua</a>
                </div>

                <div class="space-y-3">
                    @forelse($activeCourses as $course)
                        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs hover:border-gray-200 transition-all duration-200">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-start space-x-4">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-xs shrink-0 uppercase tracking-wider">
                                        {{ $course->short_name }}
                                    </div>
                                    <div>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-orange-50 text-orange-600 mb-1">
                                            {{ $course->category->name ?? 'Materi Inti' }}
                                        </span>
                                        <h3 class="text-sm font-bold text-gray-900 leading-snug">{{ $course->title }}</h3>
                                        <p class="text-[11px] text-gray-400 font-medium mt-0.5">Mentor: {{ $course->mentor->name ?? 'Staf Pengajar' }}</p>
                                    </div>
                                </div>
                                <div class="w-full sm:w-auto sm:text-right flex sm:flex-col justify-between sm:justify-center items-center sm:items-end gap-1.5 border-t sm:border-t-0 pt-3 sm:pt-0 border-gray-50">
                                    <span class="text-xs font-black text-gray-800 font-mono">{{ $course->student_progress }}%</span>
                                    <a href="/room-learn/{{ $course->id }}"
                                        class="px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-xs font-bold rounded-lg transition-all text-center w-auto">
                                        Lanjutkan
                                    </a>
                                </div>
                            </div>
                            <div class="w-full bg-gray-100 h-1.5 rounded-full mt-4 overflow-hidden">
                                <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ $course->student_progress }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-8 rounded-2xl border border-gray-100 text-center text-xs text-gray-400">
                            Belum ada kelas aktif. Silakan pilih kursus baru terlebih dahulu.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="space-y-4">
                <h2 class="text-sm font-black text-gray-900 uppercase tracking-wide">Informasi & Jadwal Kelas</h2>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden">
                    <div class="flex border-b border-gray-100 bg-gray-50/50 p-1">
                        <button @click="activeTab = 'semua'"
                            :class="activeTab === 'semua' ? 'bg-white text-indigo-600 shadow-xs' : 'text-gray-400 hover:text-gray-900'"
                            class="flex-1 text-center py-2 text-xs font-bold rounded-xl transition-all duration-200">
                            Semua Agenda
                        </button>
                        <button @click="activeTab = 'live'"
                            :class="activeTab === 'live' ? 'bg-white text-indigo-600 shadow-xs' : 'text-gray-400 hover:text-gray-900'"
                            class="flex-1 text-center py-2 text-xs font-bold rounded-xl transition-all duration-200">
                            Live Class
                        </button>
                    </div>

                    <div class="p-4 space-y-4">
                        @foreach($liveClasses as $live)
                            <div x-show="activeTab === 'semua' || activeTab === 'live'" class="flex items-start space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center shrink-0 text-xs font-bold">
                                    🔴
                                </div>
                                <div class="flex-1 border-b border-gray-50 pb-3 last:border-b-0 last:pb-0">
                                    <span class="text-[10px] text-red-500 font-bold uppercase tracking-wider block">
                                        📅 {{ \Carbon\Carbon::parse($live->date)->translatedFormat('d M • H:i') }} WIB
                                    </span>
                                    <h4 class="text-xs font-bold text-gray-900 leading-tight mt-0.5">{{ $live->topic }}</h4>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Link: <a href="{{ $live->link }}" target="_blank" class="text-indigo-600 hover:underline">Gabung Meet 🌐</a></p>
                                </div>
                            </div>
                        @endforeach

                        @foreach($pendingQuizzes as $quiz)
                            <div x-show="activeTab === 'semua'" class="flex items-start space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-500 flex items-center justify-center shrink-0 text-xs font-bold">
                                    📝
                                </div>
                                <div class="flex-1 border-b border-gray-50 pb-3 last:border-b-0 last:pb-0">
                                    <span class="text-[10px] text-amber-600 font-bold uppercase tracking-wider block">Evaluasi Belum Selesai</span>
                                    <h4 class="text-xs font-bold text-gray-900 leading-tight mt-0.5">{{ $quiz->title }}</h4>
                                    <p class="text-[10px] text-gray-400 mt-0.5">Durasi: {{ $quiz->duration_minutes }} Menit | <a href="{{ url('/materials/'.$quiz->material_id.'/quiz') }}" class="text-amber-600 font-bold hover:underline">Mulai Tugas ➡️</a></p>
                                </div>
                            </div>
                        @endforeach

                        @if($liveClasses->isEmpty() && $pendingQuizzes->isEmpty())
                            <div class="text-center py-6">
                                <p class="text-xs text-gray-400 font-medium">Tidak ada agenda atau jadwal terdekat.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection