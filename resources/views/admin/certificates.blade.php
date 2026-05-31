@extends('layout.template')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h3 class="text-gray-700 text-3xl font-semibold">Kelola Sertifikat Kelulusan</h3>
                <p class="text-sm text-gray-500 mt-1">Daftar sertifikat resmi yang diterbitkan untuk siswa Guru Hub.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                <form action="/certificates" method="GET" class="w-full sm:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama siswa / kode..."
                        class="w-full sm:w-64 bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </form>

                <button onclick="toggleModal('addCertificateModal')"
                    class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg shadow-xs transition text-sm flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Terbitkan Sertifikat
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Kode Sertifikat</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Nama Siswa</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Kelas Kursus</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Tanggal Terbit</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100 text-sm">
                        @forelse($certificates as $cert)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-indigo-600 font-semibold">
                                    {{ $cert->certificate_code }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    {{ $cert->student->name ?? 'Siswa Non-Aktif' }}
                                </td>
                                <td class="px-6 py-4 text-gray-700 font-medium">
                                    {{ $cert->course->title ?? 'Kelas Terhapus' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{-- PERBAIKAN: Langsung panggil properti karena sudah dicasting menjadi date di model --}}
                                    {{ $cert->issued_at ? $cert->issued_at->isoFormat('D MMMM YYYY') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-medium flex justify-end gap-3">
                                    <a href="{{ asset('storage/' . $cert->file_path) }}" target="_blank"
                                        class="text-indigo-600 hover:text-indigo-900 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                        Lihat PDF
                                    </a>

                                    <form action="/certificates/{{ $cert->id }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin mencabut dan menghapus permanen sertifikat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-900">Cabut</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada riwayat penerbitan
                                    sertifikat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                {{ $certificates->links() }}
            </div>
        </div>
    </div>

    <div id="addCertificateModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="/certificates" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Terbitkan Sertifikat Manual</h3>
                        <p class="text-xs text-gray-500 mb-4">Sistem akan secara otomatis membuat kode unik sertifikat
                            setelah formulir disimpan.</p>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Siswa</label>
                                <select name="student_id" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm p-2 border">
                                    <option value="">-- Pilih Siswa Penerima --</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Kelas Kursus</label>
                                <select name="course_id" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm p-2 border">
                                    <option value="">-- Pilih Kelas Terkait --</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">File Dokumen Sertifikat
                                        (PDF)</label>
                                    <input type="file" name="file" accept="application/pdf" required
                                        class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border p-1 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Terbit</label>
                                    <input type="date" name="issued_at" value="{{ date('Y-m-d') }}" required
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm p-2 border">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition shadow-xs">Simpan
                            & Terbitkan</button>
                        <button type="button" onclick="toggleModal('addCertificateModal')"
                            class="mt-3 sm:mt-0 w-full sm:w-auto bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(modalId) {
            document.getElementById(modalId).classList.toggle('hidden');
        }
    </script>
@endsection
