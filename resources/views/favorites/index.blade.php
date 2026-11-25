@extends('layouts.master')

@section('title', 'Os meus favoritos')

@section('content')


<div class="opus-favorites">

    <header class="opus-favorites-header">

        <span class="opus-favorites-eyebrow">
            A minha biblioteca
        </span>

        <h1>Os meus favoritos</h1>

        <p class="opus-favorites-description">
            Guarda os compositores e as obras que mais te inspiram
            e constrói a tua biblioteca musical pessoal.
        </p>

        <div class="opus-favorites-summary">

            <span class="opus-favorites-count">
                {{ $composerFavorites->count() }}
                {{ $composerFavorites->count() === 1
                    ? 'compositor favorito'
                    : 'compositores favoritos' }}
            </span>

            <span class="opus-favorites-count">
                {{ $favorites->total() }}
                {{ $favorites->total() === 1
                    ? 'obra favorita'
                    : 'obras favoritas' }}
            </span>

        </div>

    </header>



    <section class="opus-favorites-section">

        <header class="opus-favorites-section-header">

            <span class="opus-favorites-eyebrow">
                01 / Compositores
            </span>

            <h2>Compositores favoritos</h2>

            <p class="muted">
                Os compositores que escolheste acompanhar.
            </p>

        </header>

        @if($composerFavorites->isNotEmpty())

            <div class="opus-favorites-grid">

                @foreach($composerFavorites as $composer)

                    <article class="opus-favorite-card">

                        <div class="opus-favorite-image composer-image">

                            @if($composer->image)

                                <img
                                    src="{{ asset('storage/' . $composer->image) }}"
                                    alt="Retrato de {{ $composer->name }}"
                                    loading="lazy"
                                >

                            @else

                                <span
                                    class="opus-favorite-placeholder"
                                    aria-hidden="true"
                                >
                                    ♫
                                </span>

                            @endif

                        </div>

                        <div class="opus-favorite-body">

                            <h3 class="opus-favorite-title">
                                {{ $composer->name }}
                            </h3>

                            <p class="opus-favorite-composer">
                                {{ $composer->period ?? 'Período não indicado' }}
                                ·
                                {{ $composer->nationality ?? 'Nacionalidade não indicada' }}
                            </p>

                            <div class="opus-favorite-actions">

                                <a
                                    href="{{ route('composers.show', $composer) }}"
                                    class="opus-favorite-button opus-favorite-view"
                                >
                                    Ver compositor
                                </a>

                                <form
                                    action="{{ route('composer-favorites.destroy', $composer) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="opus-favorite-button opus-favorite-remove"
                                    >
                                        ♥ Remover
                                    </button>

                                </form>

                            </div>

                        </div>

                    </article>

                @endforeach

            </div>

        @else

            <div class="opus-favorites-empty">

                <div
                    class="opus-favorites-empty-icon"
                    aria-hidden="true"
                >
                    ♡
                </div>

                <h3>Ainda não tens compositores favoritos</h3>

                <p>
                    Explora o catálogo e guarda os compositores
                    que mais gostas.
                </p>

                <a
                    href="{{ route('composers.index') }}"
                    class="opus-favorite-button opus-favorite-view"
                >
                    Explorar compositores
                </a>

            </div>

        @endif

    </section>



    <section class="opus-favorites-section">

        <header class="opus-favorites-section-header">

            <span class="opus-favorites-eyebrow">
                02 / Repertório
            </span>

            <h2>Obras favoritas</h2>

            <p class="muted">
                A tua seleção pessoal de composições musicais.
            </p>

        </header>

        @if($favorites->count() > 0)

            <div class="opus-favorites-grid">

                @foreach($favorites as $composition)

                    <article class="opus-favorite-card">

                        <div class="opus-favorite-image">

                            @if($composition->image)

                                <img
                                    src="{{ asset('storage/' . $composition->image) }}"
                                    alt="Partitura de {{ $composition->title }}"
                                    loading="lazy"
                                >

                            @else

                                <span
                                    class="opus-favorite-placeholder"
                                    aria-hidden="true"
                                >
                                    ♫
                                </span>

                            @endif

                        </div>

                        <div class="opus-favorite-body">

                            <h3 class="opus-favorite-title">
                                {{ $composition->title }}
                            </h3>

                            <p class="opus-favorite-composer">
                                {{ $composition->composer?->name
                                    ?? 'Compositor desconhecido' }}
                            </p>

                            <div class="opus-favorite-actions">

                                <a
                                    href="{{ route('compositions.show', $composition) }}"
                                    class="opus-favorite-button opus-favorite-view"
                                >
                                    Ver obra
                                </a>

                                <form
                                    action="{{ route('favorites.destroy', $composition) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="opus-favorite-button opus-favorite-remove"
                                    >
                                        ♥ Remover
                                    </button>

                                </form>

                            </div>

                        </div>

                    </article>

                @endforeach

            </div>

            @if($favorites->hasPages())
                <div class="opus-favorites-pagination">
                    {{ $favorites->links() }}
                </div>
            @endif

        @else

            <div class="opus-favorites-empty">

                <div
                    class="opus-favorites-empty-icon"
                    aria-hidden="true"
                >
                    ♡
                </div>

                <h3>Ainda não tens obras favoritas</h3>

                <p>
                    Descobre novas composições
                    e começa a construir o teu repertório pessoal.
                </p>

                <a
                    href="{{ route('composers.index') }}"
                    class="opus-favorite-button opus-favorite-view"
                >
                    Explorar catálogo
                </a>

            </div>

        @endif

    </section>

</div>

@endsection
