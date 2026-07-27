@php
    $stato = old("dotazioni.$indice.stato", $dotazione?->stato ?? 'in uso');
    $urgenza = old("dotazioni.$indice.urgenza", $dotazione?->urgenza);
    $risolto = old("dotazioni.$indice.risolto", $dotazione?->risolto);
@endphp

<div class="border rounded p-3 mb-3 riga-dotazione" data-id="{{ $dotazione?->id }}">
    @if ($dotazione)
        <input type="hidden" name="dotazioni[{{ $indice }}][id]" value="{{ $dotazione->id }}">
    @endif

    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Tipo dotazione *</label>
            <input type="text" name="dotazioni[{{ $indice }}][tipo_dotazione]" class="form-control" value="{{ old("dotazioni.$indice.tipo_dotazione", $dotazione?->tipo_dotazione) }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Marca</label>
            <input type="text" name="dotazioni[{{ $indice }}][marca]" class="form-control" value="{{ old("dotazioni.$indice.marca", $dotazione?->marca) }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Modello</label>
            <input type="text" name="dotazioni[{{ $indice }}][modello]" class="form-control" value="{{ old("dotazioni.$indice.modello", $dotazione?->modello) }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Stato *</label>
            <select name="dotazioni[{{ $indice }}][stato]" class="form-select riga-stato">
                @foreach (\App\Models\Dotazione::STATI as $valore => $etichetta)
                    <option value="{{ $valore }}" @selected($stato === $valore)>{{ $etichetta }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 riga-urgenza-wrapper">
            <label class="form-label">Urgenza</label>
            <select name="dotazioni[{{ $indice }}][urgenza]" class="form-select">
                <option value="">—</option>
                @foreach (\App\Models\Dotazione::URGENZE as $valore => $etichetta)
                    <option value="{{ $valore }}" @selected($urgenza === $valore)>{{ $etichetta }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 riga-responsabilità-wrapper">
            <label class="form-label">Responsabilità *</label>
            <select name="dotazione[{{ $indice }}][responsabilità]" class="form-select">
                <option value="">—</option>
                <option value="IT" @selected(old('responsabilita', $d?->responsabilita) === 'IT')>IT</option>
                <option value="Admin" @selected(old('responsabilita', $d?->responsabilita) === 'Admin')>Amministrazione</option>
                <option value="Altri" @selected(old('responsabilita', $d?->responsabilita) === 'Altri')>Altri</option>
            </select>
        </div>

        <div class="col-md-4 riga-stato-wrapper">
            <label class="form-label">Stato richiesta</label>
            <select name="dotazione[{{ $indice }}][stato]" class="form-select">
                <option value="non_risolta" @selected(old('stato_richiesta', $d?->stato_richiesta ?? 'non_risolta') === 'non_risolta')>Non risolta</option>
                <option value="in_risoluzione" @selected(old('stato_richiesta', $d?->stato_richiesta ?? 'non_risolta') === 'in_risoluzione')>In risoluzione</option>
                <option value="risolta" @selected(old('stato_richiesta', $d?->stato_richiesta ?? 'non_risolta') === 'risolta')>Risolta</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Numero identificativo</label>
            <input type="text" name="dotazioni[{{ $indice }}][numero_identificativo]" class="form-control" value="{{ old("dotazioni.$indice.numero_identificativo", $dotazione?->numero_identificativo) }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Data consegna</label>
            <input type="date" name="dotazioni[{{ $indice }}][data_consegna]" class="form-control" value="{{ old("dotazioni.$indice.data_consegna", $dotazione?->data_consegna?->format('Y-m-d')) }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Data restituzione</label>
            <input type="date" name="dotazioni[{{ $indice }}][data_restituzione]" class="form-control" value="{{ old("dotazioni.$indice.data_restituzione", $dotazione?->data_restituzione?->format('Y-m-d')) }}">
        </div>

        <div class="col-12">
            <label class="form-label">Note</label>
            <textarea name="dotazioni[{{ $indice }}][note]" rows="2" class="form-control">{{ old("dotazioni.$indice.note", $dotazione?->note) }}</textarea>
        </div>

        <div class="col-12 text-end">
            <button type="button" class="btn btn-sm btn-outline-danger rimuovi-riga">Rimuovi</button>
        </div>
    </div>
</div>