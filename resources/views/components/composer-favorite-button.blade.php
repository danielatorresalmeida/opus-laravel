@props(['composer'])

@auth
    @php
        $saved = auth()->user()->favoriteComposers()
            ->whereKey($composer->id)->exists();
    @endphp

    <form method="POST" action="{{ $saved
        ? route('composer-favorites.destroy', $composer)
        : route('composer-favorites.store', $composer) }}">
        @csrf
        @if($saved)
            @method('DELETE')
        @endif

        <button type="submit" class="btn {{ $saved ? 'btn-secondary' : 'btn-primary' }} btn-sm"
                aria-label="{{ $saved ? 'Remover' : 'Guardar' }} {{ $composer->name }} {{ $saved ? 'dos' : 'nos' }} favoritos">
            {{ $saved ? '♥ Guardado' : '♡ Guardar' }}
        </button>
    </form>
@else
    <a class="btn btn-secondary btn-sm" href="{{ route('login') }}"
       aria-label="Iniciar sessão para guardar {{ $composer->name }}">
        ♡ Guardar
    </a>
@endauth
