@props([
    'href' => '#',
    'icon' => '📚',
    'name' => '',
    'meta' => null,
])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'gh-browse-cat-card group']) }}>
    <span class="gh-browse-cat-icon" aria-hidden="true">{{ $icon }}</span>
    <span class="gh-browse-cat-name">{{ $name }}</span>
    @if ($meta)
        <span class="gh-browse-cat-meta">{{ $meta }}</span>
    @endif
</a>
