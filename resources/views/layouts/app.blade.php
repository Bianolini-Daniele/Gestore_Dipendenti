<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'HRprj')</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        a {
            color: #000;
            text-decoration: none;
        }

        a.link-richiesta {
            text-decoration: underline;
        }
    </style>
</head>

<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="{{ route(\App\Http\Controllers\AccessoController::rottaHomepage(session('area_accesso'))) }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" height="50" class="d-inline-block align-text-top">
            </a>

            @if (session('area_accesso'))
                <div class="d-flex align-items-center gap-3">
                    <span class="text-white small">Area: {{ session('area_accesso') }}</span>

                    <form method="POST" action="{{ route('logout') }}" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light">Esci</button>
                    </form>
                </div>
            @endif
        </div>
    </nav>

    <main class="container pb-5">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Controllare i dati inseriti:</strong>

                <ul class="mb-0">
                    @foreach ($errors->all() as $errore)
                        <li>{{ $errore }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>