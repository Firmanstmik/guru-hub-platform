@props(['steps' => []])

<nav class="gh-browse-breadcrumb" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-1.5 text-[0.8125rem]">
        @foreach ($steps as $i => $step)
            <li class="flex items-center gap-1.5">
                @if ($i > 0)
                    <span class="text-[#0A1A4F]/25" aria-hidden="true">/</span>
                @endif
                @if (!empty($step['url']) && $i < count($steps) - 1)
                    <a href="{{ $step['url'] }}" class="font-medium text-[#0E7490] hover:text-[#0A1A4F]">{{ $step['label'] }}</a>
                @else
                    <span class="font-semibold text-[#0A1A4F]">{{ $step['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
