@props(['composition', 'large' => false])

@auth
    @php
        $saved = auth()->user()->favorites()
            ->whereKey($composition->id)->exists();
    @endphp

    <form method="POST" action="{{ $saved
        ? route('favorites.destroy', $composition)
        : route('favorites.store', $composition) }}">
        @csrf
        @if($saved)
            @method('DELETE')
        @endif

        <button type="submit" class="btn {{ $saved ? 'btn-secondary' : 'btn-primary' }} {{ $large ? '' : 'btn-sm' }}">
            @if($saved)
                {{ $large ? '♥ Remover dos favoritos' : '♥ Remover' }}
            @else
                {{ $large ? '♡ Adicionar aos favoritos' : '♡ Favorito' }}
            @endif
        </button>
    </form>
@else
    @if($large)
        <a class="btn btn-secondary" href="{{ route('login') }}">
            ♡ Iniciar sessão para guardar esta obra
        </a>
    @endif
@endauth
