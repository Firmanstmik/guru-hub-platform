@props(['steps' => []])

<nav class="gh-browse-breadcrumb" aria-label="Breadcrumb">
    <ol>
        @foreach ($steps as $i => $step)
            <li class="inline-flex items-center gap-1.5">
                @if ($i > 0)
                    <span class="gh-browse-breadcrumb-sep" aria-hidden="true">/</span>
                @endif
                @if (!empty($step['url']) && $i < count($steps) - 1)
                    <a href="{{ $step['url'] }}">{{ $step['label'] }}</a>
                @else
                    <span @if ($i === count($steps) - 1) aria-current="page" @endif>{{ $step['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
