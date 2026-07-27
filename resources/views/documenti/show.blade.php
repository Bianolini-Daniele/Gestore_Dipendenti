@extends('layouts.app')

@section('title', 'Dettaglio documento')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="mb-1">Dettaglio documento</h1>
            <p class="text-muted mb-0">{{ $documento->nome }}</p>
        </div>
        <a href="{{ route(\App\Http\Controllers\AccessoController::rottaHomepage(session('area_accesso'))) }}" class="btn btn-outline-secondary">Torna alla home</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body row g-3">
            <div class="col-md-6">
                <div class="text-muted small">Nome</div>
                <div class="fw-semibold">{{ $documento->nome }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Tipo documento</div>
                <div class="fw-semibold">{{ $documento->tipo_documento }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Stato</div>
                <div class="fw-semibold">{{ 
                    $documento->stato === 'richiesta' ? 'Richiesta' : ucfirst($documento->stato)
                }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Responsabilità</div>
                <div class="fw-semibold">{{ $documento->responsabilita ?? 'N/D' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Urgenza</div>
                <div class="fw-semibold">{{ $documento->urgenza ? (\App\Models\Documento::URGENZE[$documento->urgenza] ?? ucfirst($documento->urgenza)) : 'Non specificata' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Stato richiesta</div>
                <div class="fw-semibold">{{ match ($documento->stato_richiesta) {
                    'risolta' => 'Risolta',
                    'in_risoluzione' => 'In risoluzione',
                    default => 'Non risolta',
                } }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Dipendente</div>
                <div class="fw-semibold">{{ $documento->anagrafica?->nome_completo ?? 'N/D' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Scadenza</div>
                <div class="fw-semibold">{{ $documento->scadenza ? $documento->scadenza->format('d/m/Y') : 'N/D' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Anno conseguimento</div>
                <div class="fw-semibold">{{ $documento->anno_conseguimento ?? 'N/D' }}</div>
            </div>
            <div class="col-12">
                <div class="text-muted small">Note</div>
                <div class="fw-semibold">{{ $documento->note ?: 'Nessuna nota' }}</div>
            </div>
        </div>
    </div>

    @if ($documento->file_path)
        <div class="d-flex gap-2">
            <a href="{{ route('documenti.download', $documento) }}" class="btn btn-primary">Scarica file</a>
            <a href="{{ route('documenti.visualizza', $documento) }}" class="btn btn-outline-primary" target="_blank">Visualizza file</a>
        </div>
    @endif
@endsection
