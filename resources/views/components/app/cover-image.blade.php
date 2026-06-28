@props([
    'src' => null,
    'type' => 'course',
    'alt' => '',
    'class' => 'h-full w-full object-cover',
])

<img
    src="{{ \App\Support\MediaDefaults::coverUrl($src, $type) }}"
    alt="{{ $alt }}"
    {{ $attributes->merge(['class' => $class]) }}
    loading="lazy"
    decoding="async"
/>
