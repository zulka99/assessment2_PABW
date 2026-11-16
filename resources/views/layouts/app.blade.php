<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SmartDrain</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <script>
    try {
        const theme = localStorage.getItem('smartdrain-theme');
        document.documentElement.dataset.bsTheme =
            theme === 'dark' ? 'dark' : 'light';
        } catch (error) {}
    </script>
    
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold"
               href="{{ url('/dashboard') }}">
                KotaCare
            </a>

            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#menu"
                    aria-controls="menu"
                    aria-expanded="false"
                    aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <button id="theme-toggle"
                    type="button"
                    class="btn btn-sm btn-outline-secondary">
                    Ganti tema
            </button>
            <div class="collapse navbar-collapse" id="menu">
                <div class="navbar-nav me-auto">
                    @auth
                        <a class="nav-link"
                           href="{{ route('dashboard') }}">
                            Dashboard
                        </a>

                        <a class="nav-link"
                           href="{{ route('titikdrainase.index') }}">
                            Titik Drainase
                        </a>

                        <a class="nav-link"
                           href="{{ route('laporan.index') }}">
                            Laporan
                        </a>
                    @endauth
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2 py-2">
                    @auth
                        <span class="small">
                            {{ auth()->user()->name }}
                            ({{ auth()->user()->role }})
                        </span>

                        <form method="POST"
                              action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="btn btn-sm btn-primary">
                            Login
                        </a>

                        <a href="{{ route('register') }}"
                           class="btn btn-sm btn-outline-primary">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')

    <script>
    document.getElementById('theme-toggle')
        .addEventListener('click', function () {
            const html = document.documentElement;
            const next = html.dataset.bsTheme === 'dark'
                ? 'light'
                : 'dark';

            html.dataset.bsTheme = next;

            try {
                localStorage.setItem('smartdrain-theme', next);
            } catch (error) {}
        });
    </script>
    </main>
</body>
</html>