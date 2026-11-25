@extends('layouts.master')
@section('title', 'Página não encontrada')
@section('content')
    <section class="panel"><span class="eyebrow">Erro 404</span><h1>Página não encontrada.</h1><p class="muted">O registo ou página que procura não existe.</p><a class="btn btn-primary" href="{{ url('/') }}">Voltar ao catálogo</a></section>
@endsection
