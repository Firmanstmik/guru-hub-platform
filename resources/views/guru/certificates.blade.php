@extends('layout.master-app')
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
                        class="w-full sm:w-64 bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                </form>

                <button onclick="toggleModal('addCertificateModal')"
                    class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg shadow-xs transition text-sm flex items-center justify-center gap-2 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Terbitkan Sertifikat
                </button>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($certificates as $cert)
                <div
                    class="bg-white p-5 rounded-xl border border-gray-100 shadow-xs hover:border-gray-200 transition duration-150">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                        <div class="flex-1 min-w-0 space-y-2">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-md flex-shrink-0 border border-indigo-100">
                                    🎓
                                </div>
                                <div class="min-w-0 space-y-0.5">
                                    <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5">
                                        <h4 class="text-sm font-bold text-gray-900 truncate"
                                            title="{{ $cert->student->name ?? 'Siswa Non-Aktif' }}">
                                            {{ $cert->student->name ?? 'Siswa Non-Aktif' }}
                                        </h4>
                                        <span
                                            class="inline-flex text-[10px] font-mono bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-md font-bold tracking-wider">
                                            {{ $cert->certificate_code }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-600 font-medium truncate"
                                        title="{{ $cert->course->title ?? 'Kelas Terhapus' }}">
                                        📖 {{ $cert->course->title ?? 'Kelas Terhapus' }}
                                    </p>
                                    <div class="text-[11px] text-gray-400">
                                        📅 Terbit: <span
                                            class="font-medium text-gray-500">{{ $cert->issued_at ? $cert->issued_at->isoFormat('D MMMM YYYY') : '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-between lg:justify-end gap-4 border-t lg:border-t-0 pt-3 lg:pt-0 border-gray-50 flex-shrink-0">
                            <a href="{{ asset('storage/' . $cert->file_path) }}" target="_blank"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 px-3 py-2 rounded-xl hover:bg-indigo-100 transition shadow-2xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                <span>Lihat PDF</span>
                            </a>

                            <button data-certificate='@json($cert)' onclick="handleOpenEditModal(this)"
                                class="px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-bold rounded-xl transition">
                                Edit
                            </button>
                            {{-- <form action="/certificates/{{ $cert->id }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin mencabut dan menghapus permanen sertifikat ini?')"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-xl transition">
                                    Cabut
                                </button>
                            </form> --}}
                        </div>

                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-100 rounded-xl p-12 text-center space-y-3 shadow-xs">
                    <div
                        class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto text-lg">
                        📜
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Sertifikat Tidak Ditemukan</h3>
                        <p class="text-xs text-gray-400 max-w-xs mx-auto">Belum ada riwayat pencatatan atau nama siswa yang
                            dicari tidak terdaftar dalam database.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($certificates->hasPages())
            <div class="mt-4 p-4 bg-white border border-gray-100 rounded-xl shadow-xs">
                {{ $certificates->links() }}
            </div>
        @endif
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

    <!-- MODAL EDIT SERTIFIKAT -->
    <div id="editCertificateModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay Latar Belakang -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <!-- Action URL akan diisi secara dinamis menggunakan JavaScript (misal: /certificates/{id}) -->
                <form id="editCertificateForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Perbarui Data Sertifikat</h3>
                        <p class="text-xs text-gray-500 mb-4">Ubah detail penerima, kelas terkait, atau ganti berkas
                            dokumen sertifikat yang terdaftar.</p>

                        <div class="space-y-4">
                            <!-- Input Siswa -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Siswa</label>
                                <select name="student_id" id="edit_student_id" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm p-2 border">
                                    <option value="">-- Pilih Siswa Penerima --</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Input Kelas -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Kelas Kursus</label>
                                <select name="course_id" id="edit_course_id" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm p-2 border">
                                    <option value="">-- Pilih Kelas Terkait --</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Baris Berkas & Tanggal -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                                        File Dokumen (PDF) <span class="text-gray-400 font-normal">(Opsional)</span>
                                    </label>
                                    <!-- Atribut 'required' dihapus agar jika berkas tidak diganti, dokumen lama tidak hilang -->
                                    <input type="file" name="file" accept="application/pdf"
                                        class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border p-1 rounded-lg">
                                    <p class="text-[10px] text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah berkas
                                        PDF.</p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Terbit</label>
                                    <input type="date" name="issued_at" id="edit_issued_at" required
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm p-2 border">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi Kontrol -->
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition shadow-xs">
                            Simpan Perubahan
                        </button>
                        <button type="button" onclick="toggleModal('editCertificateModal')"
                            class="mt-3 sm:mt-0 w-full sm:w-auto bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(modalId) {
            document.getElementById(modalId).classList.toggle('hidden');
        }

        function handleOpenEditModal(button) {
            // Ambil payload data objek sertifikat dari atribut 'data-certificate'
            const cert = JSON.parse(button.getAttribute('data-certificate'));

            // 1. Atur URL Action Form secara dinamis sesuai ID data sertifikat
            document.getElementById('editCertificateForm').action = `/certificates/${cert.id}`;

            // 2. Set nilai default option & input text berdasarkan data terpilih
            document.getElementById('edit_student_id').value = cert.student_id;
            document.getElementById('edit_course_id').value = cert.course_id;

            // 3. Set format tanggal (mengambil porsi YYYY-MM-DD dari timestamp database)
            if (cert.issued_at) {
                document.getElementById('edit_issued_at').value = cert.issued_at.substring(0, 10);
            }

            // 4. Buka interface jendela modal edit
            toggleModal('editCertificateModal');
        }
    </script>
@endsection
