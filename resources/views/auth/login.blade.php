<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Accesso — HRprj</title>

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
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="card shadow-sm" style="width: 100%; max-width: 420px;">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo_blu.png') }}" alt="Logo HRprj" style="max-height: 80px; width: auto;">
                </div>
                <h1 class="h4 mb-4 text-center">Accedi</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $errore)
                                <li>{{ $errore }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Seleziona il tuo ruolo</label>
                        <select name="area" class="form-select" required>
                            <option value="" disabled selected>Seleziona un'area...</option>
                            @foreach ($aree as $area)
                                <option value="{{ $area }}" @selected(old('area') === $area)>
                                    {{ $area }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Accedi</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>