@extends('layouts.app')

@section('title', 'Homepage IT')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Area IT</h1>
    </div>

    <form method="GET" action="{{ route('home.it') }}" class="row g-2 mb-3">
        <div class="col-md-4">
            <label class="form-label">Cerca dipendente</label>
            <div class="input-group">
                <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Nome o cognome">
                <button class="btn btn-outline-secondary" type="submit">Cerca</button>
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0 align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>Richiesta</th>
                        <th>
                            <div class="d-flex align-items-center justify-content-between gap-2 w-100">
                                <span>Urgenza della richiesta</span>
                                <form method="GET" action="{{ route('home.it') }}" class="mb-0">
                                    <input type="hidden" name="tipo" value="{{ $tipo }}">
                                    <input type="hidden" name="stato_richiesta" value="{{ $statoRichiesta }}">
                                    <input type="hidden" name="stato_dipendente" value="{{ $statoDipendente }}">
                                    <input type="hidden" name="search" value="{{ $search }}">
                                    <select name="urgenza" class="form-select form-select-sm w-auto" style="background-color: #cfe2ff; border-color: #b6d4fe;" onchange="this.form.submit()">
                                        <option value="tutti" @selected($urgenza === 'tutti')>Tutti</option>
                                        <option value="alta" @selected($urgenza === 'alta')>Alta</option>
                                        <option value="media" @selected($urgenza === 'media')>Media</option>
                                        <option value="bassa" @selected($urgenza === 'bassa')>Bassa</option>
                                    </select>
                                </form>
                            </div>
                        </th>
                        <th>
                            <div class="d-flex align-items-center justify-content-between gap-2 w-100">
                                <span>Tipo richiesta</span>
                                <form method="GET" action="{{ route('home.it') }}" class="mb-0">
                                    <input type="hidden" name="stato_richiesta" value="{{ $statoRichiesta }}">
                                    <input type="hidden" name="stato_dipendente" value="{{ $statoDipendente }}">
                                    <input type="hidden" name="urgenza" value="{{ $urgenza }}">
                                    <input type="hidden" name="search" value="{{ $search }}">
                                    <select name="tipo" class="form-select form-select-sm w-auto" style="background-color: #cfe2ff; border-color: #b6d4fe;" onchange="this.form.submit()">
                                        <option value="tutti" @selected($tipo === 'tutti')>Tutti</option>
                                        <option value="documenti" @selected($tipo === 'documenti')>Documenti</option>
                                        <option value="dotazioni" @selected($tipo === 'dotazioni')>Dotazioni</option>
                                    </select>
                                </form>
                            </div>
                        </th>
                        <th>
                            <div class="d-flex align-items-center justify-content-between gap-2 w-100">
                                <span>Risolta</span>
                                <form method="GET" action="{{ route('home.it') }}" class="mb-0">
                                    <input type="hidden" name="tipo" value="{{ $tipo }}">
                                    <input type="hidden" name="stato_dipendente" value="{{ $statoDipendente }}">
                                    <input type="hidden" name="urgenza" value="{{ $urgenza }}">
                                    <input type="hidden" name="search" value="{{ $search }}">
                                    <select name="stato_richiesta" class="form-select form-select-sm w-auto" style="background-color: #cfe2ff; border-color: #b6d4fe;" onchange="this.form.submit()">
                                        <option value="tutti" @selected($statoRichiesta === 'tutti')>Tutti</option>
                                        <option value="non_risolta" @selected($statoRichiesta === 'non_risolta')>Non risolta</option>
                                        <option value="in_risoluzione" @selected($statoRichiesta === 'in_risoluzione')>In risoluzione</option>
                                        <option value="risolta" @selected($statoRichiesta === 'risolta')>Risolta</option>
                                    </select>
                                </form>
                            </div>
                        </th>
                        <th>Dipendente</th>
                        <th>
                            <div class="d-flex align-items-center justify-content-between gap-2 w-100">
                                <span>Stato dipendente</span>
                                <form method="GET" action="{{ route('home.it') }}" class="mb-0">
                                    <input type="hidden" name="tipo" value="{{ $tipo }}">
                                    <input type="hidden" name="stato_richiesta" value="{{ $statoRichiesta }}">
                                    <input type="hidden" name="urgenza" value="{{ $urgenza }}">
                                    <input type="hidden" name="search" value="{{ $search }}">
                                    <select name="stato_dipendente" class="form-select form-select-sm w-auto" style="background-color: #cfe2ff; border-color: #b6d4fe;" onchange="this.form.submit()">
                                        <option value="tutti" @selected($statoDipendente === 'tutti')>Tutti</option>
                                        @foreach (\App\Models\Anagrafica::STATI_DIPENDENTE as $valore => $etichetta)
                                            <option value="{{ $valore }}" @selected($statoDipendente === $valore)>{{ $etichetta }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($richieste as $richiesta)
                        <tr>
                            <td>
                                @php
                                    $route = $richiesta['tipo_richiesta'] === 'documento'
                                        ? route('documenti.show', $richiesta['model'])
                                        : route('dotazioni.show', $richiesta['model']);
                                @endphp
                                <a href="{{ $route }}" class="text-decoration-none fw-semibold">{{ $richiesta['richiesta'] }}</a>
                            </td>
                            <td>
                                <span class="badge {{ match ($richiesta['urgenza_valore']) {
                                    'alta' => 'bg-danger',
                                    'media' => 'bg-warning text-dark',
                                    'bassa' => 'bg-success',
                                    default => 'bg-secondary',
                                } }}">
                                    {{ $richiesta['urgenza'] }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $richiesta['tipo_richiesta'] === 'documento' ? 'bg-primary' : '' }}" {{ $richiesta['tipo_richiesta'] === 'dotazione' ? 'style=background-color:#a8d8ea;color:#000;' : '' }}>
                                    {{ $richiesta['tipo_richiesta'] === 'documento' ? 'Documento' : 'Dotazione' }}
                                </span>
                            </td>
                            <td>
                                @if ($richiesta['risolta'])
                                    <span class="badge bg-success fs-6">Risolta</span>
                                @else
                                    <form method="POST" action="{{ $richiesta['tipo_richiesta'] === 'documento' ? route('documenti.risolvi', ['anagrafica' => $richiesta['model']->anagrafica, 'documento' => $richiesta['model']]) : route('dotazioni.risolvi', ['anagrafica' => $richiesta['model']->anagrafica, 'dotazione' => $richiesta['model']]) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="stato_richiesta" class="form-select form-select-sm" onchange="this.form.submit()">
                                            <option value="non_risolta" @selected($richiesta['stato_richiesta'] === 'non_risolta')>Non risolta</option>
                                            <option value="in_risoluzione" @selected($richiesta['stato_richiesta'] === 'in_risoluzione')>In risoluzione</option>
                                            <option value="risolta" @selected($richiesta['stato_richiesta'] === 'risolta')>Risolta</option>
                                        </select>
                                    </form>
                                @endif
                            </td>
                            <td>{{ $richiesta['dipendente'] }}</td>
                            <td>{{ $richiesta['stato_dipendente'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                Nessuna richiesta trovata.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection