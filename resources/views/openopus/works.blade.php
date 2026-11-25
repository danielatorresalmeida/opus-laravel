@extends('layouts.master')
@section('title', 'Importar obras — Open Opus')
@section('content')
    <nav class="breadcrumbs" aria-label="Localização">
        <a href="{{ route('composers.index') }}">Catálogo</a><span>›</span>
        <a href="{{ route('composers.show', $composer) }}">{{ $composer->name }}</a><span>›</span>
        <span>Importar obras</span>
    </nav>

    <div class="section-head">
        <div>
            <span class="eyebrow">Administração · API externa</span>
            <h1>Importar obras de {{ $composer->name }}</h1>
            <p class="muted">Consulta os dados da Open Opus e importa as obras que pretendes.</p>
        </div>
    </div>

    <section class="panel">
        <form method="GET" action="{{ route('openopus.works', $composer) }}" class="import-search">
            <div class="field">
                <label for="selection">Lista de obras</label>
                <select id="selection" name="selection">
                    <option value="All" @selected($selection === 'All')>Todas as obras</option>
                    <option value="Recommended" @selected($selection === 'Recommended')>Recomendadas</option>
                    <option value="Popular" @selected($selection === 'Popular')>Populares</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Consultar</button>
        </form>
        @if($apiError)<p class="alert error" role="alert">{{ $apiError }}</p>@endif
        @if($noResults)
            <p class="alert info" role="status">Não foram encontradas obras de {{ $composer->name }} nesta seleção.</p>
        @endif
    </section>

    <form id="openopus-import-works" method="POST" action="{{ route('openopus.works.import', $composer) }}" class="panel spaced">
        @csrf
        <input type="hidden" name="selection" value="{{ $selection }}">
        <input type="hidden" name="page" value="{{ $works->currentPage() }}">
        <h2>Obras encontradas: {{ $works->total() }}</h2>
        <p class="muted">Página {{ $works->currentPage() }} de {{ $works->lastPage() }}. Podes selecionar até 50 obras desta página. Para ver mais obras, avança para a página seguinte. A API não fornece PDF, imagem da partitura nem ano de composição.</p>
        @error('work_ids')<p class="alert error">{{ $message }}</p>@enderror
        @error('work_ids.*')<p class="alert error">{{ $message }}</p>@enderror
        <p class="muted" id="selected-works-count" role="status" aria-live="polite">0 obras selecionadas nesta página.</p>
        <div class="table-wrap">
            <table class="catalogue-table">
                <thead><tr>
                    <th scope="col">
                        <label style="display:inline-flex;align-items:center;gap:8px;cursor:pointer;">
                            <input type="checkbox" id="select-all-works-page" aria-label="Selecionar todas as obras disponíveis nesta página">
                            <span>Selecionar todos</span>
                        </label>
                    </th>
                    <th scope="col">Obra</th><th scope="col">Género</th><th scope="col">Estado</th>
                </tr></thead>
                <tbody>
                @forelse($works as $work)
                    @php
                        $registered = $existing->first(fn ($local) =>
                            (string) $local->openopus_id === (string) $work['id'] ||
                            ($local->openopus_id === null && mb_strtolower($local->title) === mb_strtolower($work['title']))
                        );
                    @endphp
                    <tr>
                        <td>
                            @if(!$registered || $registered->openopus_id === null)
                                <input type="checkbox" name="work_ids[]" value="{{ $work['id'] }}" aria-label="Importar ou associar {{ $work['title'] }}">
                            @else
                                <span class="muted">—</span>
                            @endif
                        </td>
                        <td><strong>{{ $work['title'] }}</strong></td>
                        <td>{{ $work['genre'] ?? '—' }}</td>
                        <td>{{ $registered ? ($registered->openopus_id === null ? 'Associar à API' : 'Já existe') : 'Nova' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">Não existem obras nesta lista.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="form-actions">
            <a class="btn btn-secondary" href="{{ route('composers.show', $composer) }}">Ver compositor</a>
            <button id="import-selected-works" type="submit" class="btn btn-primary" @disabled($works->isEmpty())>Importar obras selecionadas</button>
        </div>
        <p class="muted">Fonte: <a href="https://www.openopus.org/" target="_blank" rel="noopener noreferrer">Open Opus</a>. Antes de publicar partituras, confirma os direitos de utilização.</p>
    </form>

    @if($works->hasPages())
        <nav class="import-pager" aria-label="Páginas de obras">
            @if($works->onFirstPage())
                <span class="muted">← Anterior</span>
            @else
                <a class="btn btn-secondary" href="{{ $works->previousPageUrl() }}">← Anterior</a>
            @endif
            <span>Página {{ $works->currentPage() }} / {{ $works->lastPage() }}</span>
            @if($works->hasMorePages())
                <a class="btn btn-secondary" href="{{ $works->nextPageUrl() }}">Seguinte →</a>
            @else
                <span class="muted">Seguinte →</span>
            @endif
        </nav>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('openopus-import-works');
            const selectPage = document.getElementById('select-all-works-page');
            const counter = document.getElementById('selected-works-count');
            const submit = document.getElementById('import-selected-works');
            if (!form || !selectPage || !counter || !submit) return;

            // Apenas obras que ainda podem ser importadas ou associadas.
            const checkboxes = Array.from(form.querySelectorAll('input[name="work_ids[]"]'));

            function updateSelection() {
                const selected = checkboxes.filter(checkbox => checkbox.checked).length;
                selectPage.checked = checkboxes.length > 0 && selected === checkboxes.length;
                selectPage.indeterminate = selected > 0 && selected < checkboxes.length;
                selectPage.disabled = checkboxes.length === 0;
                submit.disabled = selected === 0;
                counter.textContent = selected === 1
                    ? '1 obra selecionada nesta página.'
                    : selected + ' obras selecionadas nesta página.';
            }

            selectPage.addEventListener('change', function () {
                checkboxes.forEach(checkbox => checkbox.checked = selectPage.checked);
                updateSelection();
            });
            checkboxes.forEach(checkbox => checkbox.addEventListener('change', updateSelection));
            updateSelection();
        });
    </script>
@endsection
