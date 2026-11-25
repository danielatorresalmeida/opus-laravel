@extends('layouts.master')
@section('title', 'Entrar')
@section('content')
    <section class="auth-wrap panel">
        <span class="eyebrow">A sua biblioteca musical</span>
        <h1>Bem-vindo.</h1>
        <p class="muted">Entre para gerir e editar o catálogo.</p>
        @if ($errors->any())
            <div class="alert error" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="alert" role="status">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="field"><label for="email">Email</label><input id="email" type="email" name="email"
                    value="{{ old('email') }}" required autocomplete="email"></div>
            <div class="field"><label for="password">Palavra-passe</label><input id="password" type="password"
                    name="password" required autocomplete="current-password"></div>
            <label class="check"><input name="remember" type="checkbox" value="1" @checked(old('remember'))> Manter
                sessão iniciada</label>
            @if (Route::has('password.request'))
                <p class="muted">
                    <a href="{{ route('password.request') }}">
                        Esqueceu-se da palavra-passe?
                    </a>
                </p>
            @endif
            <button class="btn btn-primary" type="submit">Entrar</button>
        </form>
        @if (Route::has('register'))
            <p class="alt muted">Ainda não tem conta? <a href="{{ route('register') }}">Criar conta</a></p>
        @endif
    </section>
@endsection
