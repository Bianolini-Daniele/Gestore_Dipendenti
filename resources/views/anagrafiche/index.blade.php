@extends('layouts.app')

@section('title', 'Anagrafica dipendenti')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Anagrafica dipendenti</h1>

        <a href="{{ route('anagrafiche.create') }}" class="btn btn-primary">Nuovo dipendente</a>
    </div>

    <form method="GET" action="{{ route('anagrafiche.index') }}" class="row g-2 mb-3">
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
                        <th style="min-width: 260px;">
                            <div class="d-flex align-items-center justify-content-between gap-2 w-100">
                                <span>Dipendente</span>
                                <form method="GET" action="{{ route('anagrafiche.index') }}" class="mb-0">
                                    <input type="hidden" name="search" value="{{ $search }}">
                                    <select name="stato" class="form-select form-select-sm w-auto" style="background-color: #cfe2ff; border-color: #b6d4fe;" onchange="this.form.submit()">
                                        <option value="tutti" @selected($filtro === 'tutti')>Tutti</option>
                                        @foreach (\App\Models\Anagrafica::STATI_DIPENDENTE as $valore => $etichetta)
                                            <option value="{{ $valore }}" @selected($filtro === $valore)>{{ $etichetta }}</option>
                                        @endforeach
                                        @if ($isHR)
                                            <option value="disabilitati" @selected($filtro === 'disabilitati')>Disabilitati</option>
                                        @endif
                                    </select>
                                </form>
                            </div>
                        </th>
                        <th class="text-center">Documenti</th>
                        <th class="text-center">Dotazioni</th>
                        <th>Stato</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($anagrafiche as $anagrafica)
                        <tr>
                            <td>
                                <a href="{{ route('anagrafiche.show', $anagrafica) }}">{{ $anagrafica->nome_completo }}</a>
                            </td>
                            <td class="text-center">
                                @if ($anagrafica->documenti_richiesta_count > 0)<img src="{{ asset('images/richiesta.png') }}" alt="Richiesta" width="18" height="18">@endif
                                @if ($anagrafica->documenti_restituzione_count > 0)<img src="{{ asset('images/restituzione.png') }}" alt="Restituzione" width="18" height="18">@endif
                                <a href="{{ route('anagrafiche.documenti.index', $anagrafica) }}" class="{{ ($anagrafica->documenti_richiesta_count + $anagrafica->documenti_restituzione_count) > 0 ? 'link-richiesta' : '' }}">Documenti</a>
                            </td>

                            <td class="text-center">
                                @if ($anagrafica->dotazioni_richiesta_count > 0)<img src="{{ asset('images/richiesta.png') }}" alt="Richiesta" width="18" height="18">@endif
                                @if ($anagrafica->dotazioni_restituzione_count > 0)<img src="{{ asset('images/restituzione.png') }}" alt="Restituzione" width="18" height="18">@endif
                                <a href="{{ route('anagrafiche.dotazioni.index', $anagrafica) }}" class="{{ ($anagrafica->dotazioni_richiesta_count + $anagrafica->dotazioni_restituzione_count) > 0 ? 'link-richiesta' : '' }}">Dotazioni</a>
                            </td>

                            <td>
                                @if ($anagrafica->isDisabilitato())
                                    <span class="badge bg-dark">Disabilitato</span>
                                @else
                                    <form method="POST" action="{{ route('anagrafiche.stato', $anagrafica) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="stato_dipendente" class="form-select form-select-sm" onchange="this.form.submit()">
                                            @foreach (\App\Models\Anagrafica::STATI_DIPENDENTE as $valore => $etichetta)
                                                <option value="{{ $valore }}" @selected($anagrafica->stato_dipendente === $valore)>{{ $etichetta }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                Nessun dipendente trovato per il filtro selezionato.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $anagrafiche->links() }}
    </div>
@endsection