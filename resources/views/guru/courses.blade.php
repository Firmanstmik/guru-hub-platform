@extends('layout.master-app')
@section('content')
    <div class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner">
            <x-app.page-header title="Kelola Kelas" subtitle="Program pembelajaran aktif, draf, dan arsip." />

            <form action="/courses" method="GET" class="gh-app-filter-bar">
                <select name="category_id" onchange="this.form.submit()" class="gh-app-select flex-1">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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
                                <x-app.badge variant="info">{{ $course->category->name }}</x-app.badge>
                            </div>
                            <p class="gh-app-caption line-clamp-2">{{ $course->description }}</p>
                            <p class="gh-app-caption mt-1">
                                @role('admin'){{ $course->teacher->name }} · @endrole
                                {{ $course->students_count }} siswa · Rp {{ number_format($course->price, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="shrink-0 text-right">
                            @if ($course->status === 'published')
                                <x-app.badge variant="success">Published</x-app.badge>
                            @elseif($course->status === 'draft')
                                <x-app.badge variant="warning">Draft</x-app.badge>
                            @else
                                <x-app.badge variant="neutral">Archived</x-app.badge>
                            @endif
                        </div>
                    </div>
                @empty
                    <x-app.empty-state icon="book-open" title="Kelas tidak ditemukan" description="Tidak ada program yang sesuai filter." />
                @endforelse
            </div>

            @if ($courses->hasPages())
                <div class="gh-app-card">{{ $courses->links() }}</div>
            @endif
        </div>
    </div>
@endsection
