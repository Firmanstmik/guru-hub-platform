@extends('layout.master')
@section('title', $subject->name . ' — ' . $category->name . ' | GuruHub')
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
                ['label' => $level->name, 'url' => route('browse.subjects', ['category' => $category->slug, 'level' => $level->slug])],
                ['label' => $subject->name],
            ]" />

            <div class="gh-browse-head mt-8">
                <x-browse.category-icon
                    :icon="$meta['icon']"
                    :from="$meta['from']"
                    :to="$meta['to']"
                    size="lg"
                    class="mx-auto"
                />
                <p class="gh-ref-eyebrow mt-5">Pengajar tersedia</p>
                <h1 class="gh-ref-display mt-3 text-[1.75rem] sm:text-4xl">{{ $subject->name }}</h1>
                <p class="gh-ref-muted mt-3 text-[0.9375rem]">{{ $level->name }} · {{ $category->name }}</p>
            </div>

            <div class="mt-10">
                <div class="gh-browse-section-title">
                    <h2>Pilih pengajar</h2>
                    <span>{{ $teachers->count() }} tersedia</span>
                </div>

                <div class="space-y-3">
                    @forelse ($teachers as $item)
                        <a href="{{ route('browse.teacher', ['category' => $category->slug, 'level' => $level->slug, 'subject' => $subject->slug, 'teacher' => $item->user->id]) }}"
                            class="gh-browse-teacher-card">
                            <x-app.user-avatar :user="$item->user" size="lg" :ring="true" />
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-[0.9375rem] font-bold text-[#0A1A4F]">{{ $item->user->name }}</span>
                                <span class="mt-0.5 block text-[0.75rem] text-[#0A1A4F]/55">
                                    {{ $item->course_count }} kursus · {{ number_format($item->students_total) }} siswa · {{ number_format($item->rating, 1) }} ★
                                </span>
                            </span>
                            <x-ui.lucide name="arrow-right" class="h-4 w-4 shrink-0 text-[#0E7490]" />
                        </a>
                    @empty
                        <div class="gh-browse-empty">
                            <div class="gh-browse-empty-icon">
                                <x-ui.lucide name="users" class="h-6 w-6" />
                            </div>
                            <p class="text-[0.9375rem] font-semibold text-[#0A1A4F]">Belum ada pengajar untuk mapel ini</p>
                            <p class="gh-ref-muted mt-2 text-[0.8125rem]">Daftar sebagai siswa dan kami akan kabari saat kursus tersedia.</p>
                            <a href="{{ url('register/student') }}" class="gh-browse-btn mt-5 inline-flex">Daftar gratis</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
