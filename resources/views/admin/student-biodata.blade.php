@extends('layout.template')

@section('title', 'Manajemen Biodata Siswa')
@section('header', 'Daftar Biodata Siswa')

@section('content')
<div class="max-w-7xl mx-auto py-2 px-1 space-y-6">
    {{-- Kontainer Utama --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        {{-- Card Header --}}
        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2 h-5 bg-indigo-600 rounded-full"></span>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Profil Siswa Terdaftar</h2>
            </div>
            <span class="text-xs font-semibold bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-lg">
                Total: {{ $students->count() }} Siswa
            </span>
        </div>

        {{-- Tabel Responsive --}}
        <div class="w-full overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm whitespace-nowrap">
                <thead class="bg-slate-50/70">
                    <tr>
                        <th scope="col" class="text-left font-semibold text-slate-700 py-3.5 px-6">Siswa</th>
                        <th scope="col" class="text-left font-semibold text-slate-700 py-3.5 px-6">NISN</th>
                        <th scope="col" class="text-left font-semibold text-slate-700 py-3.5 px-6">Asal Instansi</th>
                        <th scope="col" class="text-left font-semibold text-slate-700 py-3.5 px-6">Lahir / JK</th>
                        <th scope="col" class="text-left font-semibold text-slate-700 py-3.5 px-6">Alamat Rumah</th>
                        <th scope="col" class="text-center font-semibold text-slate-700 py-3.5 px-6">Status</th>
                        <th scope="col" class="text-center font-semibold text-slate-700 py-3.5 px-6 w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($students as $student)
                        <tr class="hover:bg-slate-50/50 transition">
                            {{-- Kolom Info User --}}
                            <td class="py-3.5 px-6">
                                <div class="flex items-center gap-3">
                                    @if($student->user)
                                        <img src="{{ $student->user->avatarUrl() }}" 
                                            class="w-12 h-12 rounded-xl object-cover border border-slate-100 shadow-xs" alt="">
                                    @else
                                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs uppercase">
                                            {{ substr($student->user->name ?? 'NN', 0, 2) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $student->user->name ?? 'User Terhapus' }}</div>
                                        <div class="text-[11px] text-slate-400 font-medium">{{ $student->user->phone_number ?? '-' }}</div>
                                        <div class="text-[11px] text-slate-400 font-medium">{{ $student->user->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom NISN --}}
                            <td class="py-3.5 px-6 font-mono text-xs text-slate-700 tracking-wide">
                                {{ $student->nisn ?? '-' }}
                            </td>

                            {{-- Kolom Nama Sekolah --}}
                            <td class="py-3.5 px-6 text-slate-600 font-medium">
                                {{ $student->institution_name ?? '-' }}
                            </td>

                            {{-- Kolom Lahir & Jenis Kelamin --}}
                            <td class="py-3.5 px-6 text-xs">
                                <div class="text-slate-700 font-medium">
                                    {{ $student->birth_date ? \Carbon\Carbon::parse($student->birth_date)->translatedFormat('d M Y') : '-' }}
                                </div>
                                <div class="mt-0.5">
                                    @if($student->gender === 'L')
                                        <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">Laki-laki</span>
                                    @elseif($student->gender === 'P')
                                        <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100">Perempuan</span>
                                    @else
                                        <span class="text-slate-400 italic text-[11px]">Belum diisi</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Kolom Alamat --}}
                            <td class="py-3.5 px-6 text-slate-500 max-w-xs whitespace-normal truncate text-xs" title="{{ $student->address }}">
                                {{ $student->address ?? '-' }}
                            </td>

                            {{-- Kolom Status Badge --}}
                            <td class="py-3.5 px-6 text-center whitespace-nowrap">
                                @if($student->status === 'approved')
                                    <span class="px-2.5 py-1 text-xs font-bold bg-emerald-100 text-emerald-800 rounded-full">Approved</span>
                                @elseif($student->status === 'pending')
                                    <span class="px-2.5 py-1 text-xs font-bold bg-amber-100 text-amber-800 rounded-full animate-pulse">Pending</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-bold bg-rose-100 text-rose-800 rounded-full">Rejected</span>
                                @endif
                            </td>

                            {{-- Kolom Kluster Aksi Terintegrasi Verifikasi --}}
                            <td class="py-3.5 px-6 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if($student->status === 'pending')
                                        <form action="/student-biodata/{{$student->id}}/verify" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" title="Setujui Biodata" class="p-1.5 bg-emerald-50 border border-emerald-200 text-emerald-600 rounded-lg hover:bg-emerald-100 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                                </svg>
                                            </button>
                                        </form>

                                        <form action="/student-biodata/{{$student->id}}/verify" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" title="Tolak Biodata" class="p-1.5 bg-amber-50 border border-amber-200 text-amber-600 rounded-lg hover:bg-amber-100 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <button type="button"
                                        onclick="openDeleteModal('', '')"
                                        class="p-1.5 bg-rose-50 border border-rose-100 text-rose-600 rounded-lg hover:bg-rose-100 transition" title="Hapus Permanen">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-sm text-slate-400 italic">
                                Belum ada data biodata siswa yang terekam di sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI HAPUS --}}
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" onclick="closeDeleteModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-slate-100">
            <div class="bg-white px-6 pt-6 pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-xl bg-rose-50 text-rose-600 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider" id="modal-title">Hapus Biodata Permanen</h3>
                        <div class="mt-2">
                            <p class="text-xs text-slate-500 leading-relaxed">
                                Apakah Anda yakin ingin menghapus data biodata milik <strong id="deleteTargetName" class="text-slate-800"></strong>? Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="bg-slate-50 px-6 py-3.5 flex flex-row-reverse gap-2">
                    <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-xs px-4 py-2 bg-rose-600 text-xs font-bold text-white hover:bg-rose-700 focus:outline-none transition sm:w-auto">
                        Ya, Hapus Data
                    </button>
                    <button type="button" onclick="closeDeleteModal()" class="w-full inline-flex justify-center rounded-xl border border-slate-200 shadow-xs px-4 py-2 bg-white text-xs font-bold text-slate-700 hover:bg-slate-50 focus:outline-none transition sm:w-auto">
                        Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(id, name) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        const namePlaceholder = document.getElementById('deleteTargetName');
        
        form.action = `/student-biodata/${id}`;
        namePlaceholder.innerText = name;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection