@extends('layout.master')
@section('title', 'Pilih Jenjang — ' . $category->name . ' | GuruHub')
@section('flush', true)

@php
    $meta = \App\Support\CategoryIcons::meta($category->slug);
@endphp

@section('content')
<div class="gh-landing-page gh-browse-page gh-browse-shell">
    <div class="gh-browse-shell-grid" aria-hidden="true"></div>

    <section class="gh-ref-section gh-browse-hero">
        <div class="gh-ref-container relative">
            <x-browse.breadcrumb :steps="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => $category->name],
            ]" />

            <div class="gh-browse-head gh-browse-head--wide mt-8">
                <x-browse.category-icon
                    :icon="$meta['icon']"
                    :from="$meta['from']"
                    :to="$meta['to']"
                    size="lg"
                    class="mx-auto"
                />
                <p class="gh-ref-eyebrow mt-5">Pilih jenjang</p>
                <h1 class="gh-ref-display mt-3 text-[1.75rem] sm:text-4xl">{{ $category->name }}</h1>
                <p class="gh-ref-muted mt-3 text-[0.9375rem]">{{ $meta['tagline'] }}</p>
            </div>

            <div class="mt-10">
                <div class="gh-browse-section-title max-w-3xl mx-auto">
                    <h2>Jenjang pendidikan</h2>
                    <span>{{ $levels->count() }} pilihan</span>
                </div>

                <div class="mx-auto mt-4 grid max-w-3xl grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5 sm:gap-4">
                    @foreach ($levels as $level)
                        <a href="{{ route('browse.subjects', ['category' => $category->slug, 'level' => $level->slug]) }}"
                            class="gh-browse-level-card">
                            <span class="gh-browse-level-icon">
                                <x-ui.lucide :name="\App\Support\CategoryIcons::levelIcon($level->slug)" class="h-5 w-5 text-[#0E7490]" />
                            </span>
                            <span class="mt-2.5 block text-[0.875rem] font-bold leading-snug text-[#0A1A4F] sm:text-[0.9375rem]">{{ $level->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
