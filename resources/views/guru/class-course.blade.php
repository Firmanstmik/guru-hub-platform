@extends('layout.master-app')
@section('content')
    <div class="gh-app-page">
        <div class="gh-app-page-grid" aria-hidden="true"></div>
        <div class="gh-app-page-inner">
            <x-app.page-header title="Semua Kelas" subtitle="Kategori fokus pembelajaran di platform." />

            <div class="gh-app-list">
                @forelse($categories as $category)
                    <div class="gh-app-list-item">
                    <div class="gh-app-list-thumb">
                        <x-app.cover-image type="category" :alt="$category->name" />
                    </div>
                        <div class="gh-app-list-body">
                            <h3 class="gh-app-list-title">{{ $category->name }}</h3>
                            <p class="gh-app-caption font-mono">{{ $category->slug }}</p>
                            <p class="gh-app-body mt-1 line-clamp-2">{{ $category->description ?? 'Belum ada deskripsi pelengkap untuk kategori ini.' }}</p>
                        </div>
                        <x-app.badge variant="info">{{ $category->courses_count }} Kelas</x-app.badge>
                    </div>
                @empty
                    <x-app.empty-state icon="folder" title="Data kategori kosong" description="Belum ada kategori terdaftar di platform Guru Hub." />
                @endforelse
            </div>

            @if ($categories->hasPages())
                <div class="gh-app-card">{{ $categories->links() }}</div>
            @endif
        </div>
    </div>
@endsection
