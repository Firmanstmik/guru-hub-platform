@props([
    'eyebrow' => null,
    'title',
    'subtitle' => null,
    'back' => null,
    'premium' => true,
])

<div @class(['gh-app-page-header-premium' => $premium])>
    <div {{ $attributes->merge(['class' => 'gh-app-section-head']) }}>
        <div class="min-w-0 flex-1">
            @if ($back)
                <a href="{{ $back }}" class="gh-app-btn gh-app-btn-ghost mb-2 inline-flex !min-h-0 !px-0 text-[11px]">
                    <x-ui.lucide name="arrow-left" class="h-3.5 w-3.5" />
                    Kembali
                </a>
            @endif
            @if ($eyebrow)
                <p class="gh-app-eyebrow">{{ $eyebrow }}</p>
            @endif
            <h1 class="gh-app-heading-lg {{ $eyebrow ? 'mt-1.5' : '' }}">{{ $title }}</h1>
            @if ($subtitle)
                <p class="gh-app-body mt-1.5 max-w-prose">{{ $subtitle }}</p>
            @endif
        </div>
        @if (isset($action))
            <div class="shrink-0">{{ $action }}</div>
        @endif
    </div>
</div>
