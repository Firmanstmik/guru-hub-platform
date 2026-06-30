@extends('layout.master')
@section('title', 'Pilih Jenjang — ' . $category->name . ' | GuruHub')
@section('flush', true)

@php
    $meta = \App\Support\CategoryIcons::meta($category->slug);
@endphp

@section('content')
<div class="gh-landing-page gh-browse-page min-h-screen">
    <section class="gh-ref-section gh-browse-hero">
        <div class="gh-ref-container relative">
            <x-browse.breadcrumb :steps="[
                ['label' => 'Beranda', 'url' => url('/')],
                ['label' => $category->name],
            ]" />

            <div class="gh-browse-head mt-6 max-w-2xl mx-auto text-center">
                <x-browse.category-icon
                    :icon="$meta['icon']"
                    :from="$meta['from']"
                    :to="$meta['to']"
                    size="lg"
                    class="mx-auto"
                />
                <p class="gh-ref-eyebrow mt-4">Pilih jenjang</p>
                <h1 class="gh-ref-display mt-3 text-[1.75rem] sm:text-4xl">{{ $category->name }}</h1>
                <p class="gh-ref-muted mt-3 text-[0.9375rem]">{{ $meta['tagline'] }}</p>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5 sm:gap-4">
                @foreach ($levels as $level)
                    <a href="{{ route('browse.subjects', ['category' => $category->slug, 'level' => $level->slug]) }}"
                        class="gh-browse-level-card">
                        <span class="gh-browse-level-icon">
                            <x-ui.lucide :name="\App\Support\CategoryIcons::levelIcon($level->slug)" class="h-5 w-5 text-[#0E7490]" />
                        </span>
                        <span class="mt-2 block text-[0.9375rem] font-bold text-[#0A1A4F]">{{ $level->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
