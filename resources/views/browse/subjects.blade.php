@extends('layout.master')
@section('title', $level->name . ' — ' . $category->name . ' | GuruHub')
@section('flush', true)

@php
    $meta = \App\Support\CategoryIcons::meta($category->slug);
@endphp

@section('content')
<div class="gh-landing-page gh-browse-page gh-browse-shell">
    <div class="gh-browse-shell-grid" aria-hidden="true"></div>

    <section class="gh-ref-section gh-browse-hero">
        <div class="gh-ref-container relative max-w-2xl">
            <x-browse.breadcrumb :steps="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => $category->name, 'url' => route('browse.category', $category->slug)],
                ['label' => $level->name],
            ]" />

            <div class="gh-browse-head mt-8">
                <x-browse.category-icon
                    :icon="$meta['icon']"
                    :from="$meta['from']"
                    :to="$meta['to']"
                    size="lg"
                    class="mx-auto"
                />
                <p class="gh-ref-eyebrow mt-5">Pilih mata pelajaran</p>
                <h1 class="gh-ref-display mt-3 text-[1.75rem] sm:text-4xl">{{ $category->name }}</h1>
                <p class="gh-ref-muted mt-3 text-[0.9375rem]">{{ $level->name }} — pilih mapel untuk melihat kursus dan pengajar.</p>
            </div>

            <div class="mt-10">
                <div class="gh-browse-section-title">
                    <h2>Mata pelajaran</h2>
                    <span>{{ $subjects->count() }} mapel</span>
                </div>

                <div class="grid grid-cols-1 gap-3">
                    @forelse ($subjects as $subject)
                        <a href="{{ route('browse.teachers', ['category' => $category->slug, 'level' => $level->slug, 'subject' => $subject->slug]) }}"
                            class="gh-browse-level-card gh-browse-level-card--row">
                            <span class="gh-browse-level-icon shrink-0">
                                <x-ui.lucide name="book-open" class="h-5 w-5 text-[#0E7490]" />
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-[0.9375rem] font-bold text-[#0A1A4F]">{{ $subject->name }}</span>
                                @if ($subject->courses_count > 0)
                                    <span class="mt-0.5 block text-[0.75rem] text-[#0A1A4F]/45">{{ $subject->courses_count }} kursus tersedia</span>
                                @else
                                    <span class="mt-0.5 block text-[0.75rem] text-[#0A1A4F]/45">Segera hadir</span>
                                @endif
                            </span>
                            <x-ui.lucide name="arrow-right" class="h-4 w-4 shrink-0 text-[#0E7490]" />
                        </a>
                    @empty
                        <div class="gh-browse-empty">
                            <div class="gh-browse-empty-icon">
                                <x-ui.lucide name="book-open" class="h-6 w-6" />
                            </div>
                            <p class="text-[0.9375rem] font-semibold text-[#0A1A4F]">Belum ada mata pelajaran</p>
                            <p class="gh-ref-muted mt-2 text-[0.8125rem]">Kami sedang menyiapkan konten untuk jenjang ini.</p>
                            <a href="{{ url('register/student') }}" class="gh-browse-btn mt-5 inline-flex">Daftar untuk notifikasi</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
