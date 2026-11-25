
@extends('layouts.master')

@section('title', 'Catálogo de compositores')

@section('content')

    <section class="hero">
        <div class="hero-content">
            <span class="eyebrow">Biblioteca de música erudita</span>
            <h1>Descobrir a música.<br>Preservar a sua história.</h1>
            <p>Explore compositores, obras e fontes musicais de diferentes épocas.</p>
        </div>

        <img class="hero-mark" src="{{ asset('images/opus-mark.svg') }}" alt="">
    </section>

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

    <div class="section-head">
        <div>
            <span class="eyebrow">01 / Catálogo</span>
            <h2>Compositores</h2>
            <p class="muted">
                {{ $composers->total() }}
                {{ $composers->total() === 1 ? 'registo encontrado' : 'registos encontrados' }}
            </p>
        </div>

        @can('manage-system')
            <a class="btn btn-primary" href="{{ route('composers.create') }}">
                + Novo compositor
            </a>
        @endcan
    </div>

    <section class="panel flush">

        <form class="filters" method="GET" action="{{ route('composers.index') }}">

            <input type="hidden" name="sort" value="{{ request('sort', 'name') }}">
            <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">

            <div class="field search">
                <label for="q">Pesquisar</label>
                <input
                    id="q"
                    type="search"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Nome do compositor"
                >
            </div>

            <div class="field">
                <label for="period">Período musical</label>
                <select id="period" name="period">
                    <option value="">Todos os períodos</option>

                    @foreach($periods as $period)
                        <option value="{{ $period }}"
                            @selected(request('period') === $period)>
                            {{ $period }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="works">Número de obras</label>
                <select id="works" name="works">
                    <option value="">Todas</option>
                    <option value="0" @selected(request('works') === '0')>0 obras</option>
                    <option value="1" @selected(request('works') === '1')>1 obra</option>
                    <option value="2" @selected(request('works') === '2')>2 obras</option>
                    <option value="3plus" @selected(request('works') === '3plus')>3 ou mais</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Pesquisar
            </button>

            <a class="btn btn-secondary" href="{{ route('composers.index') }}">
                Limpar
            </a>
        </form>

        <div class="table-wrap">
            <table class="catalogue-table">
                <thead>
                    <tr>
                        @foreach(['name' => 'Compositor', 'period' => 'Período', 'works' => 'Obras'] as $column => $label)
                            @php
                                $active = request('sort', 'name') === $column;
                                $direction = $active && request('direction', 'asc') === 'asc' ? 'desc' : 'asc';
                            @endphp

                            <th scope="col">
                                <a class="table-sort" href="{{ request()->fullUrlWithQuery([
                                    'sort' => $column,
                                    'direction' => $direction,
                                    'page' => 1,
                                ]) }}">
                                    {{ $label }}
                                    @if($active)
                                        {{ request('direction', 'asc') === 'asc' ? '↑' : '↓' }}
                                    @endif
                                </a>
                            </th>
                        @endforeach

                        <th scope="col">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($composers as $composer)
                        <tr>
                            <td>
                                <div class="record">
                                    <x-artwork :path="$composer->image" />

                                    <div>
                                        <a class="record-name"
                                            href="{{ route('composers.show', $composer) }}">
                                            {{ $composer->name }}
                                        </a>

                                        <small>
                                            <x-composer-meta :composer="$composer" />
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge">
                                    {{ $composer->period ?? 'Não indicado' }}
                                </span>
                            </td>

                            <td class="number">
                                {{ $composer->compositions_count }}
                            </td>

                            <td>
                                <div class="actions">
                                    <a class="btn btn-secondary btn-sm"
                                        href="{{ route('composers.show', $composer) }}">
                                        Ver obras
                                    </a>

                                    <x-composer-favorite-button :composer="$composer" />

                                    @auth
                                        <a class="btn btn-secondary btn-sm"
                                            href="{{ route('composers.edit', $composer) }}">
                                            Editar
                                        </a>
                                    @endauth

                                    @can('manage-system')
                                        <form
                                            class="opus-catalogue-delete"
                                            method="POST"
                                            action="{{ route('composers.destroy', $composer) }}"
                                            onsubmit="return confirm('Eliminar este compositor e todas as suas obras?');"
                                        >
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
                                Nenhum compositor encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($composers->hasPages())
            <div class="pagination-wrap">
                {{ $composers->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </section>

@endsection
