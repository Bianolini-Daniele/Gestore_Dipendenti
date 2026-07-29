@extends('layouts.app')

@section('title', 'Dotazioni — ' . $anagrafica->nome_completo)

@php
    $isHR = session('area_accesso') === 'HR';
    $isDisabilitato = $anagrafica->isDisabilitato();

    $badgeUrgenza = [
        'bassa' => 'bg-secondary',
        'media' => 'bg-warning text-dark',
        'alta' => 'bg-danger',
    ];
@endphp

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Dotazioni di {{ $anagrafica->nome_completo }}</h1>

        @if ($isHR)
            <a href="{{ route('anagrafiche.dotazioni.create', $anagrafica) }}" class="btn btn-primary">
                Aggiungi dotazione
            </a>
        @endif
    </div>

    <div class="card shadow-sm">
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

                            @if (in_array($dotazione->stato, \App\Models\Dotazione::STATI_RICHIESTA_TIPO))
                                @if ($dotazione->urgenza)
                                    <span class="badge {{ $badgeUrgenza[$dotazione->urgenza] ?? 'bg-secondary' }}">
                                        Urgenza: {{ \App\Models\Dotazione::URGENZE[$dotazione->urgenza] ?? $dotazione->urgenza }}
                                    </span>
                                @endif

                                <span class="badge {{ $dotazione->risolto ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $dotazione->risolto ? 'Risolto' : 'Aperto' }}
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
                            @if (in_array($dotazione->stato, \App\Models\Dotazione::STATI_RICHIESTA_TIPO))
                                <form action="{{ route('dotazioni.risolvi', [$anagrafica, $dotazione]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="stato_richiesta" value="risolta">
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        Segna risolto
                                    </button>
                                </form>
                            @endif

                            @if ($isHR)
                                <a href="{{ route('anagrafiche.dotazioni.edit', ['anagrafica' => $anagrafica, 'dotazione' => $dotazione]) }}" class="btn btn-sm btn-outline-primary">Modifica</a>

                                <form action="{{ route('anagrafiche.dotazioni.destroy', [$anagrafica, $dotazione]) }}" method="POST" onsubmit="return confirm('Eliminare la dotazione?')">
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