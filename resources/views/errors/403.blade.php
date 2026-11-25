@extends('layouts.master')
@section('title', 'Acesso reservado')
@section('content')
    <section class="panel"><span class="eyebrow">Erro 403</span><h1>Acesso reservado.</h1><p class="muted">Não tem permissão para executar esta operação.</p><a class="btn btn-primary" href="{{ url('/') }}">Voltar ao catálogo</a></section>
@endsection
