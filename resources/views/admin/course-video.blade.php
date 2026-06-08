@extends('layout.template')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div>
                <h3 class="text-gray-700 text-3xl font-semibold">Kelola Video Pembelajaran</h3>
                <p class="text-sm text-gray-500 mt-1">Pantau tautan video materi, rekaman kelas, dan rekaman materi
                    asinkronus.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <form action="/videos" method="GET" class="flex gap-2 w-full sm:w-auto">
                    <select name="video_type" onchange="this.form.submit()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="">Semua Tipe Video</option>
                        <option value="material" {{ request('video_type') == 'material' ? 'selected' : '' }}>Materi</option>
                        <option value="recording" {{ request('video_type') == 'recording' ? 'selected' : '' }}>Rekaman
                        </option>
                    </select>
                    <select name="course_id" onchange="this.form.submit()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="">Semua Kelas</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <button onclick="toggleModal('addVideoModal')"
                    class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm shadow-xs transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Sematkan Video
                </button>
            </div>
        </div>


        <div class="w-full overflow-x-auto rounded-2xl border border-slate-200 shadow-xs bg-white">
            <table class="min-w-full divide-y divide-slate-200 text-sm whitespace-nowrap">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Judul Video Materi</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Terikat di Kelas</th>
                        {{-- Sembunyikan kolom header Guru jika yang masuk adalah Guru --}}
                        @role('admin')
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Guru Pengajar</th>
                        @endrole
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Tipe Video</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Tautan URL</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @forelse($videos as $video)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                {{ $video->title }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 font-medium">
                                {{ $video->course->title ?? 'Kelas Tidak Ditemukan' }}
                            </td>
                            {{-- Sembunyikan baris data nama Guru jika bukan Admin --}}
                            @role('admin')
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $video->course->teacher->name ?? 'Tidak Ada' }}
                                </td>
                            @endrole
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($video->video_type === 'material')
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Materi</span>
                                @else
                                    <span
                                        class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">Rekaman</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                <button type="button"
                                    onclick="playVideoModal('{{ $video->video_url }}', '{{ $video->title }}')"
                                    class="inline-flex items-center gap-1 font-semibold text-indigo-600 hover:text-indigo-900 transition focus:outline-none">
                                    Putar Video
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-medium flex justify-end gap-3 mt-0.5">
                                <button data-video='@json($video)' onclick="handleEditModal(this)"
                                    class="text-amber-600 hover:text-amber-900">Edit</button>

                                <form action="/videos/{{ $video->id }}" method="POST"
                                    onsubmit="return confirm('Hapus tautan video materi ini dari sistem?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            {{-- Sesuaikan jumlah kolom tabel secara dinamis berdasarkan status hak akses login --}}
                            <td colspan="{{ auth()->user()->hasRole('admin') ? '6' : '5' }}"
                                class="px-6 py-12 text-center text-gray-500">Tidak ada data video pembelajaran yang
                                disematkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $videos->links() }}
        </div>
    </div>

    <div id="addVideoModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="/videos" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Sematkan Video Pembelajaran</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Hubungkan ke Kelas</label>
                                <select name="course_id" required
                                    class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <option value="">-- Pilih Program Kelas --</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->title }} (Guru:
                                            {{ $course->teacher->name ?? 'Tidak Ada' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Judul / Pembahasan
                                    Video</label>
                                <input type="text" name="title" required
                                    placeholder="Contoh: Pengenalan Karakter Kanji Dasar - Bagian 1"
                                    class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500">
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tipe / Kategori
                                        Video</label>
                                    <select name="video_type" required
                                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500">
                                        <option value="material">Materi Kuliah / Kursus</option>
                                        <option value="recording">Rekaman Live / Kelas</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Opsi A: Tautan URL Video
                                        External</label>
                                    <input type="url" name="video_url"
                                        placeholder="https://www.youtube.com/watch?v=..."
                                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500">
                                    <p class="text-[11px] text-gray-500 mt-0.5">Gunakan tautan eksternal jika berkas sudah
                                        tersimpan di YouTube/Drive.</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Opsi B: Unggah Berkas
                                        Video Lokal (Render Server)</label>
                                    <input type="file" name="video_file"
                                        accept="video/mp4,video/quicktime,video/x-msvideo"
                                        class="w-full border border-gray-300 rounded-lg text-sm p-2 text-gray-700 focus:border-indigo-500 bg-white file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                    <p class="text-[11px] text-amber-600 font-medium mt-1">Ekstensi yang didukung: MP4,
                                        MOV, AVI (Maks. 100MB). Video akan otomatis dikonversi ke format WebM demi
                                        optimalisasi performa streaming.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">Proses
                            & Simpan</button>
                        <button type="button" onclick="toggleModal('addVideoModal')"
                            class="mt-3 sm:mt-0 w-full sm:w-auto bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="editVideoModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Tautan Video Pembelajaran</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Hubungkan ke Kelas</label>
                                <select id="edit_course_id" name="course_id" required
                                    class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500">
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Judul / Pembahasan
                                    Video</label>
                                <input type="text" id="edit_title" name="title" required
                                    class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500">
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tipe / Kategori
                                        Video</label>
                                    <select id="edit_video_type" name="video_type" required
                                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500">
                                        <option value="material">Materi Kuliah / Kursus</option>
                                        <option value="recording">Rekaman Live / Kelas</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Opsi A: Tautan URL Video
                                        (External / Saat Ini)</label>
                                    <input type="url" id="edit_video_url" name="video_url"
                                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500 bg-gray-50">
                                    <p class="text-[11px] text-gray-500 mt-0.5">Biarkan atau ubah tautan ini jika ingin
                                        menggunakan platform luar seperti YouTube/Drive.</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Opsi B: Ganti & Unggah
                                        File Video Baru (Render Server)</label>
                                    <input type="file" name="video_file"
                                        accept="video/mp4,video/quicktime,video/x-msvideo"
                                        class="w-full border border-gray-300 rounded-lg text-sm p-2 text-gray-700 focus:border-indigo-500 bg-white file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                                    <p class="text-[11px] text-amber-600 font-medium mt-1">Kosongkan jika tidak ingin
                                        mengganti file video fisik. Jika Anda mengunggah file baru di sini, otomatis file
                                        video lama di server akan dihapus dan digantikan hasil render WebM yang baru (Maks.
                                        100MB).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">Perbarui
                            Data Video</button>
                        <button type="button" onclick="toggleModal('editVideoModal')"
                            class="mt-3 sm:mt-0 w-full sm:w-auto bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="videoPlayerModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="fixed inset-0 transition-opacity" onclick="closeVideoModal()">
            <div class="absolute inset-0 bg-gray-900 opacity-80 backdrop-blur-sm"></div>
        </div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div
                class="relative bg-black rounded-2xl overflow-hidden shadow-2xl transform transition-all sm:max-w-3xl w-full border border-gray-800">

                <div
                    class="absolute top-0 inset-x-0 bg-gradient-to-b from-black/80 to-transparent p-4 flex items-center justify-between z-10">
                    <h3 id="videoModalTitle" class="text-sm font-medium text-white truncate pr-8">Memuat Video...</h3>
                    <button type="button" onclick="closeVideoModal()"
                        class="text-gray-400 hover:text-white transition focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="relative aspect-video w-full bg-black flex items-center justify-center">
                    <div id="videoContainer" class="w-full h-full">
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        function toggleModal(modalId) {
            document.getElementById(modalId).classList.toggle('hidden');
        }

        function handleEditModal(button) {
            const videoData = JSON.parse(button.getAttribute('data-video'));
            openEditModal(videoData);
        }

        function openEditModal(video) {
            document.getElementById('edit_course_id').value = video.course_id;
            document.getElementById('edit_title').value = video.title;
            document.getElementById('edit_video_type').value = video.video_type;
            document.getElementById('edit_video_url').value = video.video_url;

            const form = document.getElementById('editForm');
            form.action = `/videos/${video.id}`;

            toggleModal('editVideoModal');
        }

        function playVideoModal(url, title) {
            const modal = document.getElementById('videoPlayerModal');
            const modalTitle = document.getElementById('videoModalTitle');
            const container = document.getElementById('videoContainer');

            // Set Judul Video
            modalTitle.textContent = title;

            // Kosongkan kontainer lama terlebih dahulu
            container.innerHTML = '';

            let htmlPlayer = '';

            // Deteksi jika link merupakan internal server WebM / MP4 hasil render Docker
            if (url.includes('.webm') || url.includes('.mp4') || url.includes('.mov') || url.includes('.avi')) {
                htmlPlayer = `
            <video controls autoplay controlsList="nodownload" class="w-full h-full object-contain focus:outline-none">
                <source src="${url}" type="video/webm">
                <source src="${url}" type="video/mp4">
                Browser Anda tidak mendukung pemutar video HTML5.
            </video>
        `;
            }
            // Deteksi jika link merupakan video dari Youtube biasa atau Share link
            else if (url.includes('youtube.com') || url.includes('youtu.be')) {
                let videoId = '';
                if (url.includes('youtu.be/')) {
                    videoId = url.split('youtu.be/')[1].split(/[?#]/)[0];
                } else if (url.includes('v=')) {
                    videoId = url.split('v=')[1].split('&')[0];
                } else if (url.includes('embed/')) {
                    videoId = url.split('embed/')[1].split(/[?#]/)[0];
                }

                htmlPlayer = `
            <iframe src="https://www.youtube.com/embed/${videoId}?autoplay=1" 
                class="w-full h-full" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>
        `;
            }
            // Alternatif jika berupa tautan Google Drive / URL mentah lainnya
            else {
                htmlPlayer = `
            <iframe src="${url}" class="w-full h-full" frameborder="0" allow="autoplay" allowfullscreen></iframe>
        `;
            }

            // Suntikkan elemen player ke dalam DOM kontainer modal
            container.innerHTML = htmlPlayer;

            // Tampilkan Modal ke Layar
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Kunci scroll layar belakang
        }

        function closeVideoModal() {
            const modal = document.getElementById('videoPlayerModal');
            const container = document.getElementById('videoContainer');

            // Sembunyikan Modal
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Kembalikan scroll layar

            // FIX PENTING: Hancurkan elemen di dalam kontainer agar suara video otomatis berhenti saat modal ditutup
            container.innerHTML = '';
        }

        // Menutup modal otomatis jika menekan tombol 'Esc' di keyboard
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeVideoModal();
            }
        });
    </script>
@endsection
