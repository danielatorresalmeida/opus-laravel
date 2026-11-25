@props(['composer'])
<span {{ $attributes->class(['composer-meta']) }}>
    <span>{{ $composer->nationality ?? 'Nacionalidade não indicada' }}</span>
    @if ($composer->birth_year || $composer->death_year)
        <span class="meta-separator" aria-hidden="true">·</span>
        <span class="composer-years">
            @if ($composer->birth_year && $composer->death_year)
                <span>{{ $composer->birth_year }}</span><span class="year-divider">–</span><span>{{ $composer->death_year }}</span>
            @elseif ($composer->birth_year)
                <span>Nasc. {{ $composer->birth_year }}</span>
            @else
                <span>Fal. {{ $composer->death_year }}</span>
            @endif
        </span>
    @endif
</span>
