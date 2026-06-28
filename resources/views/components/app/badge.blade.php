@props([
    'variant' => 'neutral',
])

@php
    $classes = match ($variant) {
        'success' => 'gh-app-badge gh-app-badge--success',
        'warning' => 'gh-app-badge gh-app-badge--warning',
        'danger' => 'gh-app-badge gh-app-badge--danger',
        'info' => 'gh-app-badge gh-app-badge--info',
        default => 'gh-app-badge gh-app-badge--neutral',
    };
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
