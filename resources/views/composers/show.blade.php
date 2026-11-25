@extends('layouts.master')

@section('title', $composer->name)

@section('content')
    @can('manage-system')
        @if($composer->openopus_id)
            <div class="import-shortcut">
                <a class="btn btn-secondary btn-sm" href="{{ route('openopus.works', $composer) }}">
                    Importar obras da Open Opus
                </a>
            </div>
        @endif
    @endcan


    <nav class="breadcrumbs" aria-label="Localização">
        <a href="{{ route('composers.index') }}">
            Catálogo
        </a>

        <span>›</span>
        <span>{{ $composer->name }}</span>
    </nav>

    <section class="panel profile">

        <x-artwork :path="$composer->image" />

        <div>
            <span class="eyebrow">
                Compositor · {{ $composer->period ?? 'Período não indicado' }}
            </span>

            <h1>{{ $composer->name }}</h1>

            <p class="muted">
                <x-composer-meta :composer="$composer" />
            </p>

            <div class="actions">
                <x-composer-favorite-button :composer="$composer" />
            </div>

            @if ($composer->biography)
                <p class="prose">
                    {{ $composer->biography }}
                </p>
            @endif

            @auth
                <a class="btn btn-secondary" href="{{ route('composers.edit', $composer) }}">
                    Editar compositor
                </a>
            @endauth
        </div>

    </section>

    <div class="section-head">

        <div>
            <span class="eyebrow">02 / Repertório</span>

            <h2>Obras musicais</h2>

            <p class="muted">
                {{ $compositions->total() }}

                {{ $compositions->total() === 1 ? 'obra registada' : 'obras registadas' }}
            </p>
        </div>

        @can('manage-system')
            <a class="btn btn-primary" href="{{ route('composers.compositions.create', $composer) }}">
                + Nova obra
            </a>
        @endcan

    </div>

    <section class="panel flush">

        <form class="filters" action="{{ route('composers.show', $composer) }}" method="GET">
            <div class="field search">
                <label for="q">Pesquisar obra</label>

                <input id="q" type="search" name="q" value="{{ request('q') }}"
                    placeholder="Título ou número de catálogo">
            </div>

            <button class="btn btn-primary" type="submit">
                Pesquisar
            </button>

            <a class="btn btn-secondary" href="{{ route('composers.show', $composer) }}">
                Limpar
            </a>
        </form>

        <div class="table-wrap">

            <table>
                <thead>
                    <tr>
                        <th scope="col">Obra</th>
                        <th scope="col">Ano de composição</th>
                        <th scope="col">Género</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($compositions as $composition)
                        <tr>
                            <td>
                                <div class="record">

                                    <x-artwork :path="$composition->image" kind="score" />

                                    <div>
                                        <a class="record-name" href="{{ route('compositions.show', $composition) }}">
                                            {{ $composition->title }}
                                        </a>

                                        <small>
                                            {{ $composition->catalogue_number ?? 'Sem número de catálogo' }}
                                        </small>
                                    </div>

                                </div>
                            </td>

                            <td>
                                {{ $composition->composition_year ?? 'Desconhecido' }}
                            </td>

                            <td>
                                {{ $composition->genre ?? '—' }}
                            </td>

                            <td>

                                <div class="actions">

                                    <a class="btn btn-secondary btn-sm"
                                        href="{{ route('compositions.show', $composition) }}">
                                        Ver obra
                                    </a>

                                    @auth
                                        <x-composition-favorite-button :composition="$composition" />

                                        <a class="btn btn-secondary btn-sm"
                                           href="{{ route('compositions.edit', $composition) }}">
                                            Editar
                                        </a>
                                    @endauth

                                    @can('manage-system')
                                        <form method="POST" action="{{ route('compositions.destroy', $composition) }}"
                                            onsubmit="return confirm('Eliminar esta obra?');">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-danger btn-sm" type="submit">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endcan

                                </div>

                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="4" class="empty">
                                Ainda não existem obras para apresentar.
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

        </div>

        @if ($compositions->hasPages())
            <div class="pagination-wrap">
                {{ $compositions->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </section>

@endsection
