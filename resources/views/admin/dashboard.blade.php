@extends('layout.template')
@section('content')
    <div x-data="{ activeTab: 'semua' }" class="space-y-8 animate-fade-in">

        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div>
                <h1 class="text-xl font-black text-gray-900 tracking-tight">
                    Laporan Utama Sistem Admin 🛡️
                </h1>
                <p class="text-xs text-gray-400 font-medium mt-1">
                    Akses penuh kendali sistem. Pantau transaksi keuangan, total pengguna, dan kelayakan konten kursus.
                </p>
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
                    <span class="text-[11px] text-gray-400 font-bold block uppercase tracking-wider">Total Pendapatan
                        Kotor</span>
                    <span class="text-lg font-black text-gray-900 leading-tight">Rp
                        {{ number_format($totalTransactions, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
                <div
                    class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] text-gray-400 font-bold block uppercase tracking-wider">Total Siswa</span>
                    <span
                        class="text-lg font-black text-gray-900 leading-tight">{{ number_format($totalStudents, 0, ',', '.') }}
                        <span class="text-xs font-normal text-gray-400">User</span></span>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] text-gray-400 font-bold block uppercase tracking-wider">Total Mentor</span>
                    <span class="text-lg font-black text-gray-900 leading-tight">{{ $totalTeachers }} <span
                            class="text-xs font-normal text-gray-400">Guru</span></span>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] text-gray-400 font-bold block uppercase tracking-wider">Kursus Aktif</span>
                    <span class="text-lg font-black text-gray-900 leading-tight">{{ $totalCourses }} <span
                            class="text-xs font-normal text-gray-400">Kelas</span></span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-black text-gray-900 uppercase tracking-wide">
                        Log Masuk Dana Transaksi Belajar Terbaru
                    </h2>
                    <a href="/payments" class="text-xs font-bold text-indigo-600 hover:underline">
                        Lihat Semua
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr
                                    class="bg-gray-50 border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                    <th class="p-4">No. Invoice</th>
                                    <th class="p-4">Siswa</th>
                                    <th class="p-4">Kursus</th>
                                    <th class="p-4">Nominal</th>
                                    <th class="p-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 text-gray-600 font-medium">
                                @forelse ($latestPayments as $pay)
                                    <tr class="hover:bg-gray-50/50 transition-all">
                                        <td class="p-4 font-bold text-gray-900">#{{ $pay->invoice_number }}</td>
                                        <td class="p-4">{{ $pay->student->name ?? 'User Terhapus' }}</td>
                                        <td class="p-4 truncate max-w-[150px]">{{ $pay->course->title ?? 'Kelas Terhapus' }}
                                        </td>
                                        <td class="p-4 text-gray-900 font-bold">Rp
                                            {{ number_format($pay->amount, 0, ',', '.') }}</td>
                                        <td class="p-4">
                                            @if ($pay->status === 'approved')
                                                <span
                                                    class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-600">Berhasil</span>
                                            @elseif($pay->status === 'pending')
                                                <span
                                                    class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-600">Pending</span>
                                            @else
                                                <span
                                                    class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-600">Ditolak</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-gray-400 font-medium">Belum ada
                                            riwayat transaksi masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="space-y-4">
                <h2 class="text-sm font-black text-gray-900 uppercase tracking-wide">
                    Verifikasi (Approval)
                </h2>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    {{-- Navigasi Tab Kontrol --}}
                    <div class="flex border-b border-gray-100 bg-gray-50/50 p-1 gap-0.5">
                        <button @click="activeTab = 'semua'"
                            :class="activeTab === 'semua' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-400'"
                            class="flex-1 text-center py-2 text-[11px] font-bold rounded-xl transition-all">
                            Pengajar ({{ $pendingTeachers->count() }})
                        </button>
                        <button @click="activeTab = 'siswa'"
                            :class="activeTab === 'siswa' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-400'"
                            class="flex-1 text-center py-2 text-[11px] font-bold rounded-xl transition-all">
                            Siswa ({{ $pendingStudents->count() }})
                        </button>
                        <button @click="activeTab = 'kelas'"
                            :class="activeTab === 'kelas' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-400'"
                            class="flex-1 text-center py-2 text-[11px] font-bold rounded-xl transition-all">
                            Kelas ({{ $pendingCourses->count() }})
                        </button>
                    </div>

                    <div class="p-4 space-y-4">

                        {{-- TAB: PENGAJAR PENDING --}}
                        <div x-show="activeTab === 'semua'" class="space-y-3">
                            @forelse($pendingTeachers as $teacher)
                                <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 space-y-3 transition-all">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold text-xs flex items-center justify-center">
                                            {{ strtoupper(substr($teacher->teacher_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-gray-900">{{ $teacher->teacher_name }}</h4>
                                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">
                                                Keahlian: {{ $teacher->skills_tags ?? 'Tidak spesifik' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex space-x-2 pt-1">
                                        <a href="/teachers" class="w-full">
                                            <button type="button"
                                                class="w-full py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-[10px] rounded-lg transition-all">
                                                Tinjau Berkas Pengajar
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-xs text-gray-400 font-medium">
                                    Tidak ada pengajar baru yang menunggu persetujuan.
                                </div>
                            @endforelse
                        </div>

                        {{-- TAB BARU: SISWA PENDING --}}
                        <div x-show="activeTab === 'siswa'" class="space-y-3" x-cloak>
                            @forelse($pendingStudents as $student)
                                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 space-y-3 transition-all">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-emerald-600 text-white font-bold text-xs flex items-center justify-center">
                                            {{ strtoupper(substr($student->student_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-gray-900">{{ $student->student_name }}</h4>
                                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">
                                                Instansi: {{ $student->institution_name ?? '-' }} @if ($student->nisn)
                                                    (NISN: {{ $student->nisn }})
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex space-x-2 pt-1">
                                        <a href="/student-biodata" class="w-full">
                                            <button type="button"
                                                class="w-full py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] rounded-lg transition-all">
                                                Validasi Biodata Siswa
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-xs text-gray-400 font-medium">
                                    Tidak ada biodata siswa yang menunggu verifikasi.
                                </div>
                            @endforelse
                        </div>

                        {{-- TAB: KELAS PENDING --}}
                        <div x-show="activeTab === 'kelas'" class="space-y-3" x-cloak>
                            @forelse($pendingCourses as $course)
                                <div
                                    class="p-3 bg-amber-50/40 rounded-xl border border-amber-100 space-y-3 transition-all">
                                    <div>
                                        <span
                                            class="text-[9px] font-bold text-amber-700 bg-amber-100/60 px-1.5 py-0.5 rounded uppercase tracking-wider">
                                            Menunggu Review Silabus
                                        </span>
                                        <h4 class="text-xs font-black text-gray-900 mt-2">{{ $course->title }}</h4>
                                        <p class="text-[10px] text-gray-400 mt-0.5">Diajukan oleh:
                                            {{ $course->teacher->name ?? 'Guru' }}</p>
                                    </div>

                                    <form action="/admin/courses/{{ $course->id }}/publish" method="POST"
                                        class="pt-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="w-full py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-[10px] rounded-lg transition-all">
                                            Publikasikan Kelas
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="text-center py-6 text-xs text-gray-400 font-medium">
                                    Tidak ada pengajuan kelas baru saat ini.
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
