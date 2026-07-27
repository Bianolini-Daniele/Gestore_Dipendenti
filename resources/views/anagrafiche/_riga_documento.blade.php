@php
    $stato = old("documenti.$indice.stato", $documento?->stato ?? 'in uso');
    $urgenza = old("documenti.$indice.urgenza", $documento?->urgenza);
    $risolto = old("documenti.$indice.risolto", $documento?->risolto);
@endphp

<div class="border rounded p-3 mb-3 riga-documento" data-id="{{ $documento?->id }}">
    @if ($documento)
        <input type="hidden" name="documenti[{{ $indice }}][id]" value="{{ $documento->id }}">
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Nome *</label>
            <input type="text" name="documenti[{{ $indice }}][nome]" class="form-control" value="{{ old("documenti.$indice.nome", $documento?->nome) }}">
        </div>

        <div class="col-md-6">
            <label class="form-label">Tipo documento *</label>
            <input type="text" name="documenti[{{ $indice }}][tipo_documento]" class="form-control" placeholder="Es. Contratto, Attestato..." value="{{ old("documenti.$indice.tipo_documento", $documento?->tipo_documento) }}">
        </div>

        <div class="col-md-12">
            <label class="form-label">File {{ $documento?->file_path ? '(lascia vuoto per non modificarlo)' : '(facoltativo se lo stato è "Richiesta")' }}</label>
            <input type="file" name="documenti[{{ $indice }}][file]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
            @if ($documento?->file_path)
                <a href="{{ route('documenti.visualizza', $documento) }}" target="_blank" class="small d-block mt-1">Visualizza file attuale</a>
            @endif
        </div>

        <div class="col-md-4">
            <label class="form-label">Stato *</label>
            <select name="documenti[{{ $indice }}][stato]" class="form-select riga-stato">
                @foreach (\App\Models\Documento::STATI as $valore => $etichetta)
                    <option value="{{ $valore }}" @selected($stato === $valore)>{{ $etichetta }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 riga-urgenza-wrapper">
            <label class="form-label">Urgenza</label>
            <select name="documenti[{{ $indice }}][urgenza]" class="form-select">
                <option value="">—</option>
                @foreach (\App\Models\Documento::URGENZE as $valore => $etichetta)
                    <option value="{{ $valore }}" @selected($urgenza === $valore)>{{ $etichetta }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 riga-responsabilità-wrapper">
            <label class="form-label">Responsabilità *</label>
            <select name="documenti[{{ $indice }}][responsabilita]" class="form-select">
                <option value="">—</option>
                @foreach (\App\Models\Documento::RESPONSABILITA as $valore => $etichetta)
                    <option value="{{ $valore }}" @selected(old("documenti.$indice.responsabilita", $documento?->responsabilita) === $valore)>{{ $etichetta }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 riga-stato-wrapper">
            <label class="form-label">Stato richiesta</label>
            <select name="documenti[{{ $indice }}][stato_richiesta]" class="form-select">
                <option value="non_risolta" @selected(old("documenti.$indice.stato_richiesta", $documento?->stato_richiesta ?? 'non_risolta') === 'non_risolta')>Non risolta</option>
                <option value="in_risoluzione" @selected(old("documenti.$indice.stato_richiesta", $documento?->stato_richiesta ?? 'non_risolta') === 'in_risoluzione')>In risoluzione</option>
                <option value="risolta" @selected(old("documenti.$indice.stato_richiesta", $documento?->stato_richiesta ?? 'non_risolta') === 'risolta')>Risolta</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Scadenza (eventuale)</label>
            <input type="date" name="documenti[{{ $indice }}][scadenza]" class="form-control" value="{{ old("documenti.$indice.scadenza", $documento?->scadenza?->format('Y-m-d')) }}">
        </div>

        <div class="col-md-6">
            <label class="form-label">Anno di conseguimento (eventuale)</label>
            <input type="number" name="documenti[{{ $indice }}][anno_conseguimento]" class="form-control" min="1950" max="{{ date('Y') + 1 }}" value="{{ old("documenti.$indice.anno_conseguimento", $documento?->anno_conseguimento) }}">
        </div>

        <div class="col-12">
            <label class="form-label">Note</label>
            <textarea name="documenti[{{ $indice }}][note]" rows="1" class="form-control">{{ old("documenti.$indice.note", $documento?->note) }}</textarea>
        </div>

        <div class="col-12 text-end">
            <button type="button" class="btn btn-sm btn-outline-danger rimuovi-riga">Rimuovi</button>
        </div>
    </div>
</div>