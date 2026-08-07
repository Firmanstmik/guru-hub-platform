@props([
    'class' => '',
    'title' => 'Keluar',
])

<form method="POST" action="{{ url('/logout') }}" {{ $attributes->merge(['class' => 'inline']) }}>
    @csrf
    <button type="submit" class="{{ $class }}" title="{{ $title }}" aria-label="{{ $title }}">
        {{ $slot }}
    </button>
</form>
