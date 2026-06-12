@extends('layout.master-app')
@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div>
                <h3 class="text-gray-700 text-3xl font-semibold">Kelola Berkas Materi Belajar</h3>
                <p class="text-sm text-gray-500 mt-1">Pantau dokumen silabus, slide presentasi, atau modul pendukung yang
                    diakses siswa.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <form action="/materials" method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul materi..."
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                    <select name="course_id" onchange="this.form.submit()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                        <option value="">Semua Kelas</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}</option>
                        @endforeach
                    </select>
                </form>

                <button onclick="toggleModal('addMaterialModal')"
                    class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm shadow-xs transition flex items-center justify-center gap-2 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Unggah Berkas
                </button>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($materials as $mat)
                <div
                    class="bg-white p-5 rounded-xl border border-gray-100 shadow-xs hover:border-gray-200 transition duration-150">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                        <div class="flex-1 min-w-0 space-y-2">
                            <div class="flex items-start gap-3">
                                <div class="min-w-0 space-y-0.5">
                                    <h4 class="text-sm font-bold text-gray-900 truncate">{{ $mat->title }}</h4>

                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-800 text-xs">
                                            📖 {{ $mat->course->title ?? 'Kelas Tidak Ditemukan' }}
                                        </span>
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $mat->completed_count >= 5 ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                                            {{ $mat->completed_count }} Siswa Selesai
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex flex-wrap sm:flex-nowrap items-center justify-between lg:justify-end gap-4 border-t lg:border-t-0 pt-3 lg:pt-0 border-gray-50 flex-shrink-0">
                            <button type="button"
                                onclick="openDocumentModal('{{ asset('storage/' . $mat->file_path) }}', '{{ $mat->title ?? 'Dokumen Materi' }}')"
                                class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md hover:bg-indigo-100 transition focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5l4 4v13a2 2 0 01-2 2z" />
                                </svg>
                                Lihat Dokumen
                            </button>
                            <div class="flex items-center gap-2">
                                @if ($mat->completed_count >= 5)
                                    <a href="/guru/schedules/create?material_id={{ $mat->id }}"
                                        class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition">
                                        + Atur Live Class
                                    </a>
                                @else
                                    <button disabled
                                        class="px-3 py-2 bg-gray-100 text-gray-400 text-xs font-bold rounded-xl cursor-not-allowed">
                                        Perlu 5+ Selesai
                                    </button>
                                @endif
                                <a href="{{ url('/materials/'. $mat->id) }}"
                                    class="px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold rounded-xl transition flex items-center gap-1">
                                    📝 Kelola Kuis
                                </a>

                                <button data-material='@json($mat)' onclick="handleOpenEditModal(this)"
                                    class="px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-bold rounded-xl transition">
                                    Edit
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-100 rounded-xl p-12 text-center space-y-3 shadow-xs">
                    <div
                        class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto text-lg">
                        📂
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Berkas Tidak Ditemukan</h3>
                        <p class="text-xs text-gray-400 max-w-xs mx-auto">Tidak ada dokumen materi pembelajaran yang sesuai
                            dengan kata kunci atau filter saat ini.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($materials->hasPages())
            <div class="mt-4 p-4 bg-white border border-gray-100 rounded-xl shadow-xs">
                {{ $materials->links() }}
            </div>
        @endif
    </div>
    <div id="addMaterialModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="/materials" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Unggah Materi Pembelajaran</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Hubungkan ke Kelas</label>
                                <select name="course_id" required
                                    class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <option value="">-- Pilih Program Kelas --</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->title }} (Guru:
                                            {{ $course->teacher->name }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Judul / Nama Modul
                                    Materi</label>
                                <input type="text" name="title" required
                                    placeholder="Contoh: Bab 2 - Pengenalan Kosakata Dasar Partikel Wa"
                                    class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih File Berkas (PDF, DOCX,
                                    PPTX, ZIP - Max 5MB)</label>
                                <input type="file" name="file" required
                                    class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 border p-1 rounded-lg">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">Mulai
                            Unggah</button>
                        <button type="button" onclick="toggleModal('addMaterialModal')"
                            class="mt-3 sm:mt-0 w-full sm:w-auto bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editMaterialModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Data Dokumen Materi</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Hubungkan ke Kelas</label>
                                <select id="edit_course_id" name="course_id" required
                                    class="w-full border-gray-300 rounded-lg text-sm p-2.5 border">
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Judul / Nama Modul
                                    Materi</label>
                                <input type="text" id="edit_title" name="title" required
                                    class="w-full border-gray-300 rounded-lg text-sm p-2.5 border">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Ganti File Berkas (Opsional -
                                    Beresiko menimpa berkas lama)</label>
                                <input type="file" name="file"
                                    class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 border p-1 rounded-lg">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">Perbarui
                            Dokumen</button>
                        <button type="button" onclick="toggleModal('editMaterialModal')"
                            class="mt-3 sm:mt-0 w-full sm:w-auto bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="documentPreviewModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="fixed inset-0 transition-opacity" onclick="closeDocumentModal()">
            <div class="absolute inset-0 bg-gray-600 opacity-75 backdrop-blur-sm"></div>
        </div>

        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div
                class="relative bg-white rounded-2xl overflow-hidden shadow-2xl transform transition-all max-w-5xl w-full border border-gray-200 flex flex-col h-[85vh]">

                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5l4 4v13a2 2 0 01-2 2z" />
                        </svg>
                        <h3 id="documentModalTitle"
                            class="text-sm font-semibold text-gray-900 truncate max-w-md sm:max-w-xl">Memuat Dokumen...
                        </h3>
                    </div>
                    <div class="flex items-center gap-3">
                        <a id="documentDownloadBtn" href="#" target="_blank" download
                            class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1.5 rounded-md hover:bg-emerald-100 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Unduh File
                        </a>
                        <button type="button" onclick="closeDocumentModal()"
                            class="text-gray-400 hover:text-gray-600 transition focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="grow w-full bg-gray-100 relative">
                    <div id="documentContainer" class="w-full h-full">
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function toggleModal(modalId) {
            document.getElementById(modalId).classList.toggle('hidden');
        }

        function handleOpenEditModal(button) {
            const materialData = JSON.parse(button.getAttribute('data-material'));
            openEditModal(materialData); // Teruskan objek bersih ke fungsi modal utama Anda
        }

        function openEditModal(material) {
            document.getElementById('edit_course_id').value = material.course_id;
            document.getElementById('edit_title').value = material.title;

            const form = document.getElementById('editForm');
            // PENGUBAHAN: Penyesuaian ke hardcoded URL untuk rute pembaruan berkas di JavaScript
            form.action = `/materials/${material.id}`;

            toggleModal('editMaterialModal');
        }


        function openDocumentModal(fileUrl, title) {
            const modal = document.getElementById('documentPreviewModal');
            const modalTitle = document.getElementById('documentModalTitle');
            const container = document.getElementById('documentContainer');
            const downloadBtn = document.getElementById('documentDownloadBtn');

            // Set Judul & Link Download Asli
            modalTitle.textContent = title;
            downloadBtn.href = fileUrl;

            // Kosongkan kontainer lama
            container.innerHTML = '';

            // Ambil ekstensi berkas secara lowercase
            const extension = fileUrl.split('.').pop().toLowerCase();

            let htmlContent = '';

            // Kasus 1: Berkas PDF (Dapat ditampilkan secara native di browser modern)
            if (extension === 'pdf') {
                htmlContent =
                    `<iframe src="${fileUrl}#toolbar=0" class="w-full h-full border-0" allow="autoplay"></iframe>`;
            }
            // Kasus 2: Gambar/Foto Materi (PNG, JPG, JPEG, WEBP, GIF)
            else if (['png', 'jpg', 'jpeg', 'webp', 'gif'].includes(extension)) {
                htmlContent = `
            <div class="w-full h-full flex items-center justify-center p-4 overflow-auto">
                <img src="${fileUrl}" alt="${title}" class="max-w-full max-h-full object-contain rounded shadow-md bg-white">
            </div>
        `;
            }
            // Kasus 3: File dokumen kantor (Docx, Xlsx, Pptx) yang membutuhkan Google Docs Viewer agar tidak terunduh paksa
            else if (['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(extension)) {
                // Menggunakan Google Docs Embedded Viewer
                const googleViewerUrl = `https://docs.google.com/gview?url=${encodeURIComponent(fileUrl)}&embedded=true`;
                htmlContent = `<iframe src="${googleViewerUrl}" class="w-full h-full border-0"></iframe>`;
            }
            // Kasus Alternatif lainnya
            else {
                htmlContent = `<iframe src="${fileUrl}" class="w-full h-full border-0"></iframe>`;
            }

            // Suntikkan komponen render ke kontainer modal
            container.innerHTML = htmlContent;

            // Tampilkan modal ke view browser
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Kunci scroll halaman utama
        }

        function closeDocumentModal() {
            const modal = document.getElementById('documentPreviewModal');
            const container = document.getElementById('documentContainer');

            // Sembunyikan Modal
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Aktifkan kembali scroll halaman utama

            // Hancurkan iframe/content di dalam agar memory clear dan proses network berhenti
            container.innerHTML = '';
        }

        // Menutup modal otomatis jika menekan tombol 'Esc' di keyboard
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDocumentModal();
            }
        });
    </script>
@endsection
