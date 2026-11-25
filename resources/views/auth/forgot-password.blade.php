@extends('layouts.master')

@section('title', 'Recuperar palavra-passe')

@section('content')
    <section class="auth-wrap panel">
        <span class="eyebrow">Segurança da conta</span>
        <h1>Recuperar palavra-passe</h1>

        <p class="muted">
            Indique o email associado à sua conta. Enviaremos uma ligação
            para definir uma nova palavra-passe.
        </p>

        @if (session('status'))
            <div class="alert" role="status">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert error" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    required
                    autofocus
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Enviar ligação de recuperação
            </button>
        </form>

        <p class="alt muted">
            <a href="{{ route('login') }}">Voltar ao início de sessão</a>
        </p>
    </section>
@endsection
