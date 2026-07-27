@extends('layouts.app')

@section('title', 'Dettaglio dotazione')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="mb-1">Dettaglio dotazione</h1>
            <p class="text-muted mb-0">{{ $dotazione->tipo_dotazione }}</p>
        </div>
        <a href="{{ route(\App\Http\Controllers\AccessoController::rottaHomepage(session('area_accesso'))) }}" class="btn btn-outline-secondary">Torna alla home</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body row g-3">
            <div class="col-md-6">
                <div class="text-muted small">Tipo dotazione</div>
                <div class="fw-semibold">{{ $dotazione->tipo_dotazione }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Marca</div>
                <div class="fw-semibold">{{ $dotazione->marca ?: 'N/D' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Modello</div>
                <div class="fw-semibold">{{ $dotazione->modello ?: 'N/D' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Numero identificativo</div>
                <div class="fw-semibold">{{ $dotazione->numero_identificativo ?: 'N/D' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Stato</div>
                <div class="fw-semibold">{{ $dotazione->stato === 'richiesta' ? 'Richiesta' : ucfirst($dotazione->stato) }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Responsabilità</div>
                <div class="fw-semibold">{{ $dotazione->responsabilita ?? 'N/D' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Urgenza</div>
                <div class="fw-semibold">{{ $dotazione->urgenza ? (\App\Models\Dotazione::URGENZE[$dotazione->urgenza] ?? ucfirst($dotazione->urgenza)) : 'Non specificata' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Stato richiesta</div>
                <div class="fw-semibold">{{ match ($dotazione->stato_richiesta) {
                    'risolta' => 'Risolta',
                    'in_risoluzione' => 'In risoluzione',
                    default => 'Non risolta',
                } }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Dipendente</div>
                <div class="fw-semibold">{{ $dotazione->anagrafica?->nome_completo ?? 'N/D' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Data consegna</div>
                <div class="fw-semibold">{{ $dotazione->data_consegna ? $dotazione->data_consegna->format('d/m/Y') : 'N/D' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Data restituzione</div>
                <div class="fw-semibold">{{ $dotazione->data_restituzione ? $dotazione->data_restituzione->format('d/m/Y') : 'N/D' }}</div>
            </div>
            <div class="col-12">
                <div class="text-muted small">Note</div>
                <div class="fw-semibold">{{ $dotazione->note ?: 'Nessuna nota' }}</div>
            </div>
        </div>
    </div>
@endsection
