@extends('layout.master-app')
@section('content')
    <div class="p-6 max-w-7xl mx-auto space-y-6">

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center gap-2">
                <span>✅</span> <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-xs">
            <div>
                <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Materi: {{ $quiz->material->title ?? 'N/A' }}</span>
                <h3 class="text-gray-900 text-2xl font-bold mt-1">Daftar Pengumpulan: {{ $quiz->title }}</h3>
                <p class="text-xs text-gray-500 mt-1">Durasi Kuis: <strong>{{ $quiz->duration_minutes }} Menit</strong></p>
            </div>
            <a href="/materials/{{ $quiz->material_id }}" class="text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 px-4 py-2.5 rounded-xl transition">
                ⬅️ Kembali ke Materi
            </a>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-bold text-gray-500 uppercase tracking-wider">
                            <th class="p-4 pl-6">Nama Siswa</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Total Skor Sementara</th>
                            <th class="p-4">Status Koreksi</th>
                            <th class="p-4 pr-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm text-gray-700">
                        @forelse($submissions as $student)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="p-4 pl-6 font-semibold text-gray-900">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center font-bold text-xs text-indigo-600 uppercase">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                        <span>{{ $student->name }}</span>
                                    </div>
                                </td>
                                <td class="p-4 text-gray-500 text-xs">{{ $student->email }}</td>
                                <td class="p-4 font-bold text-base text-gray-800">
                                    {{ $student->total_score }} <span class="text-xs text-gray-400 font-normal">Poin</span>
                                </td>
                                <td class="p-4">
                                    @if($student->need_review)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-amber-50 border border-amber-100 text-amber-800">
                                            ⚠️ Perlu Diperiksa
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-emerald-50 border border-emerald-100 text-emerald-800">
                                            ✅ Selesai Dinilai
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 pr-6 text-right">
                                    <a href="/teacher/quiz/{{ $quiz->id }}/review/{{ $student->id }}" 
                                       class="inline-flex items-center gap-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold px-3 py-2 rounded-xl shadow-xs transition">
                                        <span>Periksa Jawaban</span>
                                        <span>➡️</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-gray-400 italic">
                                    📭 Belum ada siswa yang mengumpulkan jawaban untuk kuis ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection