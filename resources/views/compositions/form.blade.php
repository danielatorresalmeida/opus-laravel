@extends('layouts.master')
@php($editing = isset($composition))
@section('title', $editing ? 'Editar obra' : 'Nova obra')

@section('content')
    <div class="form-wrap">
        <nav class="breadcrumbs" aria-label="Localização">
            <a href="{{ route('composers.index') }}">Catálogo</a>
            <span>›</span>
            <a href="{{ route('composers.show', $composer) }}">{{ $composer->name }}</a>
            <span>›</span>
            <span>{{ $editing ? 'Editar obra' : 'Nova obra' }}</span>
        </nav>

        <div class="section-head">
            <div>
                <span class="eyebrow">{{ $composer->name }}</span>
                <h1>{{ $editing ? 'Editar obra' : 'Nova obra musical' }}</h1>
                <p class="muted">Os campos assinalados com * são obrigatórios.</p>
            </div>
        </div>

        <form class="panel" method="POST" enctype="multipart/form-data"
              action="{{ $editing ? route('compositions.update', $composition) : route('composers.compositions.store', $composer) }}">
            @csrf
            @if($editing)
                @method('PUT')
            @endif

            <div class="form-grid">
                <div class="field full">
                    <label for="title">Título da obra *</label>
                    <input id="title" name="title" maxlength="255" required
                           value="{{ old('title', $composition->title ?? '') }}">
                    <x-field-error name="title" />
                </div>

                <div class="field">
                    <label for="catalogue_number">Número de catálogo</label>
                    <input id="catalogue_number" name="catalogue_number" maxlength="100"
                           placeholder="BWV, K., Op., D. …"
                           value="{{ old('catalogue_number', $composition->catalogue_number ?? '') }}">
                    <x-field-error name="catalogue_number" />
                </div>

                <div class="field">
                    <label for="composition_year">Ano de composição</label>
                    <input id="composition_year" name="composition_year" type="number" min="1" max="2100"
                           value="{{ old('composition_year', $composition->composition_year ?? '') }}">
                    <x-field-error name="composition_year" />
                </div>

                <div class="field">
                    <label for="genre">Género musical</label>
                    <input id="genre" name="genre" maxlength="100"
                           placeholder="Cantata, sonata, motete…"
                           value="{{ old('genre', $composition->genre ?? '') }}">
                    <x-field-error name="genre" />
                </div>

                <div class="field">
                    <label for="instrumentation">Instrumentação</label>
                    <input id="instrumentation" name="instrumentation" maxlength="255"
                           placeholder="Coro SATB, órgão…"
                           value="{{ old('instrumentation', $composition->instrumentation ?? '') }}">
                    <x-field-error name="instrumentation" />
                </div>

                <div class="field full">
                    <label for="description">Notas e contexto</label>
                    <textarea id="description" name="description" rows="5">{{ old('description', $composition->description ?? '') }}</textarea>
                    <x-field-error name="description" />
                </div>

                <div class="field full">
                    <label for="image">Imagem de capa ou manuscrito</label>
                    @if($editing && $composition->image)
                        <x-artwork :path="$composition->image" kind="score" />
                    @endif
                    <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
                    <span class="help">JPG, PNG ou WebP. Deixe vazio para manter a imagem atual.</span>
                    <x-field-error name="image" />
                </div>

                <div class="field full">
                    <label for="score">Partitura (PDF)</label>
                    <input id="score" name="score" type="file" accept="application/pdf">
                    <span class="help">
                        @if($editing && $composition->score_path)
                            Já existe uma partitura. Deixe vazio para a manter.
                        @else
                            Anexe apenas uma edição que possa ser legalmente partilhada.
                        @endif
                    </span>
                    <x-field-error name="score" />
                </div>
            </div>

            <div class="form-actions">
                <a class="btn btn-secondary" href="{{ route('composers.show', $composer) }}">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar obra</button>
            </div>
        </form>
    </div>
@endsection
