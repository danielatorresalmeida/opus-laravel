@extends('layouts.master')
@section('title', 'Criar conta')
@section('content')
    <section class="auth-wrap panel">
        <span class="eyebrow">A sua biblioteca musical</span><h1>Criar conta.</h1><p class="muted">Faça parte do catálogo OPUS.</p>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="field"><label for="name">Nome</label><input id="name" name="name" value="{{ old('name') }}" maxlength="255" required autocomplete="name"><x-field-error name="name"/></div>
            <div class="field"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"><x-field-error name="email"/></div>
            <div class="field"><label for="password">Palavra-passe</label><input id="password" type="password" name="password" required autocomplete="new-password"><x-field-error name="password"/></div>
            <div class="field"><label for="password_confirmation">Confirmar palavra-passe</label><input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"><x-field-error name="password_confirmation"/></div>
            <button class="btn btn-primary" type="submit">Criar conta</button>
        </form>
        <p class="alt muted">Já tem conta? <a href="{{ route('login') }}">Entrar</a></p>
    </section>
@endsection
