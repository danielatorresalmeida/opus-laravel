@extends('layouts.master')
@section('title', 'Importar compositores — Open Opus')
@section('content')
    <nav class="breadcrumbs" aria-label="Localização">
        <a href="{{ route('composers.index') }}">Catálogo</a><span>›</span><span>Open Opus</span>
    </nav>

    <div class="section-head">
        <div>
            <span class="eyebrow">Administração · API externa</span>
            <h1>Importar compositores</h1>
            <p class="muted">Pesquisa ou consulta a biblioteca da Open Opus. O catálogo do OPUS não tem limite de registos.</p>
        </div>
    </div>

    <section class="panel">
        <form method="GET" action="{{ route('openopus.index') }}" class="import-search">
            <div class="field">
                <label for="q">Pesquisar compositor</label>
                <input id="q" type="search" name="q" maxlength="80" value="{{ $search }}" placeholder="Ex.: Bach, Mozart, Schumann">
            </div>
            <div class="field">
                <label for="scope">Lista (sem pesquisa)</label>
                <select id="scope" name="scope">
                    <option value="Popular" @selected($scope === 'Popular')>Populares</option>
                    <option value="Recommended" @selected($scope === 'Recommended')>Recomendados</option>
                    <option value="All" @selected($scope === 'All')>Todos os compositores</option>
                </select>
            </div>
            <button class="btn btn-primary" type="submit">Consultar</button>
        </form>
        <p class="muted">A lista é obtida através do Laravel e apresentada em páginas de 50. Uma pesquisa pelo nome tem prioridade sobre a lista escolhida.</p>
        @error('q')<p class="alert error">{{ $message }}</p>@enderror
        @if($apiError)<p class="alert error" role="alert">{{ $apiError }}</p>@endif
        @if($noResults)
            <p class="alert info" role="status">
                @if($search !== '')
                    Não foram encontrados compositores para «{{ $search }}». Experimenta outro nome.
                @else
                    Não foram encontrados compositores nesta lista.
                @endif
            </p>
        @endif
    </section>

    <form method="POST" action="{{ route('openopus.composers.import') }}" class="panel spaced">
        @csrf
        <h2>Compositores encontrados: {{ $composers->total() }}</h2>
        <p class="muted">Página {{ $composers->currentPage() }} de {{ $composers->lastPage() }}. Seleciona até 50 compositores desta página. Os registos existentes não são duplicados nem substituídos.</p>
        @error('composer_ids')<p class="alert error">{{ $message }}</p>@enderror
        @error('composer_ids.*')<p class="alert error">{{ $message }}</p>@enderror
        <div class="table-wrap">
            <table class="catalogue-table">
                <thead><tr><th scope="col">Selecionar</th><th scope="col">Compositor</th><th scope="col">Período</th><th scope="col">No OPUS</th></tr></thead>
                <tbody>
                @forelse($composers as $remote)
                    @php
                        $registered = $existing->first(fn ($local) =>
                            (string) $local->openopus_id === (string) $remote['id'] ||
                            ($local->openopus_id === null && mb_strtolower($local->name) === mb_strtolower($remote['complete_name']))
                        );
                    @endphp
                    <tr>
                        <td>
                            @if(!$registered || $registered->openopus_id === null)
                                <input type="checkbox" name="composer_ids[]" value="{{ $remote['id'] }}" aria-label="Importar ou associar {{ $remote['complete_name'] }}">
                            @else
                                <span class="muted">—</span>
                            @endif
                        </td>
                        <td><strong>{{ $remote['complete_name'] }}</strong><br><small class="muted">{{ substr((string) ($remote['birth'] ?? ''), 0, 4) }} — {{ substr((string) ($remote['death'] ?? ''), 0, 4) ?: 'presente' }}</small></td>
                        <td>{{ $remote['epoch'] ?? '—' }}</td>
                        <td>
                            @if($registered)
                                <a href="{{ route('composers.show', $registered) }}">Já existe</a>
                                @if($registered->openopus_id)
                                    <a class="btn btn-secondary btn-sm" href="{{ route('openopus.works', $registered) }}">Importar obras</a>
                                @else
                                    <small class="muted">Seleciona para associar à API</small>
                                @endif
                            @else
                                <span class="muted">Novo</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">Não foram encontrados compositores nesta pesquisa.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="form-actions">
            <a class="btn btn-secondary" href="{{ route('composers.index') }}">Voltar ao catálogo</a>
            <button type="submit" class="btn btn-primary" @disabled($composers->isEmpty())>Importar selecionados</button>
        </div>
        <p class="muted">Fonte: <a href="https://www.openopus.org/" target="_blank" rel="noopener noreferrer">Open Opus</a>. Os retratos não são descarregados automaticamente.</p>
    </form>

    @if($composers->hasPages())
        <nav class="import-pager" aria-label="Páginas de compositores">
            @if($composers->onFirstPage())
                <span class="muted">← Anterior</span>
            @else
                <a class="btn btn-secondary" href="{{ $composers->previousPageUrl() }}">← Anterior</a>
            @endif
            <span>Página {{ $composers->currentPage() }} / {{ $composers->lastPage() }}</span>
            @if($composers->hasMorePages())
                <a class="btn btn-secondary" href="{{ $composers->nextPageUrl() }}">Seguinte →</a>
            @else
                <span class="muted">Seguinte →</span>
            @endif
        </nav>
    @endif
@endsection
