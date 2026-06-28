@extends('layout.master-app')
@section('title', 'Materi Pembelajaran')
@section('content')
    <div class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner">
            <x-app.page-header title="Video Pembelajaran" subtitle="Tautan video materi dan rekaman kelas.">
                <x-slot:action>
                    <button onclick="toggleModal('addVideoModal')" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">
                        <x-ui.lucide name="plus" class="h-4 w-4" /> Sematkan
                    </button>
                </x-slot:action>
            </x-app.page-header>

            <form action="/videos" method="GET" class="gh-app-filter-bar">
                <select name="video_type" onchange="this.form.submit()" class="gh-app-select flex-1">
                        <option value="">Semua Tipe Video</option>
                        <option value="material" {{ request('video_type') == 'material' ? 'selected' : '' }}>Materi</option>
                        <option value="recording" {{ request('video_type') == 'recording' ? 'selected' : '' }}>Rekaman
                        </option>
                    </select>
                    <select name="course_id" onchange="this.form.submit()" class="gh-app-select flex-1">
                        <option value="">Semua Kelas</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                </select>
            </form>

        <div class="gh-app-list">
            @forelse($videos as $video)
                <div class="gh-app-list-item">
                    <div class="gh-app-list-thumb relative">
                        <x-app.cover-image type="video" :alt="$video->title" />
                        <span class="absolute inset-0 grid place-items-center bg-[#06122E]/25">
                            <x-ui.lucide name="play" class="h-5 w-5 text-white drop-shadow" />
                        </span>
                    </div>
                    <div class="gh-app-list-body">
                        <div class="flex flex-wrap items-center gap-1.5">
                            <h4 class="gh-app-list-title">{{ $video->title }}</h4>
                            <x-app.badge :variant="$video->video_type === 'material' ? 'info' : 'success'">{{ $video->video_type === 'material' ? 'Materi' : 'Rekaman' }}</x-app.badge>
                        </div>
                        <p class="gh-app-list-meta">📖 {{ $video->course->title ?? 'Kelas Tidak Ditemukan' }}</p>
                    </div>
                    <div class="flex shrink-0 flex-col gap-2">
                        <button type="button" onclick="playVideoModal('{{ $video->video_url }}', '{{ $video->title }}')" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">Putar</button>
                        <button data-video='@json($video)' onclick="handleEditModal(this)" class="gh-app-btn gh-app-btn-ghost gh-app-btn-sm">
                            <x-ui.lucide name="edit" class="h-3.5 w-3.5" />
                        </button>
                    </div>
                </div>
            @empty
                <x-app.empty-state icon="video" title="Video tidak ditemukan" description="Tidak ada video sesuai filter." />
            @endforelse
        </div>

        @if ($videos->hasPages())
            <div class="gh-app-card mt-4">{{ $videos->links() }}</div>
        @endif
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
                <form action="/videos" method="POST">
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
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="sm:col-span-1">
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tipe / Platform</label>
                                    <select name="video_type" required
                                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500">
                                        <option value="material">Materi Kuliah / Kursus</option>
                                        <option value="recording">Rekaman Live / Kelas</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tautan URL Video</label>
                                    <input type="url" name="video_url" required
                                        placeholder="https://www.youtube.com/watch?v=..."
                                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">Sematkan
                            URL</button>
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
                <form id="editForm" method="POST">
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
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="sm:col-span-1">
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tipe / Platform</label>
                                    <select id="edit_video_type" name="video_type" required
                                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500">
                                        <option value="material">Materi Kuliah / Kursus</option>
                                        <option value="recording">Rekaman Live / Kelas</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tautan URL Video</label>
                                    <input type="url" id="edit_video_url" name="video_url" required
                                        class="w-full border-gray-300 rounded-lg text-sm p-2.5 border focus:border-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">Perbarui
                            Tautan</button>
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

            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Kembalikan scroll layar

            container.innerHTML = '';
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeVideoModal();
            }
        });
    </script>
@endsection
