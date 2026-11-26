<!doctype html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#19382f">

    <title>@yield('title', 'Catálogo') | OPUS</title>

    <link rel="icon" type="image/svg+xml"
          href="{{ asset('images/opus-mark.svg') }}">

    <link rel="stylesheet" href="{{ asset('css/opus.css') }}">
</head>

<body>
    <a class="skip" href="#main">
        Saltar para o conteúdo
    </a>

    <header class="topbar">
        <nav class="container nav" aria-label="Menu principal">

            <a class="brand" href="{{ url('/') }}">
                <img src="{{ asset('images/opus-mark.svg') }}"
                     alt="" width="38" height="38">

                <span class="wordmark">
                    OPUS
                    <small>BIBLIOTECA MUSICAL</small>
                </span>
            </a>

            <div class="nav-links">

                @if(Route::has('composers.index'))
                    <a
                        class="{{ request()->routeIs('composers.*', 'compositions.*') ? 'active' : '' }}"
                        href="{{ route('composers.index') }}"
                    >
                        Catálogo
                    </a>
                @endif

                @if(Route::has('sources.index'))
                    <a
                        class="{{ request()->routeIs('sources.*') ? 'active' : '' }}"
                        href="{{ route('sources.index') }}"
                    >
                        Fontes
                    </a>
                @endif

                @auth

                    @if(Route::has('dashboard'))
                        <a
                            class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            href="{{ route('dashboard') }}"
                        >
                            Dashboard
                        </a>
                    @endif

                    <a
                        class="{{ request()->routeIs('favorites.*') ? 'active' : '' }}"
                        href="{{ route('favorites.index') }}"
                    >
                        ♡ Favoritos
                    </a>

                    @can('manage-system')
                        <a class="{{ request()->routeIs('openopus.*') ? 'active' : '' }}"
                           href="{{ route('openopus.index') }}">Importar da API</a>
                        <span class="role">
                            Administrador
                        </span>
                    @endcan

                    @if(Route::has('logout'))
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <button class="btn btn-nav" type="submit">
                                Sair
                            </button>
                        </form>
                    @endif

                @endauth

                @guest
                @unless(config('opus.read_only'))

                    @if(Route::has('login'))
                        <a href="{{ route('login') }}">
                            Entrar
                        </a>
                    @endif

                    @if(Route::has('register'))
                        <a class="btn btn-gold"
                           href="{{ route('register') }}">
                            Criar conta
                        </a>
                    @endif

                @endunless
                @endguest

            </div>
        </nav>
    </header>

    <main class="container main" id="main">

        @if(config('opus.read_only'))
            <div class="alert" role="status">
                Demonstração pública do OPUS: consulta disponível; registo e alterações desativados.
            </div>
        @endif

        @if(session('success'))
            <div class="alert" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')

    </main>

    <footer class="footer">
        <div class="container footer-inner">
            <span>
                <strong>OPUS</strong> · Biblioteca de música erudita
            </span>
            <span>
                Projeto Laravel · 2026
            </span>
        </div>
    </footer>

</body>
</html>
