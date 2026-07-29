@php
    $d = $documento ?? null;
@endphp

<div class="card shadow-sm mb-4">
    <div class="card-body row g-3">
        <div class="col-md-6">
            <label class="form-label">Nome *</label>
            <input
                type="text"
                name="nome"
                class="form-control"
                value="{{ old('nome', $d?->nome) }}"
                required
            >
        </div>

        <div class="col-md-6">
            <label class="form-label">Tipo documento *</label>
            <input
                type="text"
                name="tipo_documento"
                class="form-control"
                placeholder="Es. Contratto, Attestato, Certificato..."
                value="{{ old('tipo_documento', $d?->tipo_documento) }}"
                required
            >
        </div>

        <div class="col-md-4">
            <label class="form-label">Stato *</label>
            <select name="stato" id="documento-stato" class="form-select" required>
                @foreach (\App\Models\Documento::STATI as $valore => $etichetta)
                    <option value="{{ $valore }}" @selected(old('stato', $d?->stato ?? 'in uso') === $valore)>
                        {{ $etichetta }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4" id="documento-urgenza-wrapper">
            <label class="form-label">Urgenza</label>
            <select name="urgenza" class="form-select">
                <option value="">—</option>
                @foreach (\App\Models\Documento::URGENZE as $valore => $etichetta)
                    <option value="{{ $valore }}" @selected(old('urgenza', $d?->urgenza) === $valore)>
                        {{ $etichetta }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4" id="documento-responsabilita-wrapper">
            <label class="form-label">Responsabilità *</label>
            <select name="responsabilita" class="form-select" required>
                <option value="">—</option>
                <option value="IT" @selected(old('responsabilita', $d?->responsabilita) === 'IT')>IT</option>
                <option value="Admin" @selected(old('responsabilita', $d?->responsabilita) === 'Admin')>Amministrazione</option>
                <option value="Altri" @selected(old('responsabilita', $d?->responsabilita) === 'Altri')>Altri</option>
            </select>
        </div>

        <div class="col-md-4" id="documento-stato-richiesta-wrapper">
            <label class="form-label">Stato richiesta</label>
            <select name="stato_richiesta" class="form-select">
                <option value="non_risolta" @selected(old('stato_richiesta', $d?->stato_richiesta ?? 'non_risolta') === 'non_risolta')>Non risolta</option>
                <option value="in_risoluzione" @selected(old('stato_richiesta', $d?->stato_richiesta ?? 'non_risolta') === 'in_risoluzione')>In risoluzione</option>
                <option value="risolta" @selected(old('stato_richiesta', $d?->stato_richiesta ?? 'non_risolta') === 'risolta')>Risolta</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Scadenza (eventuale)</label>
            <input
                type="date"
                name="scadenza"
                class="form-control"
                value="{{ old('scadenza', $d?->scadenza?->format('Y-m-d')) }}"
            >
        </div>

        <div class="col-md-4">
            <label class="form-label">Anno di conseguimento (eventuale)</label>
            <input
                type="number"
                name="anno_conseguimento"
                class="form-control"
                min="1950"
                max="{{ date('Y') + 1 }}"
                value="{{ old('anno_conseguimento', $d?->anno_conseguimento) }}"
            >
        </div>

        <div class="col-12">
            <label class="form-label">Note</label>
            <textarea
                name="note"
                rows="3"
                class="form-control"
            >{{ old('note', $d?->note) }}</textarea>
        </div>

        <div class="col-12">
            <label class="form-label">
                File {{ $d && $d->file_path ? '(lascia vuoto per non modificarlo)' : '' }}
            </label>
            <input
                type="file"
                name="file"
                class="form-control"
                accept=".pdf,.jpg,.jpeg,.png"
            >
            <div class="form-text">
                Se lo stato è "Richiesta" il file può essere caricato in un secondo momento.
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-primary">Salva</button>
<a href="{{ route('anagrafiche.show', $anagrafica) }}" class="btn btn-secondary">Annulla</a>

    <script>
    (function () {
        const stato = document.getElementById('documento-stato');
        const urgenzaWrapper = document.getElementById('documento-urgenza-wrapper');
        const responsabilitaWrapper = document.getElementById('documento-responsabilita-wrapper');
        const statoRichiestaWrapper = document.getElementById('documento-stato-richiesta-wrapper');
        const responsabilitaSelect = responsabilitaWrapper.querySelector('select[name="responsabilita"]');

        function aggiornaVisibilitaRichiesta() {
            const mostra = ['richiesta', 'restituzione'].includes(stato.value);
            urgenzaWrapper.style.display = mostra ? '' : 'none';
            responsabilitaWrapper.style.display = mostra ? '' : 'none';
            statoRichiestaWrapper.style.display = mostra ? '' : 'none';

            // Il campo obbligatorio non deve bloccare il salvataggio quando è nascosto
            responsabilitaSelect.required = mostra;
        }

        stato.addEventListener('change', aggiornaVisibilitaRichiesta);
        aggiornaVisibilitaRichiesta();
    })();
    </script>
