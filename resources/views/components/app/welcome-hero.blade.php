@props([
    'eyebrow' => null,
    'title',
    'subtitle' => null,
])

@php
    $user = auth()->user();
    $isGuru = $user->hasRole('guru');
@endphp

<div {{ $attributes->class(['gh-app-welcome', $isGuru ? 'gh-app-welcome--guru' : 'gh-app-welcome--siswa']) }}>
    <div class="gh-app-welcome-glow" aria-hidden="true"></div>
    <div class="gh-app-welcome-inner">
        <div class="gh-app-welcome-copy">
            @if ($eyebrow)
                <p class="gh-app-welcome-eyebrow">{{ $eyebrow }}</p>
            @endif
            <h2 class="gh-app-welcome-title">{{ $title }}</h2>
            @if ($subtitle)
                <p class="gh-app-welcome-sub">{{ $subtitle }}</p>
            @endif
            @if (isset($action))
                <div class="gh-app-welcome-action">{{ $action }}</div>
            @endif
        </div>
        <x-app.user-avatar :user="$user" size="lg" class="gh-app-welcome-avatar" />
    </div>
</div>
