@php
    $d = $dotazione ?? null;
@endphp

<div class="card shadow-sm mb-4">
    <div class="card-body row g-3">
        <div class="col-md-6">
            <label class="form-label">Tipo dotazione *</label>
            <input
                type="text"
                name="tipo_dotazione"
                class="form-control"
                value="{{ old('tipo_dotazione', $d?->tipo_dotazione) }}"
                required
            >
        </div>

        <div class="col-md-6">
            <label class="form-label">Stato *</label>
            <select name="stato" id="dotazione-stato" class="form-select" required>
                @foreach (\App\Models\Dotazione::STATI as $valore => $etichetta)
                    <option
                        value="{{ $valore }}"
                        @selected(old('stato', $d?->stato) === $valore)
                    >
                        {{ $etichetta }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6" id="dotazione-urgenza-wrapper">
            <label class="form-label">Urgenza</label>
            <select name="urgenza" class="form-select">
                <option value="">—</option>
                @foreach (\App\Models\Dotazione::URGENZE as $valore => $etichetta)
                    <option value="{{ $valore }}" @selected(old('urgenza', $d?->urgenza) === $valore)>
                        {{ $etichetta }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6" id="dotazione-responsabilita-wrapper">
            <label class="form-label">Responsabilità *</label>
            <select name="responsabilita" class="form-select" required>
                <option value="">—</option>
                <option value="IT" @selected(old('responsabilita', $d?->responsabilita) === 'IT')>IT</option>
                <option value="Admin" @selected(old('responsabilita', $d?->responsabilita) === 'Admin')>Amministrazione</option>
                <option value="Altri" @selected(old('responsabilita', $d?->responsabilita) === 'Altri')>Altri</option>
            </select>
        </div>

        <div class="col-md-6" id="dotazione-stato-richiesta-wrapper">
            <label class="form-label">Stato richiesta</label>
            <select name="stato_richiesta" class="form-select">
                <option value="non_risolta" @selected(old('stato_richiesta', $d?->stato_richiesta ?? 'non_risolta') === 'non_risolta')>Non risolta</option>
                <option value="in_risoluzione" @selected(old('stato_richiesta', $d?->stato_richiesta ?? 'non_risolta') === 'in_risoluzione')>In risoluzione</option>
                <option value="risolta" @selected(old('stato_richiesta', $d?->stato_richiesta ?? 'non_risolta') === 'risolta')>Risolta</option>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Marca</label>
            <input
                type="text"
                name="marca"
                class="form-control"
                value="{{ old('marca', $d?->marca) }}"
            >
        </div>

        <div class="col-md-6">
            <label class="form-label">Modello</label>
            <input
                type="text"
                name="modello"
                class="form-control"
                value="{{ old('modello', $d?->modello) }}"
            >
        </div>

        <div class="col-md-6">
            <label class="form-label">Numero identificativo</label>
            <input
                type="text"
                name="numero_identificativo"
                class="form-control"
                value="{{ old('numero_identificativo', $d?->numero_identificativo) }}"
            >
        </div>

        <div class="col-md-3">
            <label class="form-label">Data consegna</label>
            <input
                type="date"
                name="data_consegna"
                class="form-control"
                value="{{ old('data_consegna', $d?->data_consegna?->format('Y-m-d')) }}"
            >
        </div>

        <div class="col-md-3">
            <label class="form-label">Data restituzione</label>
            <input
                type="date"
                name="data_restituzione"
                class="form-control"
                value="{{ old('data_restituzione', $d?->data_restituzione?->format('Y-m-d')) }}"
            >
        </div>

        <div class="col-12">
            <label class="form-label">Note</label>
            <textarea name="note" rows="3" class="form-control">{{ old('note', $d?->note) }}</textarea>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary">Salva</button>
<a href="{{ route('anagrafiche.show', $anagrafica) }}" class="btn btn-secondary">Annulla</a>

<script>
    (function () {
        const stato = document.getElementById('dotazione-stato');
        const urgenzaWrapper = document.getElementById('dotazione-urgenza-wrapper');
        const responsabilitaWrapper = document.getElementById('dotazione-responsabilita-wrapper');
        const statoRichiestaWrapper = document.getElementById('dotazione-stato-richiesta-wrapper');

        function aggiornaVisibilitaRichiesta() {
            const mostra = stato.value === 'richiesta';
            urgenzaWrapper.style.display = mostra ? '' : 'none';
            responsabilitaWrapper.style.display = mostra ? '' : 'none';
            statoRichiestaWrapper.style.display = mostra ? '' : 'none';
        }

        stato.addEventListener('change', aggiornaVisibilitaRichiesta);
        aggiornaVisibilitaRichiesta();
    })();
</script>