@extends('layouts.master')

@section('title', 'Definir nova palavra-passe')

@section('content')
    <section class="auth-wrap panel">
        <span class="eyebrow">Segurança da conta</span>
        <h1>Nova palavra-passe</h1>

        <p class="muted">
            Escolha uma nova palavra-passe para a sua conta OPUS.
        </p>

        @if ($errors->any())
            <div class="alert error" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div class="field">
                <label for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', request('email')) }}"
                    autocomplete="email"
                    required
                    readonly
                >
            </div>

            <div class="field">
                <label for="password">Nova palavra-passe</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    autocomplete="new-password"
                    required
                >
            </div>

            <div class="field">
                <label for="password_confirmation">Confirmar palavra-passe</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    autocomplete="new-password"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Guardar nova palavra-passe
            </button>
        </form>

        <p class="alt muted">
            <a href="{{ route('login') }}">Voltar ao início de sessão</a>
        </p>
    </section>
@endsection
