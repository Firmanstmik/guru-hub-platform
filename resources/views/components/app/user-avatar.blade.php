@props([
    'user' => null,
    'size' => 'md',
    'ring' => true,
])

@php
    $user = $user ?? auth()->user();
    $sizeMap = [
        'xs' => 'gh-app-user-photo--xs',
        'sm' => 'gh-app-user-photo--sm',
        'md' => 'gh-app-user-photo--md',
        'lg' => 'gh-app-user-photo--lg',
        'xl' => 'gh-app-user-photo--xl',
        '2xl' => 'gh-app-user-photo--2xl',
    ];
    $sizeClass = $sizeMap[$size] ?? $size;
    $isGuru = $user?->hasRole('guru') ?? false;
@endphp

@if ($user)
    <img
        src="{{ $user->avatarUrl() }}"
        alt="Foto {{ $user->name }}"
        {{ $attributes->class([
            'gh-app-user-photo',
            $sizeClass,
            'gh-app-user-photo--ring' => $ring,
            'gh-app-user-photo--guru' => $isGuru,
            'gh-app-user-photo--siswa' => $user->hasRole('siswa'),
        ]) }}
        loading="lazy"
        decoding="async"
    />
@endif
