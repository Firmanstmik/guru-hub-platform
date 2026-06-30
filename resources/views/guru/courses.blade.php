@extends('layout.master-app')
@section('content')
    <div class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner">
            <x-app.page-header title="Kelola Kelas" subtitle="Buat program belajar berdasarkan jenjang & mapel yang Anda ampu.">
                <x-slot:action>
                    @if ($canCreateCourse)
                        <button type="button" onclick="toggleModal('addCourseModal')" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">
                            <x-ui.lucide name="plus" class="h-4 w-4" /> Buat Kelas
                        </button>
                    @endif
                </x-slot:action>
            </x-app.page-header>

            @unless ($canCreateCourse)
                <div class="gh-app-card mb-4 border-amber-200 bg-amber-50/80 p-4">
                    <p class="text-sm font-semibold text-amber-900">Lengkapi profil pengajar terlebih dahulu</p>
                    <p class="mt-1 text-xs text-amber-800/90">
                        Untuk membuat kelas, daftarkan jenjang dan mata pelajaran yang Anda ampu di halaman profil.
                    </p>
                    <a href="/teachers" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm mt-3 inline-flex">Ke Profil Pengajar</a>
                </div>
            @endunless

            <form action="/courses" method="GET" class="gh-app-filter-bar">
                <select name="subject_id" onchange="this.form.submit()" class="gh-app-select flex-1">
                    <option value="">Semua Mapel</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->educationLevel?->name }} · {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                <select name="status" onchange="this.form.submit()" class="gh-app-select flex-1">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </form>

            <div class="gh-app-list">
                @forelse($courses as $course)
                    <div class="gh-app-list-item">
                        <div class="gh-app-list-thumb">
                            <x-app.cover-image :src="$course->cover_image" type="course" :alt="$course->title" />
                        </div>
                        <div class="gh-app-list-body">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <h3 class="gh-app-list-title">{{ $course->title }}</h3>
                                @if ($course->educationLevel)
                                    <x-app.badge variant="info">{{ $course->educationLevel->name }}</x-app.badge>
                                @endif
                                @if ($course->subject)
                                    <x-app.badge variant="neutral">{{ $course->subject->name }}</x-app.badge>
                                @elseif ($course->category)
                                    <x-app.badge variant="neutral">{{ $course->category->name }}</x-app.badge>
                                @endif
                            </div>
                            <p class="gh-app-caption line-clamp-2">{{ $course->description }}</p>
                            <p class="gh-app-caption mt-1">
                                {{ $course->students_count }} siswa · Rp {{ number_format($course->price, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="flex shrink-0 flex-col items-end gap-2">
                            @if ($course->status === 'published')
                                <x-app.badge variant="success">Published</x-app.badge>
                            @elseif($course->status === 'draft')
                                <x-app.badge variant="warning">Draft</x-app.badge>
                            @else
                                <x-app.badge variant="neutral">Archived</x-app.badge>
                            @endif
                            <div class="flex gap-2">
                                <button type="button" data-course='@json($course)' onclick="handleOpenEditModal(this)"
                                    class="gh-app-btn gh-app-btn-ghost gh-app-btn-sm">
                                    <x-ui.lucide name="edit" class="h-3.5 w-3.5" /> Edit
                                </button>
                                <form action="/courses/{{ $course->id }}" method="POST"
                                    onsubmit="return confirm('Hapus kelas ini? Tindakan tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="gh-app-btn gh-app-btn-ghost gh-app-btn-sm text-rose-600">
                                        <x-ui.lucide name="trash-2" class="h-3.5 w-3.5" />
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <x-app.empty-state
                        icon="book-open"
                        :title="$canCreateCourse ? 'Belum ada kelas' : 'Mapel belum didaftarkan'"
                        :description="$canCreateCourse ? 'Buat kelas pertama Anda berdasarkan mapel yang sudah didaftarkan di profil.' : 'Daftarkan mata pelajaran di profil pengajar, lalu kembali ke sini untuk membuat kelas.'"
                    >
                        @if ($canCreateCourse)
                            <button type="button" onclick="toggleModal('addCourseModal')" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm mt-4 inline-flex">
                                Buat Kelas Pertama
                            </button>
                        @else
                            <a href="/teachers" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm mt-4 inline-flex">Ke Profil Pengajar</a>
                        @endif
                    </x-app.empty-state>
                @endforelse
            </div>

            @if ($courses->hasPages())
                <div class="gh-app-card">{{ $courses->links() }}</div>
            @endif
        </div>
    </div>

    @if ($canCreateCourse)
        <div id="addCourseModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="toggleModal('addCourseModal')" aria-hidden="true"></div>
            <div class="relative z-10 flex min-h-full items-end justify-center p-4 pb-24 sm:items-center sm:pb-4">
                <div class="max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-xl border border-gray-100 bg-white shadow-xl" onclick="event.stopPropagation()">
                    <form action="/courses" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="border-b border-gray-100 px-5 py-4">
                            <h3 class="text-base font-bold text-gray-900">Buat Kelas Baru</h3>
                            <p class="mt-0.5 text-xs text-gray-500">Pilih jenjang & mapel yang Anda ampu, lalu lengkapi detail kelas.</p>
                        </div>
                        <div class="space-y-4 px-5 py-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Jenjang & Mata Pelajaran</label>
                                <x-course.subject-select :subjects="$subjects" id="add_subject_id" />
                            </div>
                            <input type="hidden" name="teacher_id" value="{{ auth()->id() }}">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Judul Kelas</label>
                                <input type="text" name="title" required placeholder="Contoh: Matematika Kelas 7 — Persiapan UTS"
                                    class="gh-app-input w-full">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi</label>
                                <textarea name="description" rows="3" required placeholder="Tujuan belajar, materi utama, dan target siswa..."
                                    class="gh-app-input w-full min-h-[88px]"></textarea>
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Harga (IDR)</label>
                                    <input type="number" name="price" required min="0" placeholder="0 = gratis" class="gh-app-input w-full">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                                    <select name="status" required class="gh-app-select w-full">
                                        <option value="draft" selected>Draft</option>
                                        <option value="published">Published</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Cover Kelas (opsional)</label>
                                <input type="file" name="cover_image" accept="image/*" class="gh-app-input w-full text-xs file:mr-3 file:rounded-md file:border-0 file:bg-indigo-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-indigo-700">
                            </div>
                        </div>
                        <div class="flex flex-col-reverse gap-2 border-t border-gray-100 bg-gray-50 px-5 py-4 sm:flex-row sm:justify-end">
                            <button type="button" onclick="toggleModal('addCourseModal')" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm">Batal</button>
                            <button type="submit" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">Simpan Kelas</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="editCourseModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" role="dialog" aria-modal="true">
            <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="toggleModal('editCourseModal')" aria-hidden="true"></div>
            <div class="relative z-10 flex min-h-full items-end justify-center p-4 pb-24 sm:items-center sm:pb-4">
                <div class="max-h-[90vh] w-full max-w-xl overflow-y-auto rounded-xl border border-gray-100 bg-white shadow-xl" onclick="event.stopPropagation()">
                    <form id="editForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="border-b border-gray-100 px-5 py-4">
                            <h3 class="text-base font-bold text-gray-900">Edit Kelas</h3>
                        </div>
                        <div class="space-y-4 px-5 py-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Jenjang & Mata Pelajaran</label>
                                <x-course.subject-select :subjects="$subjects" id="edit_subject_id" />
                            </div>
                            <input type="hidden" name="teacher_id" value="{{ auth()->id() }}">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Judul Kelas</label>
                                <input type="text" id="edit_title" name="title" required class="gh-app-input w-full">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi</label>
                                <textarea id="edit_description" name="description" rows="3" required class="gh-app-input w-full min-h-[88px]"></textarea>
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Harga (IDR)</label>
                                    <input type="number" id="edit_price" name="price" required min="0" class="gh-app-input w-full">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Status</label>
                                    <select id="edit_status" name="status" required class="gh-app-select w-full">
                                        <option value="draft">Draft</option>
                                        <option value="published">Published</option>
                                        <option value="archived">Archived</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Ganti Cover (opsional)</label>
                                <input type="file" name="cover_image" accept="image/*" class="gh-app-input w-full text-xs file:mr-3 file:rounded-md file:border-0 file:bg-indigo-50 file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-indigo-700">
                            </div>
                        </div>
                        <div class="flex flex-col-reverse gap-2 border-t border-gray-100 bg-gray-50 px-5 py-4 sm:flex-row sm:justify-end">
                            <button type="button" onclick="toggleModal('editCourseModal')" class="gh-app-btn gh-app-btn-secondary gh-app-btn-sm">Batal</button>
                            <button type="submit" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">Perbarui Kelas</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script>
        function toggleModal(modalId) {
            document.getElementById(modalId).classList.toggle('hidden');
        }

        function handleOpenEditModal(button) {
            openEditModal(JSON.parse(button.getAttribute('data-course')));
        }

        function openEditModal(course) {
            document.getElementById('edit_subject_id').value = course.subject_id || '';
            document.getElementById('edit_title').value = course.title;
            document.getElementById('edit_description').value = course.description;
            document.getElementById('edit_price').value = Math.round(course.price);
            document.getElementById('edit_status').value = course.status;
            document.getElementById('editForm').action = `/courses/${course.id}`;
            toggleModal('editCourseModal');
        }
    </script>
@endsection
