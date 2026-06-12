@extends('layout.master-app')
@section('content')
    <div class="p-6 max-w-7xl mx-auto space-y-6">

        <div class="bg-white p-4 rounded-2xl shadow-xs border border-gray-100">
            {{-- Mengubah grid sistem agar proporsional setelah tombol reset dihapus --}}
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">

                <div class="relative w-full md:col-span-6">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text" id="js-search-input" placeholder="Cari judul kelas atau nama guru pengajar..."
                        class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                </div>

                <div class="w-full md:col-span-3">
                    <select id="js-category-filter"
                        class="w-full px-3 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-gray-700">
                        <option value="">-- Semua Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ strtolower($category->name) }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:col-span-3">
                    <select id="js-teacher-filter"
                        class="w-full px-3 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-hidden focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-gray-700">
                        <option value="">-- Semua Guru Pengajar --</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ strtolower($teacher->name) }}">
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        <div id="course-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $course)
                {{-- TAMBAHAN: Menambahkan atribut data-category --}}
                <div class="course-card bg-white rounded-2xl shadow-xs border border-gray-100 overflow-hidden hover:shadow-md transition duration-300 flex flex-col h-full"
                    data-title="{{ strtolower($course->title) }}"
                    data-category="{{ strtolower($course->category->name ?? 'umum') }}"
                    data-teacher="{{ strtolower($course->teacher->name ?? '') }}">

                    <div class="relative h-48 bg-gray-100">
                        <img src="{{ $course->cover_image ? asset('storage/' . $course->cover_image) : 'https://placehold.co/600x400?text=No+Image' }}"
                            alt="Cover {{ $course->title }}" class="w-full h-full object-cover">

                        <span
                            class="absolute top-3 left-3 px-2.5 py-1 text-xs font-semibold bg-white/90 backdrop-blur-xs text-indigo-600 rounded-lg shadow-xs">
                            {{ $course->category->name ?? 'Umum' }}
                        </span>
                    </div>

                    <div class="p-5 flex flex-col flex-grow">
                        <h3
                            class="font-bold text-gray-900 text-lg leading-snug line-clamp-2 hover:text-indigo-600 transition mb-2">
                            {{ $course->title }}
                        </h3>

                        <p class="text-xs text-gray-500 line-clamp-2 mb-4 flex-grow">
                            {{ $course->description }}
                        </p>

                        <div class="space-y-2.5 border-t border-b border-gray-50 py-3.5 mb-4">
                            <div class="flex items-center gap-2.5 text-sm text-gray-600">
                                <div
                                    class="w-6 h-6 rounded-full bg-indigo-50 flex items-center justify-center font-semibold text-xs text-indigo-600 uppercase">
                                    {{ substr($course->teacher->name ?? 'G', 0, 1) }}
                                </div>
                                <span class="truncate font-medium">
                                    <span class="text-xs text-gray-400 block -mb-0.5">Guru Pengajar</span>
                                    {{ $course->teacher->name ?? 'Instruktur Anonim' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between text-xs text-gray-500 pt-1">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0 -13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332 0.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332 0.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332 0.477-4.5 1.253" />
                                    </svg>
                                    <span>{{ $course->materials_count }} Modul</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span>{{ $course->students_count }} Siswa</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-auto">
                            <div>
                                <span
                                    class="text-[10px] uppercase tracking-wider font-semibold text-gray-400 block -mb-0.5">Pembayaran
                                    Belajar</span>
                                <span class="text-base font-bold text-gray-900">
                                    @if ($course->price > 0)
                                        Rp {{ number_format($course->price, 0, ',', '.') }}
                                    @else
                                        <span class="text-emerald-600 font-extrabold">GRATIS</span>
                                    @endif
                                </span>
                            </div>

                            <a href="/bookings/create?course_id={{ $course->id }}"
                                class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-xs hover:shadow-sm transition duration-150">
                                Daftar Kelas
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div id="js-empty-state-server"
                    class="col-span-full bg-gray-50 border border-dashed rounded-2xl p-12 text-center text-gray-500">
                    Tidak ditemukan kelas aktif saat ini.
                </div>
            @endforelse

            <div id="js-empty-state-client"
                class="hidden col-span-full bg-gray-50 border border-dashed rounded-2xl p-12 text-center text-gray-500">
                Tidak ditemukan kelas yang cocok dengan pencarian atau filter Anda.
            </div>
        </div>

        <div class="mt-6">
            {{ $courses->links() }}
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('js-search-input');
            const categoryFilter = document.getElementById('js-category-filter'); // Definisi filter kategori
            const teacherFilter = document.getElementById('js-teacher-filter');
            const courseCards = document.querySelectorAll('.course-card');
            const clientEmptyState = document.getElementById('js-empty-state-client');

            // Fungsi Utama Filter Real-time (Mendukung 3 filter bersamaan)
            function filterCourses() {
                const searchValue = searchInput.value.toLowerCase().trim();
                const selectedCategory = categoryFilter.value; // Ambil value kategori terpilih
                const selectedTeacher = teacherFilter.value;
                let visibleCount = 0;

                courseCards.forEach(card => {
                    const cardTitle = card.getAttribute('data-title');
                    const cardCategory = card.getAttribute('data-category');
                    const cardTeacher = card.getAttribute('data-teacher');

                    // Cek kecocokan masing-masing filter
                    const matchesSearch = cardTitle.includes(searchValue) || cardTeacher.includes(searchValue);
                    const matchesCategory = selectedCategory === "" || cardCategory === selectedCategory;
                    const matchesTeacher = selectedTeacher === "" || cardTeacher === selectedTeacher;

                    // Harus memenuhi ketiga kondisi filter sekaligus
                    if (matchesSearch && matchesCategory && matchesTeacher) {
                        card.style.display = 'flex';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Atur tampilan empty state client jika hasil filter nihil
                if (visibleCount === 0 && courseCards.length > 0) {
                    clientEmptyState.classList.remove('hidden');
                } else {
                    clientEmptyState.classList.add('hidden');
                }
            }

            // Daftarkan event listener untuk input ketikan & perubahan seleksi dropdown
            searchInput.addEventListener('input', filterCourses);
            categoryFilter.addEventListener('change', filterCourses); // Event untuk kategori
            teacherFilter.addEventListener('change', filterCourses);
        });
    </script>
@endsection