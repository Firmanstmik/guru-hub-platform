@props([
    'name',
    'class' => 'h-4 w-4 shrink-0',
    'variant' => 'linear',
])

<x-ui.iconsax :name="$name" :class="$class" :variant="$variant" {{ $attributes }} />
