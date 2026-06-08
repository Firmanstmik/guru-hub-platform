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
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Database Profil Siswa Terdaftar</h2>
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
                        <th scope="col" class="text-center font-semibold text-slate-700 py-3.5 px-6 w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($students as $student)
                        <tr class="hover:bg-slate-50/50 transition">
                            {{-- Kolom Info User (Avatar + Nama + Telp) --}}
                            <td class="py-3.5 px-6">
                                <div class="flex items-center gap-3">
                                    @if($student->user && $student->user->avatar)
                                        <img src="{{ asset('storage/' . $student->user->avatar) }}" 
                                            class="w-9 h-9 rounded-xl object-cover border border-slate-100 shadow-xs">
                                    @else
                                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs uppercase">
                                            {{ substr($student->user->name ?? 'NN', 0, 2) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $student->user->name ?? 'User Terhapus' }}</div>
                                        <div class="text-[11px] text-slate-400 font-medium">{{ $student->user->phone_number ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom NISN --}}
                            <td class="py-3.5 px-6 font-mono text-xs text-slate-700 tracking-wide">
                                {{ $student->nisn }}
                            </td>

                            {{-- Kolom Nama Sekolah --}}
                            <td class="py-3.5 px-6 text-slate-600 font-medium">
                                {{ $student->institution_name }}
                            </td>

                            {{-- Kolom Lahir & Jenis Kelamin --}}
                            <td class="py-3.5 px-6 text-xs">
                                <div class="text-slate-700 font-medium">
                                    {{ \Carbon\Carbon::parse($student->birth_date)->translatedFormat('d M Y') }}
                                </div>
                                <div class="mt-0.5">
                                    @if($student->gender === 'L')
                                        <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">Laki-laki</span>
                                    @else
                                        <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100">Perempuan</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Kolom Alamat (Truncate agar tetap pendek di tabel) --}}
                            <td class="py-3.5 px-6 text-slate-500 max-w-xs whitespace-normal truncate text-xs" title="{{ $student->address }}">
                                {{ $student->address }}
                            </td>

                            {{-- Kolom Aksi Trigger Modal --}}
                            <td class="py-3.5 px-6 text-center">
                                <button type="button"
                                    onclick="openDeleteModal('{{ $student->id }}', '{{ addslashes($student->user->name ?? 'Siswa ini') }}')"
                                    class="inline-flex items-center justify-center bg-rose-50 border border-rose-100 text-rose-600 font-bold px-3 py-1.5 rounded-lg hover:bg-rose-100 transition text-xs">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-sm text-slate-400 italic">
                                Belum ada data biodata siswa yang terekam di sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI HAPUS MODERAT --}}
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Overlay Latar Gelap --}}
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity" onclick="closeDeleteModal()"></div>

        {{-- Trik memusatkan modal di tengah layar --}}
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        {{-- Isi Konten Kotak Modal --}}
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
                                Apakah Anda yakin ingin menghapus data biodata milik <strong id="deleteTargetName" class="text-slate-800"></strong>? Tindakan ini tidak dapat dibatalkan dan berkas foto profil yang terkait akan ikut dihapus.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Form Aksi Modal --}}
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

{{-- JAVASCRIPT KONTROL MODAL --}}
<script>
    function openDeleteModal(id, name) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        const namePlaceholder = document.getElementById('deleteTargetName');
        
        // Atur action form URL secara dinamis ke route destroy
        form.action = `/student-biodata/${id}`;
        
        // Masukkan nama siswa ke teks modal
        namePlaceholder.innerText = name;
        
        // Tampilkan modal (hapus class hidden)
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        // Sembunyikan modal kembali
        modal.classList.add('hidden');
    }
</script>
@endsection