@extends('layout.master-app')
@section('content')
    <div class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner">
            <x-app.page-header title="Kelola Materi" subtitle="Dokumen silabus, slide, dan modul pendukung.">
                <x-slot:action>
                    <button onclick="toggleModal('addMaterialModal')" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">
                        <x-ui.lucide name="upload" class="h-4 w-4" /> Unggah
                    </button>
                </x-slot:action>
            </x-app.page-header>

            <form action="/materials" method="GET" class="gh-app-filter-bar">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul materi..." class="gh-app-input flex-1">
                <select name="course_id" onchange="this.form.submit()" class="gh-app-select flex-1">
                        <option value="">Semua Kelas</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}</option>
                        @endforeach
                </select>
            </form>

        <div class="gh-app-list">
            @forelse($materials as $mat)
                <div class="gh-app-list-item">
                    <div class="gh-app-list-thumb">
                        <x-app.cover-image type="material" :alt="$mat->title" />
                    </div>
                    <div class="gh-app-list-body flex-1 min-w-0">
                    <div class="flex flex-col gap-3">
                        <div>
                            <h4 class="gh-app-subheading truncate">{{ $mat->title }}</h4>
                            <p class="gh-app-caption mt-1">{{ $mat->course->title ?? 'Kelas Tidak Ditemukan' }}</p>
                            <x-app.badge :variant="$mat->completed_count >= 5 ? 'success' : 'neutral'" class="mt-2">{{ $mat->completed_count }} Siswa Selesai</x-app.badge>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="openDocumentModal('{{ asset('storage/' . $mat->file_path) }}', '{{ $mat->title ?? 'Dokumen Materi' }}')" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm">
                                <x-ui.lucide name="eye" class="h-3.5 w-3.5" /> Lihat
                            </button>
                            @if ($mat->completed_count >= 5)
                                <a href="/guru/schedules/create?material_id={{ $mat->id }}" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">+ Live Class</a>
                            @else
                                <button disabled class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm opacity-50 cursor-not-allowed">Perlu 5+ Selesai</button>
                            @endif
                            <a href="{{ url('/materials/'. $mat->id) }}" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm">📝 Kuis</a>
                            <button data-material='@json($mat)' onclick="handleOpenEditModal(this)" class="gh-app-btn gh-app-btn-ghost gh-app-btn-sm">
                                <x-ui.lucide name="edit" class="h-3.5 w-3.5" /> Edit
                            </button>
                        </div>
                    </div>
                    </div>
                </div>
            @empty
                <x-app.empty-state icon="file-text" title="Berkas tidak ditemukan" description="Tidak ada dokumen materi sesuai filter." />
            @endforelse
        </div>

        @if ($materials->hasPages())
            <div class="gh-app-card mt-4">{{ $materials->links() }}</div>
        @endif
        </div>
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
                        <button type="submit" class="gh-app-btn gh-app-btn-primary">Mulai Unggah</button>
                        <button type="button" onclick="toggleModal('addMaterialModal')" class="gh-app-btn gh-app-btn-secondary">Batal</button>
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
                        <button type="submit" class="gh-app-btn gh-app-btn-primary">Perbarui Dokumen</button>
                        <button type="button" onclick="toggleModal('editMaterialModal')" class="gh-app-btn gh-app-btn-secondary">Batal</button>
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
