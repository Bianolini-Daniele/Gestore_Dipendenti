@extends('layouts.app')

@section('title', 'Dettagli dipendente')

@php
    $isHR = session('area_accesso') === 'HR';
    $isDisabilitato = $anagrafica->isDisabilitato();

    $badgeStato = [
        'on_boarding' => 'bg-info text-dark',
        'dipendente' => 'bg-success',
        'off_boarding' => 'bg-secondary',
        'disabilitato' => 'bg-dark',
    ][$anagrafica->stato_dipendente] ?? 'bg-secondary';

    $badgeUrgenza = [
        'bassa' => 'bg-secondary',
        'media' => 'bg-warning text-dark',
        'alta' => 'bg-danger',
    ];
@endphp

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="d-inline-block me-2">{{ $anagrafica->nome_completo }}</h1>
            <span class="badge {{ $badgeStato }}">{{ $anagrafica->stato_dipendente_etichetta }}</span>
        </div>

        @if ($isHR)
            <div class="d-flex gap-2">
                @if ($isDisabilitato)
                    <form action="{{ route('anagrafiche.riattiva', $anagrafica) }}" method="POST" onsubmit="return confirm('Riportare {{ $anagrafica->nome_completo }} in Off Boarding?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-secondary">Riattiva (torna a Off Boarding)</button>
                    </form>
                @else
                    <a href="{{ route('anagrafiche.edit', $anagrafica) }}" class="btn btn-primary">Modifica</a>

                    <form action="{{ route('anagrafiche.disabilita', $anagrafica) }}" method="POST" onsubmit="return confirm('Disabilitare {{ $anagrafica->nome_completo }}? Non sarà più visibile nell\'elenco standard e non sarà più modificabile.')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-danger">Disabilita</button>
                    </form>
                @endif
            </div>
        @endif
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-4">Codice fiscale</dt>
                <dd class="col-md-8">{{ $anagrafica->codice_fiscale }}</dd>

                <dt class="col-md-4">E-mail personale</dt>
                <dd class="col-md-8">{{ $anagrafica->mail_personale ?: '-' }}</dd>

                <dt class="col-md-4">Telefono</dt>
                <dd class="col-md-8">{{ $anagrafica->telefono ?: '-' }}</dd>

                <dt class="col-md-4">Reparto</dt>
                <dd class="col-md-8">{{ $anagrafica->reparto ?: '-' }}</dd>

                <dt class="col-md-4">Mansione</dt>
                <dd class="col-md-8">{{ $anagrafica->mansione ?: '-' }}</dd>

                <dt class="col-md-4">Data assunzione</dt>
                <dd class="col-md-8">{{ $anagrafica->data_assunzione?->format('d/m/Y') ?? '-' }}</dd>

                <dt class="col-md-4">Primo giorno lavorativo</dt>
                <dd class="col-md-8">{{ $anagrafica->primo_giorno_lavorativo?->format('d/m/Y') ?? '-' }}</dd>

                <dt class="col-md-4">Data licenziamento/dimissione</dt>
                <dd class="col-md-8">{{ $anagrafica->data_cessazione?->format('d/m/Y') ?? '-' }}</dd>

                <dt class="col-md-4">Ultimo giorno lavorativo</dt>
                <dd class="col-md-8">{{ $anagrafica->ultimo_giorno_lavorativo?->format('d/m/Y') ?? '-' }}</dd>
            </dl>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header">
            <strong>Documenti anagrafici disponibili</strong>
        </div>

        <div class="card-body d-flex gap-2 flex-wrap">
            @php
                $allegatiFissi = [
                    'carta_identita' => ['label' => "Carta d'identità", 'file' => $anagrafica->carta_identita_file],
                    'cud' => ['label' => 'CUD', 'file' => $anagrafica->cud_file],
                    'corso_sicurezza' => ['label' => 'Corso sicurezza', 'file' => $anagrafica->corso_sicurezza_file],
                    'visita_medica' => ['label' => 'Visita medica', 'file' => $anagrafica->visita_medica_file],
                    'cv' => ['label' => 'CV', 'file' => $anagrafica->cv_file],
                ];
            @endphp

            @foreach ($allegatiFissi as $tipo => $info)
                @if ($info['file'])
                    <div class="btn-group">
                        <a class="btn btn-outline-primary" href="{{ route('anagrafiche.documenti.visualizza', ['anagrafica' => $anagrafica, 'tipo' => $tipo]) }}" target="_blank">{{ $info['label'] }} — Visualizza</a>
                        <a class="btn btn-outline-secondary" href="{{ route('anagrafiche.documenti.download', ['anagrafica' => $anagrafica, 'tipo' => $tipo]) }}">Scarica</a>
                    </div>
                @endif
            @endforeach

            @if (collect($allegatiFissi)->every(fn ($i) => !$i['file']))
                <span>Nessun documento caricato.</span>
            @endif
        </div>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>
                @if ($anagrafica->documenti->where('stato', 'richiesta')->where('risolto', false)->isNotEmpty())
                    <img src="{{ asset('images/richiesta.png') }}" alt="Richiesta" width="18" height="18">
                @endif
                Documenti
            </strong>
            @if ($isHR && !$isDisabilitato)
                <a href="{{ route('anagrafiche.documenti.create', $anagrafica) }}" class="btn btn-sm btn-primary">Aggiungi documento</a>
            @endif
        </div>

        <ul class="list-group list-group-flush">
            @forelse ($anagrafica->documenti as $documento)
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <strong>{{ $documento->nome }}</strong>
                            <span class="text-muted">— {{ $documento->tipo_documento }}</span>

                            <span class="badge bg-light text-dark border ms-1">
                                {{ \App\Models\Documento::STATI[$documento->stato] ?? $documento->stato }}
                            </span>

                            @if ($documento->stato === 'richiesta')
                                @if ($documento->urgenza)
                                    <span class="badge {{ $badgeUrgenza[$documento->urgenza] ?? 'bg-secondary' }}">
                                        Urgenza: {{ \App\Models\Documento::URGENZE[$documento->urgenza] ?? $documento->urgenza }}
                                    </span>
                                @endif

                                @if ($documento->responsabilita)
                                    <span class="badge bg-info text-dark">
                                        Responsabilità: {{ $documento->responsabilita }}
                                    </span>
                                @endif

                                <span class="badge {{ $documento->risolto ? 'bg-success' : ($documento->stato_richiesta === 'in_risoluzione' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                    {{ $documento->stato_richiesta === 'risolta' ? 'Risolto' : ($documento->stato_richiesta === 'in_risoluzione' ? 'In risoluzione' : 'Non risolta') }}
                                </span>
                            @endif

                            @if ($documento->scadenza)
                                <span class="text-muted d-block small">Scadenza: {{ $documento->scadenza->format('d/m/Y') }}</span>
                            @endif

                            @if ($documento->anno_conseguimento)
                                <span class="text-muted d-block small">Anno conseguimento: {{ $documento->anno_conseguimento }}</span>
                            @endif

                            @if ($documento->note)
                                <span class="text-muted d-block small">Note: {{ $documento->note }}</span>
                            @endif
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            @if ($documento->stato === 'richiesta')
                                <form action="{{ route('documenti.risolvi', ['anagrafica' => $anagrafica, 'documento' => $documento]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="stato_richiesta" value="{{ $documento->risolto ? 'non_risolta' : 'risolta' }}">
                                    <button type="submit" class="btn btn-sm {{ $documento->risolto ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                        {{ $documento->risolto ? 'Riapri richiesta' : 'Segna risolto' }}
                                    </button>
                                </form>
                            @endif

                            @if ($documento->file_path)
                                <a href="{{ route('documenti.visualizza', ['documento' => $documento]) }}" class="btn btn-sm btn-outline-primary" target="_blank">Visualizza file</a>
                                <a href="{{ route('documenti.download', ['documento' => $documento]) }}" class="btn btn-sm btn-outline-secondary">Scarica</a>
                            @endif

                            <a href="{{ route('documenti.show', $documento) }}" class="btn btn-sm btn-outline-info">Dettaglio</a>

                            @if ($isHR && !$isDisabilitato)
                                <a href="{{ route('anagrafiche.documenti.edit', ['anagrafica' => $anagrafica, 'documento' => $documento]) }}" class="btn btn-sm btn-outline-primary">Modifica</a>

                                <form action="{{ route('anagrafiche.documenti.destroy', ['anagrafica' => $anagrafica, 'documento' => $documento]) }}" method="POST" onsubmit="return confirm('Eliminare il documento?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Elimina</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="list-group-item">Nessun documento caricato.</li>
            @endforelse
        </ul>
    </div>

    <div class="card shadow-sm mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>
                @if ($anagrafica->dotazioni->where('stato', 'richiesta')->where('risolto', false)->isNotEmpty())
                    <img src="{{ asset('images/richiesta.png') }}" alt="Richiesta" width="18" height="18">
                @endif
                Dotazioni
            </strong>
            @if ($isHR && !$isDisabilitato)
                <a href="{{ route('anagrafiche.dotazioni.create', $anagrafica) }}" class="btn btn-sm btn-primary">Aggiungi dotazione</a>
            @endif
        </div>

        <ul class="list-group list-group-flush">
            @forelse ($anagrafica->dotazioni as $dotazione)
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <strong>{{ $dotazione->tipo_dotazione }}</strong>
                            <span class="text-muted">— {{ $dotazione->marca }} {{ $dotazione->modello }}</span>

                            <span class="badge bg-light text-dark border ms-1">
                                {{ \App\Models\Dotazione::STATI[$dotazione->stato] ?? $dotazione->stato }}
                            </span>

                            @if ($dotazione->stato === 'richiesta')
                                @if ($dotazione->urgenza)
                                    <span class="badge {{ $badgeUrgenza[$dotazione->urgenza] ?? 'bg-secondary' }}">
                                        Urgenza: {{ \App\Models\Dotazione::URGENZE[$dotazione->urgenza] ?? $dotazione->urgenza }}
                                    </span>
                                @endif

                                @if ($dotazione->responsabilita)
                                    <span class="badge bg-info text-dark">
                                        Responsabilità: {{ $dotazione->responsabilita }}
                                    </span>
                                @endif

                                <span class="badge {{ $dotazione->risolto ? 'bg-success' : ($dotazione->stato_richiesta === 'in_risoluzione' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                    {{ $dotazione->stato_richiesta === 'risolta' ? 'Risolto' : ($dotazione->stato_richiesta === 'in_risoluzione' ? 'In risoluzione' : 'Non risolta') }}
                                </span>
                            @endif

                            @if ($dotazione->numero_identificativo)
                                <span class="text-muted d-block small">N. identificativo: {{ $dotazione->numero_identificativo }}</span>
                            @endif

                            @if ($dotazione->note)
                                <span class="text-muted d-block small">Note: {{ $dotazione->note }}</span>
                            @endif
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            @if ($dotazione->stato === 'richiesta')
                                <form action="{{ route('dotazioni.risolvi', ['anagrafica' => $anagrafica, 'dotazione' => $dotazione]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="stato_richiesta" value="{{ $dotazione->risolto ? 'non_risolta' : 'risolta' }}">
                                    <button type="submit" class="btn btn-sm {{ $dotazione->risolto ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                        {{ $dotazione->risolto ? 'Riapri richiesta' : 'Segna risolto' }}
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('dotazioni.show', $dotazione) }}" class="btn btn-sm btn-outline-info">Dettaglio</a>

                            @if ($isHR && !$isDisabilitato)
                                <a href="{{ route('anagrafiche.dotazioni.edit', ['anagrafica' => $anagrafica, 'dotazione' => $dotazione]) }}" class="btn btn-sm btn-outline-primary">Modifica</a>

                                <form action="{{ route('anagrafiche.dotazioni.destroy', ['anagrafica' => $anagrafica, 'dotazione' => $dotazione]) }}" method="POST" onsubmit="return confirm('Eliminare la dotazione?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Elimina</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="list-group-item">Nessuna dotazione assegnata.</li>
            @endforelse
        </ul>
    </div>

<a href="{{ route('anagrafiche.index', $isDisabilitato ? ['stato' => 'disabilitati'] : []) }}" class="btn btn-secondary mt-3">Torna all'elenco</a>
@endsection