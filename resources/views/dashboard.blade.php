
@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

    <section class="hero">
        <div class="hero-content">

            <span class="eyebrow">Área pessoal</span>

            <h1>Olá, {{ auth()->user()->name }}!</h1>

            <p>
                Bem-vindo à sua biblioteca musical.
                Explore o catálogo, descubra novas obras
                e organize os seus favoritos.
            </p>

        </div>

        <img
            class="hero-mark"
            src="{{ asset('images/opus-mark.svg') }}"
            alt=""
        >
    </section>

    @isset($stats)
        <div class="stats">

            <div class="stat">
                <strong>{{ $stats['composers'] }}</strong>
                <span>Compositores</span>
            </div>

            <div class="stat">
                <strong>{{ $stats['compositions'] }}</strong>
                <span>Obras</span>
            </div>

            <div class="stat">
                <strong>{{ $stats['periods'] }}</strong>
                <span>Períodos representados</span>
            </div>

        </div>
    @endisset

    <div class="section-head">
        <div>
            <span class="eyebrow">Acesso rápido</span>
            <h2>O seu espaço</h2>
        </div>
    </div>

    <div class="cards">

        <section class="panel">

            <h3>Catálogo musical</h3>

            <p class="muted">
                Explore compositores, descubra obras
                e consulte partituras e fontes musicais.
            </p>

            <a
                class="btn btn-primary"
                href="{{ route('composers.index') }}"
            >
                Abrir catálogo
            </a>

        </section>

        @can('manage-system')
            <section class="panel">

                <h3>Administração</h3>

                <p class="muted">
                    Adicione novos compositores e obras
                    e mantenha o catálogo atualizado.
                </p>

                <a
                    class="btn btn-secondary"
                    href="{{ route('composers.create') }}"
                >
                    + Novo compositor
                </a>
                <a class="btn btn-secondary" href="{{ route('openopus.index') }}">
                    Importar da Open Opus
                </a>

            </section>
        @endcan

    </div>

    @auth
        @php
            $favoriteComposersCount = auth()->user()
                ->favoriteComposers()
                ->count();

            $favoriteCompositionsCount = auth()->user()
                ->favorites()
                ->count();
        @endphp

        <section class="panel spaced">

            <span class="eyebrow">
                A minha biblioteca
            </span>

            <h2>Os meus favoritos</h2>

            <p class="muted">
                A sua coleção pessoal de compositores
                e obras musicais.
            </p>

            <div class="cards">

                <div class="stat">
                    <strong>
                        {{ $favoriteComposersCount }}
                    </strong>

                    <span>
                        {{ $favoriteComposersCount === 1
                            ? 'Compositor favorito'
                            : 'Compositores favoritos' }}
                    </span>
                </div>

                <div class="stat">
                    <strong>
                        {{ $favoriteCompositionsCount }}
                    </strong>

                    <span>
                        {{ $favoriteCompositionsCount === 1
                            ? 'Obra favorita'
                            : 'Obras favoritas' }}
                    </span>
                </div>

            </div>

            <div class="actions spaced">

                <a
                    href="{{ route('favorites.index') }}"
                    class="btn btn-primary"
                >
                    ♡ Ver os meus favoritos
                </a>

                <a
                    href="{{ route('composers.index') }}"
                    class="btn btn-secondary"
                >
                    Explorar catálogo
                </a>

            </div>

        </section>
    @endauth

@endsection
