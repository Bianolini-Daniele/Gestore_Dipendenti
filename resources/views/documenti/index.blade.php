@extends('layouts.app')

@section('title', 'Documenti — ' . $anagrafica->nome_completo)

@php
    $isHR = session('area_accesso') === 'HR';

    $badgeUrgenza = [
        'bassa' => 'bg-secondary',
        'media' => 'bg-warning text-dark',
        'alta' => 'bg-danger',
    ];
@endphp

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Documenti di {{ $anagrafica->nome_completo }}</h1>

        @if ($isHR)
            <a href="{{ route('anagrafiche.documenti.create', $anagrafica) }}" class="btn btn-primary">
                Aggiungi documento
            </a>
        @endif
    </div>

    <div class="card shadow-sm">
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

                                <span class="badge {{ $documento->risolto ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $documento->risolto ? 'Risolto' : 'Aperto' }}
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
                                <form action="{{ route('documenti.risolvi', [$anagrafica, $documento]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="stato_richiesta" value="risolta">
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        Segna risolto
                                    </button>
                                </form>
                            @endif

                            @if ($documento->file_path)
                                <a href="{{ route('documenti.visualizza', $documento) }}" class="btn btn-sm btn-outline-primary" target="_blank">Visualizza file</a>
                                <a href="{{ route('documenti.download', $documento) }}" class="btn btn-sm btn-outline-secondary">Scarica</a>
                            @endif

                            @if ($isHR)
                                <a href="{{ route('anagrafiche.documenti.edit', ['anagrafica' => $anagrafica, 'documento' => $documento]) }}" class="btn btn-sm btn-outline-primary">Modifica</a>

                                <form action="{{ route('anagrafiche.documenti.destroy', [$anagrafica, $documento]) }}" method="POST" onsubmit="return confirm('Eliminare il documento?')">
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

    <a href="{{ route('anagrafiche.index') }}" class="btn btn-secondary mt-3">Torna all'elenco</a>
@endsection