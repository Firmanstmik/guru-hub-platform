@extends('layout.master-app')
@section('content')
    <div class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner">
            <x-app.page-header title="Katalog Kursus" subtitle="Temukan kelas dari pengajar terbaik.">
                <x-slot:action>
                    <a href="/tampil-kursus" class="gh-app-btn gh-app-btn-ghost gh-app-btn-sm hidden">Filter</a>
                </x-slot:action>
            </x-app.page-header>

            <div class="gh-app-card space-y-3">
                <div class="gh-app-search">
                    <x-ui.lucide name="search" class="gh-app-search-icon" />
                    <input type="text" id="js-search-input" placeholder="Cari kelas atau guru..."
                        class="gh-app-input">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <select id="js-category-filter" class="gh-app-select">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <select id="js-teacher-filter" class="gh-app-select">
                        <option value="">Semua Guru</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="course-container" class="space-y-3">
                @forelse($courses as $course)
                    <div class="course-card gh-app-course-card"
                        data-title="{{ strtolower($course->title) }}"
                        data-category-id="{{ $course->category_id ?? '' }}"
                        data-teacher-id="{{ $course->teacher_id ?? '' }}"
                        data-teacher-name="{{ strtolower($course->teacher->name ?? '') }}">
                        <div class="gh-app-course-thumb">
                            <x-app.cover-image :src="$course->cover_image" type="course" :alt="$course->title" />
                            <x-app.badge variant="info" class="absolute left-3 top-3">{{ $course->category->name ?? 'Umum' }}</x-app.badge>
                        </div>
                        <div class="gh-app-course-body">
                            <h3 class="gh-app-subheading line-clamp-2">{{ $course->title }}</h3>
                            <p class="gh-app-caption line-clamp-2">{{ $course->description }}</p>
                            <div class="flex items-center gap-2 pt-1">
                                @if ($course->teacher)
                                    <x-app.user-avatar :user="$course->teacher" size="sm" />
                                @else
                                    <img src="{{ asset('assets/avatar/default-guru.png') }}" alt="Instruktur"
                                        class="gh-app-user-photo gh-app-user-photo--sm gh-app-user-photo--ring gh-app-user-photo--guru">
                                @endif
                                <span class="gh-app-caption">{{ $course->teacher->name ?? 'Instruktur' }}</span>
                            </div>
                            <div class="flex items-center justify-between pt-2">
                                <div>
                                    <p class="gh-app-caption">Harga</p>
                                    <p class="gh-app-subheading">
                                        @if ($course->price > 0)
                                            Rp {{ number_format($course->price, 0, ',', '.') }}
                                        @else
                                            <span class="text-emerald-600">GRATIS</span>
                                        @endif
                                    </p>
                                </div>
                                <a href="/bookings/create?course_id={{ $course->id }}" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm">
                                    Daftar <x-ui.lucide name="arrow-right" class="h-3.5 w-3.5" />
                                </a>
                            </div>
                            <p class="gh-app-caption">{{ $course->materials_count }} modul · {{ $course->students_count }} siswa</p>
                        </div>
                    </div>
                @empty
                    <x-app.empty-state id="js-empty-state-server" icon="library" title="Belum ada kelas" description="Katalog kursus masih kosong." />
                @endforelse
                <x-app.empty-state id="js-empty-state-client" icon="search" title="Tidak ditemukan" description="Coba ubah kata kunci atau filter." class="hidden" />
            </div>

            <div class="gh-app-card">{{ $courses->links() }}</div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('js-search-input');
            const categoryFilter = document.getElementById('js-category-filter');
            const teacherFilter = document.getElementById('js-teacher-filter');
            const courseCards = document.querySelectorAll('.course-card');
            const clientEmptyState = document.getElementById('js-empty-state-client');

            function filterCourses() {
                const searchValue = searchInput.value.toLowerCase().trim();
                const selectedCategoryId = categoryFilter.value;
                const selectedTeacherId = teacherFilter.value;
                let visibleCount = 0;

                courseCards.forEach(card => {
                    const cardTitle = card.getAttribute('data-title');
                    const cardCategoryId = card.getAttribute('data-category-id');
                    const cardTeacherId = card.getAttribute('data-teacher-id');
                    const cardTeacherName = card.getAttribute('data-teacher-name');
                    const matchesSearch = cardTitle.includes(searchValue) || cardTeacherName.includes(searchValue);
                    const matchesCategory = selectedCategoryId === "" || cardCategoryId === selectedCategoryId;
                    const matchesTeacher = selectedTeacherId === "" || cardTeacherId === selectedTeacherId;

                    if (matchesSearch && matchesCategory && matchesTeacher) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (visibleCount === 0 && courseCards.length > 0) {
                    clientEmptyState.classList.remove('hidden');
                } else {
                    clientEmptyState.classList.add('hidden');
                }
            }

            searchInput.addEventListener('input', filterCourses);
            categoryFilter.addEventListener('change', filterCourses);
            teacherFilter.addEventListener('change', filterCourses);
        });
    </script>
@endsection
