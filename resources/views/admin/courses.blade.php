@extends('layout.template')

@section('content')
    <div class="container mx-auto px-6 py-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div>
                <h3 class="text-gray-700 text-3xl font-semibold">Kelola Kelas / Kursus</h3>
                <p class="text-sm text-gray-500 mt-1">Daftar program pembelajaran aktif, draf materi, dan arsip program.</p>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <form action="/courses" method="GET" class="flex gap-2 w-full sm:w-auto">
                    <select name="category_id" onchange="this.form.submit()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <select name="status" onchange="this.form.submit()"
                        class="bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published
                        </option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </form>

                <button onclick="toggleModal('addCourseModal')"
                    class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm shadow-xs transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Kelas Baru
                </button>
            </div>
        </div>

        <div class="w-full overflow-x-auto rounded-2xl border border-slate-200 shadow-xs bg-white">
            <table class="min-w-full divide-y divide-slate-200 text-sm whitespace-nowrap">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Cover & Judul Kelas</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                        @role('admin')
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Guru Pengajar</th>
                        @endrole
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Harga Paket</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Murid</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                    @forelse($courses as $course)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $course->cover_image ? asset('storage/' . $course->cover_image) : 'https://placehold.co/600x400?text=No+Image' }}"
                                        alt="Cover" class="w-14 h-10 object-cover rounded-md bg-gray-100 border">
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $course->title }}</div>
                                        <div class="text-xs text-gray-400 max-w-xs truncate">{{ $course->description }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-medium">
                                {{ $course->category->name }}
                            </td>
                            @role('admin')
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    {{ $course->teacher->name }}
                                </td>
                            @endrole
                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">
                                Rp {{ number_format($course->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $course->students_count }} Siswa
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($course->status === 'published')
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full">Published</span>
                                @elseif($course->status === 'draft')
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">Draft</span>
                                @else
                                    <span
                                        class="px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Archived</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-medium flex justify-end gap-3 mt-2">
                                <button data-course='@json($course)' onclick="handleOpenEditModal(this)"
                                    class="text-amber-600 hover:text-amber-900">Edit</button>

                                <form action="/courses/{{ $course->id }}" method="POST"
                                    onsubmit="return confirm('Hapus permanen program kelas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->hasRole('admin') ? '7' : '6' }}"
                                class="px-6 py-12 text-center text-gray-500">Tidak ada program kelas yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $courses->links() }}
        </div>
    </div>
    
    <div id="addCourseModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="/courses" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Buat Program Kelas Baru</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Kategori</label>
                                    <select name="category_id" required
                                        class="w-full border-gray-300 rounded-lg text-sm p-2 border">
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Guru Pengajar</label>
                                    <select name="teacher_id" required
                                        class="w-full border-gray-300 rounded-lg text-sm p-2 border">
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Judul Kursus / Kelas</label>
                                <input type="text" name="title" required
                                    placeholder="Contoh: Percakapan Bahasa Jepang Tingkat Dasar (N5)"
                                    class="w-full border-gray-300 rounded-lg text-sm p-2.5 border">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi Lengkap</label>
                                <textarea name="description" rows="3" required
                                    placeholder="Tuliskan silabus singkat atau rangkuman target belajar..."
                                    class="w-full border-gray-300 rounded-lg text-sm p-2.5 border"></textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Kelas (IDR)</label>
                                    <input type="number" name="price" required placeholder="0 jika kelas gratis"
                                        class="w-full border-gray-300 rounded-lg text-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status Publikasi</label>
                                    <select name="status" required
                                        class="w-full border-gray-300 rounded-lg text-sm p-2 border">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Upload Gambar Cover</label>
                                <input type="file" name="cover_image" accept="image/*"
                                    class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 border p-1 rounded-lg">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">Simpan
                            Kelas</button>
                        <button type="button" onclick="toggleModal('addCourseModal')"
                            class="mt-3 sm:mt-0 w-full sm:w-auto bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editCourseModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Data Kursus / Kelas</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih Kategori</label>
                                    <select id="edit_category_id" name="category_id" required
                                        class="w-full border-gray-300 rounded-lg text-sm p-2 border">
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Guru Pengajar</label>
                                    <select id="edit_teacher_id" name="teacher_id" required
                                        class="w-full border-gray-300 rounded-lg text-sm p-2 border">
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Judul Kursus / Kelas</label>
                                <input type="text" id="edit_title" name="title" required
                                    class="w-full border-gray-300 rounded-lg text-sm p-2.5 border">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi Lengkap</label>
                                <textarea id="edit_description" name="description" rows="3" required
                                    class="w-full border-gray-300 rounded-lg text-sm p-2.5 border"></textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Harga Kelas (IDR)</label>
                                    <input type="number" id="edit_price" name="price" required
                                        class="w-full border-gray-300 rounded-lg text-sm p-2 border">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status Publikasi</label>
                                    <select id="edit_status" name="status" required
                                        class="w-full border-gray-300 rounded-lg text-sm p-2 border">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Ganti Berkas Cover
                                    (Opsional)</label>
                                <input type="file" name="cover_image" accept="image/*"
                                    class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 border p-1 rounded-lg">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">Perbarui
                            Kelas</button>
                        <button type="button" onclick="toggleModal('editCourseModal')"
                            class="mt-3 sm:mt-0 w-full sm:w-auto bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-4 py-2 rounded-lg text-sm transition">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function handleOpenEditModal(button) {
            const courseData = JSON.parse(button.getAttribute('data-course'));
            openEditModal(courseData); // panggil fungsi modal aseli Anda di sini
        }

        function toggleModal(modalId) {
            document.getElementById(modalId).classList.toggle('hidden');
        }

        function openEditModal(course) {
            document.getElementById('edit_category_id').value = course.category_id;
            document.getElementById('edit_teacher_id').value = course.teacher_id;
            document.getElementById('edit_title').value = course.title;
            document.getElementById('edit_description').value = course.description;
            document.getElementById('edit_price').value = Math.round(course.price);
            document.getElementById('edit_status').value = course.status;

            const form = document.getElementById('editForm');
            // PENGUBAHAN: Penyesuaian ke hardcoded URL untuk rute pembaruan kelas di JavaScript
            form.action = `/courses/${course.id}`;

            toggleModal('editCourseModal');
        }
    </script>
@endsection
