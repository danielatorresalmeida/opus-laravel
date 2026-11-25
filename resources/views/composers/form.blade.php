@extends('layouts.master')

@php($editing = isset($composer))

@section('title', $editing ? 'Editar compositor' : 'Novo compositor')

@section('content')
    <div class="form-wrap">
        <nav class="breadcrumbs" aria-label="Localização">
            <a href="{{ route('composers.index') }}">Catálogo</a>
            <span>›</span>
            <span>{{ $editing ? 'Editar' : 'Novo compositor' }}</span>
        </nav>

        <div class="section-head">
            <div>
                <span class="eyebrow">Gestão do catálogo</span>
                <h1>{{ $editing ? 'Editar compositor' : 'Novo compositor' }}</h1>
                <p class="muted">Os campos assinalados com * são obrigatórios.</p>
            </div>
        </div>

        <form class="panel" method="POST" enctype="multipart/form-data"
              action="{{ $editing ? route('composers.update', $composer) : route('composers.store') }}">
            @csrf
            @if($editing)
                @method('PUT')
            @endif

            <div class="form-grid">
                <div class="field full">
                    <label for="name">Nome completo *</label>
                    <input id="name" name="name" required maxlength="255"
                           value="{{ old('name', $composer->name ?? '') }}">
                    <x-field-error name="name" />
                </div>

                <div class="field">
                    <label for="nationality">Nacionalidade</label>
                    <input id="nationality" name="nationality" maxlength="100"
                           value="{{ old('nationality', $composer->nationality ?? '') }}">
                    <x-field-error name="nationality" />
                </div>

                <div class="field">
                    <label for="period">Período musical</label>
                    <select id="period" name="period">
                        <option value="">Selecionar período</option>
                        @foreach([
                            'Medieval',
                            'Renascimento',
                            'Barroco',
                            'Classicismo',
                            'Romantismo',
                            'Impressionismo',
                            'Transição para o século XX',
                            'Século XX',
                            'Contemporâneo'
                        ] as $period)
                            <option value="{{ $period }}"
                                @selected(old('period', $composer->period ?? '') === $period)>
                                {{ $period }}
                            </option>
                        @endforeach
                    </select>
                    <x-field-error name="period" />
                </div>

                <div class="field">
                    <label for="birth_year">Ano de nascimento</label>
                    <input id="birth_year" name="birth_year" type="number" min="1" max="2100"
                           value="{{ old('birth_year', $composer->birth_year ?? '') }}">
                    <x-field-error name="birth_year" />
                </div>

                <div class="field">
                    <label for="death_year">Ano de falecimento</label>
                    <input id="death_year" name="death_year" type="number" min="1" max="2100"
                           value="{{ old('death_year', $composer->death_year ?? '') }}">
                    <x-field-error name="death_year" />
                </div>

                <div class="field full">
                    <label for="biography">Biografia</label>
                    <textarea id="biography" name="biography" rows="6">{{ old('biography', $composer->biography ?? '') }}</textarea>
                    <x-field-error name="biography" />
                </div>

                <div class="field full">
                    <label for="image">Retrato do compositor</label>
                    @if($editing && $composer->image)
                        <x-artwork :path="$composer->image" />
                    @endif
                    <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
                    <span class="help">JPG, PNG ou WebP. Utilize imagens com autorização de utilização. Na edição, deixe vazio para manter o retrato atual.</span>
                    <x-field-error name="image" />
                </div>
            </div>

            <div class="form-actions">
                <a class="btn btn-secondary" href="{{ route('composers.index') }}">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar compositor</button>
            </div>
        </form>
    </div>
@endsection
