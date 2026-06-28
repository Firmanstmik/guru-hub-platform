@props([
    'icon' => 'inbox',
    'title',
    'description' => null,
    'actionLabel' => null,
    'actionHref' => null,
])

<div {{ $attributes->merge(['class' => 'gh-app-empty']) }}>
    <div class="gh-app-empty-icon">
        <x-ui.lucide :name="$icon" class="h-6 w-6" />
    </div>
    <p class="gh-app-empty-title">{{ $title }}</p>
    @if ($description)
        <p class="gh-app-empty-text">{{ $description }}</p>
    @endif
    @if ($actionLabel && $actionHref)
        <a href="{{ $actionHref }}" class="gh-app-btn gh-app-btn-primary gh-app-btn-sm mt-4 inline-flex">
            {{ $actionLabel }}
        </a>
    @endif
    {{ $slot }}
</div>
