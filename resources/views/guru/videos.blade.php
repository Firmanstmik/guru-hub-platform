@extends('layout.master-app')
@section('title', 'Materi Pembelajaran')
@section('content')
<div class="container mx-auto px-6 py-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div>
                <h3 class="text-gray-700 text-3xl font-semibold">Kelola Video Pembelajaran</h3>
                <p class="text-sm text-gray-500 mt-1">Pantau tautan video materi, rekaman kelas, dan rekaman materi asinkronus.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <form action="/videos" method="GET" class="flex gap-2 w-full sm:w-auto">
                    <select name="video_type" onchange="this.form.submit()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                        <option value="">Semua Tipe Video</option>
                        <option value="material" {{ request('video_type') == 'material' ? 'selected' : '' }}>Materi</option>
                        <option value="recording" {{ request('video_type') == 'recording' ? 'selected' : '' }}>Rekaman</option>
                    </select>
                    <select name="course_id" onchange="this.form.submit()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                        <option value="">Semua Kelas</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <button onclick="toggleModal('addVideoModal')"
                    class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm shadow-xs transition flex items-center justify-center gap-2 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Sematkan Video
                </button>
            </div>
        </div>

        <div class="space-y-3">
            @forelse($videos as $video)
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-xs hover:border-gray-200 transition duration-150">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        
                        <div class="flex-1 min-w-0 space-y-2">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-md flex-shrink-0 border border-indigo-100">
                                    ▶️
                                </div>
                                <div class="min-w-0 space-y-0.5">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="text-sm font-bold text-gray-900 truncate max-w-md" title="{{ $video->title }}">
                                            {{ $video->title }}
                                        </h4>
                                        
                                        @if ($video->video_type === 'material')
                                            <span class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-100 text-indigo-800">Materi</span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded-md text-[10px] font-bold bg-teal-100 text-teal-800">Rekaman</span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-400">
                                        <span class="font-medium text-gray-600">
                                            📖 {{ $video->course->title ?? 'Kelas Tidak Ditemukan' }}
                                        </span>
                                        @role('admin')
                                            <span class="hidden sm:inline text-gray-300">|</span>
                                            <span class="flex items-center gap-1">
                                                👨‍🏫 {{ $video->course->teacher->name ?? 'Tidak Ada' }}
                                            </span>
                                        @endrole
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap sm:flex-nowrap items-center justify-between lg:justify-end gap-4 border-t lg:border-t-0 pt-3 lg:pt-0 border-gray-50 flex-shrink-0">
                            <a href="{{ $video->video_url }}" target="_blank"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 bg-indigo-50 px-3 py-2 rounded-xl hover:bg-indigo-100 transition shadow-2xs">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Putar Video</span>
                            </a>

                            <div class="flex items-center gap-2">
                                <button data-video='@json($video)' onclick="handleEditModal(this)"
                                    class="px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-700 text-xs font-bold rounded-xl transition">
                                    Edit
                                </button>
{{-- 
                                <form action="/videos/{{ $video->id }}" method="POST"
                                    onsubmit="return confirm('Hapus tautan video materi ini dari sistem?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-xl transition">
                                        Hapus
                                    </button>
                                </form> --}}
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-100 rounded-xl p-12 text-center space-y-3 shadow-xs">
                    <div class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto text-lg">
                        📹
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Video Tidak Ditemukan</h3>
                        <p class="text-xs text-gray-400 max-w-xs mx-auto">Tidak ada rekaman atau materi video yang sesuai dengan kriteria penyaringan saat ini.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($videos->hasPages())
            <div class="mt-4 p-4 bg-white border border-gray-100 rounded-xl shadow-xs">
                {{ $videos->links() }}
            </div>
        @endif
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
    </script>
@endsection