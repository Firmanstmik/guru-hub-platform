@props([
    'icon' => 'book-open',
    'from' => '#0E7490',
    'to' => '#22D3EE',
    'size' => 'md',
])

@php
    $sizes = [
        'sm' => ['box' => 'h-9 w-9', 'icon' => 'h-4 w-4'],
        'md' => ['box' => 'h-11 w-11', 'icon' => 'h-5 w-5'],
        'lg' => ['box' => 'h-14 w-14', 'icon' => 'h-6 w-6'],
    ];
    $s = $sizes[$size] ?? $sizes['md'];
@endphp

<span {{ $attributes->merge(['class' => 'gh-ref-cat-icon grid place-items-center rounded-2xl ' . $s['box']]) }}
    style="background:linear-gradient(135deg,{{ $from }},{{ $to }});box-shadow:0 10px 24px -14px {{ $from }}88">
    <x-ui.lucide :name="$icon" :class="$s['icon'] . ' text-white'" />
</span>
