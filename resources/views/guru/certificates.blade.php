@extends('layout.master-app')
@section('content')
    <div class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner">
            <x-app.page-header title="Kelola Sertifikat" subtitle="Sertifikat resmi untuk siswa Guru Hub.">
                <x-slot:action>
                    <button onclick="toggleModal('addCertificateModal')" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">
                        <x-ui.lucide name="plus" class="h-4 w-4" /> Terbitkan
                    </button>
                </x-slot:action>
            </x-app.page-header>

            <form action="/certificates" method="GET" class="gh-app-search mb-3">
                <x-ui.lucide name="search" class="gh-app-search-icon" />
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa / kode..." class="gh-app-input">
            </form>

        <div class="gh-app-list">
            @forelse($certificates as $cert)
                <div class="gh-app-list-item overflow-hidden !p-0">
                    <div class="gh-app-list-thumb !h-auto !w-full sm:!w-28 sm:!min-h-[88px]">
                        <x-app.cover-image type="certificate" :alt="$cert->student->name ?? 'Sertifikat'" />
                    </div>
                    <div class="flex flex-1 flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h4 class="gh-app-subheading truncate">{{ $cert->student->name ?? 'Siswa Non-Aktif' }}</h4>
                                <x-app.badge variant="info">{{ $cert->certificate_code }}</x-app.badge>
                            </div>
                            <p class="gh-app-caption mt-1">{{ $cert->course->title ?? 'Kelas Terhapus' }}</p>
                            <p class="gh-app-caption">{{ $cert->issued_at ? $cert->issued_at->isoFormat('D MMMM YYYY') : '-' }}</p>
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <a href="{{ asset('storage/' . $cert->file_path) }}" target="_blank" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">
                                <x-ui.lucide name="eye" class="h-3.5 w-3.5" /> Lihat
                            </a>
                            <button data-certificate='@json($cert)' onclick="handleOpenEditModal(this)" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm">
                                <x-ui.lucide name="edit" class="h-3.5 w-3.5" /> Edit
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <x-app.empty-state icon="award" title="Sertifikat tidak ditemukan" description="Belum ada sertifikat terdaftar." />
            @endforelse
        </div>

        @if ($certificates->hasPages())
            <div class="gh-app-card mt-4">{{ $certificates->links() }}</div>
        @endif
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
                                        <option value="{{ $course->id }}">{{ $course->catalogLabel() }}</option>
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
                                        <option value="{{ $course->id }}">{{ $course->catalogLabel() }}</option>
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
