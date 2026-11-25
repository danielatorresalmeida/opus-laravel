@extends('layouts.master')

@section('title', $composition->title)

@section('content')

    <nav class="breadcrumbs" aria-label="Localização">
        <a href="{{ route('composers.index') }}">Catálogo</a>
        <span>›</span>

        <a href="{{ route('composers.show', $composition->composer) }}">
            {{ $composition->composer->name }}
        </a>

        <span>›</span>
        <span>{{ $composition->title }}</span>
    </nav>

    <section class="panel profile">

        <x-artwork :path="$composition->image" kind="score" />

        <div>
            <span class="eyebrow">
                {{ $composition->composer->period ?? 'Música erudita' }}
            </span>

            <h1>{{ $composition->title }}</h1>

            <p>{{ $composition->composer->name }}</p>

            <div class="opus-favourite-section">
                <x-composition-favorite-button :composition="$composition" large="true" />
            </div>

            <dl class="details">

                <div>
                    <dt>Catálogo</dt>
                    <dd>
                        {{ $composition->catalogue_number ?? 'Não indicado' }}
                    </dd>
                </div>

                <div>
                    <dt>Ano de composição</dt>
                    <dd>
                        {{ $composition->composition_year ?? 'Desconhecido' }}
                    </dd>
                </div>

                <div>
                    <dt>Género</dt>
                    <dd>
                        {{ $composition->genre ?? 'Não indicado' }}
                    </dd>
                </div>

                <div>
                    <dt>Instrumentação</dt>
                    <dd>
                        {{ $composition->instrumentation ?? 'Não indicada' }}
                    </dd>
                </div>

            </dl>

            <div class="actions">

                @if($composition->score_path)
                    <a
                        class="btn btn-primary"
                        href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($composition->score_path) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        Consultar partitura (PDF)
                    </a>
                @endif

                @auth
                    <a
                        class="btn btn-secondary"
                        href="{{ route('compositions.edit', $composition) }}"
                    >
                        Editar obra
                    </a>
                @endauth

            </div>

        </div>
    </section>

    <section class="panel spaced">

        <span class="eyebrow">Contexto musical</span>

        <h2>Sobre esta obra</h2>

        <p class="prose">
            {{ $composition->description ?? 'Ainda não foram adicionadas notas.' }}
        </p>

    </section>

    <section class="panel spaced">

        <span class="eyebrow">Investigação</span>

        <h2>Fontes e referências</h2>

        <ul class="source-list">

            @forelse($references ?? [] as $reference)

                <li>
                    <div>
                        <strong>
                            {{ $reference['name'] }}
                        </strong>

                        <br>

                        <span class="muted">
                            {{ $reference['label'] ?? 'Referência externa' }}
                        </span>
                    </div>

                    <a
                        class="btn btn-secondary btn-sm"
                        href="{{ $reference['url'] }}"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        Consultar fonte ↗
                    </a>
                </li>

            @empty

                <li class="muted">
                    Ainda não foram associadas referências a esta obra.
                </li>

            @endforelse

        </ul>

        @if(Route::has('sources.index'))
            <a
                class="btn btn-secondary spaced"
                href="{{ route('sources.index') }}"
            >
                Explorar diretório de fontes
            </a>
        @endif

    </section>

@endsection
