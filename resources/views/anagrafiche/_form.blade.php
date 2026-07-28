@php
    $d = $anagrafica ?? null;
@endphp

<ul class="nav nav-tabs mb-4" id="datiTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab-anagrafici-btn" data-bs-toggle="tab" data-bs-target="#tab-anagrafici" type="button" role="tab">
            Dati anagrafici
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-residenza-btn" data-bs-toggle="tab" data-bs-target="#tab-residenza" type="button" role="tab">
            Residenza
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-lavorativi-btn" data-bs-toggle="tab" data-bs-target="#tab-lavorativi" type="button" role="tab">
            Dati lavorativi
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-allegati-btn" data-bs-toggle="tab" data-bs-target="#tab-allegati" type="button" role="tab">
            Documenti anagrafici e CV
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-documenti-btn" data-bs-toggle="tab" data-bs-target="#tab-documenti" type="button" role="tab">
            Documenti
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-dotazioni-btn" data-bs-toggle="tab" data-bs-target="#tab-dotazioni" type="button" role="tab">
            Dotazioni
        </button>
    </li>
</ul>

<div class="tab-content" id="datiTabContent">

    {{-- ===================== TAB 1: DATI ANAGRAFICI ===================== --}}
    <div class="tab-pane fade show active" id="tab-anagrafici" role="tabpanel">
        <div class="card shadow-sm mb-4">
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nome *</label>
                    <input type="text" name="nome" class="form-control" value="{{ old('nome', $d?->nome) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Cognome *</label>
                    <input type="text" name="cognome" class="form-control" value="{{ old('cognome', $d?->cognome) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Data di nascita</label>
                    <input type="date" name="data_nascita" class="form-control" value="{{ old('data_nascita', $d?->data_nascita?->format('Y-m-d')) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Luogo di nascita</label>
                    <input type="text" name="luogo_nascita" class="form-control" value="{{ old('luogo_nascita', $d?->luogo_nascita) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Codice fiscale *</label>
                    <input type="text" name="codice_fiscale" maxlength="16" class="form-control text-uppercase" value="{{ old('codice_fiscale', $d?->codice_fiscale) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">E-mail personale</label>
                    <input type="email" name="mail_personale" class="form-control" value="{{ old('mail_personale', $d?->mail_personale) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Telefono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $d?->telefono) }}">
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== TAB 2: RESIDENZA ===================== --}}
    <div class="tab-pane fade" id="tab-residenza" role="tabpanel">
        <div class="card shadow-sm mb-4">
            <div class="card-body row g-3">
                <div class="col-md-8">
                    <label class="form-label">Indirizzo</label>
                    <input type="text" name="indirizzo_residenza" class="form-control" value="{{ old('indirizzo_residenza', $d?->indirizzo_residenza) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Città</label>
                    <input type="text" name="citta_residenza" class="form-control" value="{{ old('citta_residenza', $d?->citta_residenza) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Provincia</label>
                    <input type="text" name="provincia_residenza" maxlength="2" class="form-control text-uppercase" value="{{ old('provincia_residenza', $d?->provincia_residenza) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">CAP</label>
                    <input type="text" name="cap_residenza" class="form-control" value="{{ old('cap_residenza', $d?->cap_residenza) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Residenza verificata il</label>
                    <input type="date" name="residenza_aggiornata_al" class="form-control" value="{{ old('residenza_aggiornata_al', $d?->residenza_aggiornata_al?->format('Y-m-d')) }}">
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== TAB 3: DATI LAVORATIVI ===================== --}}
    <div class="tab-pane fade" id="tab-lavorativi" role="tabpanel">
        <div class="card shadow-sm mb-4">
            <div class="card-header"><strong>Stato e inquadramento</strong></div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">Stato dipendente *</label>
                    <select name="stato_dipendente" id="stato_dipendente" class="form-select" required>
                        @foreach (\App\Models\Anagrafica::STATI_DIPENDENTE as $valore => $etichetta)
                            <option value="{{ $valore }}" @selected(old('stato_dipendente', $d?->stato_dipendente ?? 'on_boarding') === $valore)>
                                {{ $etichetta }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Reparto</label>
                    <input type="text" name="reparto" class="form-control" value="{{ old('reparto', $d?->reparto) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Mansione</label>
                    <input type="text" name="mansione" class="form-control" value="{{ old('mansione', $d?->mansione) }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label">IBAN</label>
                    <input type="text" name="iban" maxlength="34" class="form-control text-uppercase" value="{{ old('iban', $d?->iban) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Data assunzione
                        <span class="text-danger campo-obbligatorio" data-gruppo="onboarding" style="display:none">*</span>
                    </label>
                    <input type="date" name="data_assunzione" id="data_assunzione" class="form-control" value="{{ old('data_assunzione', $d?->data_assunzione?->format('Y-m-d')) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Primo giorno lavorativo
                        <span class="text-danger campo-obbligatorio" data-gruppo="onboarding" style="display:none">*</span>
                    </label>
                    <input type="date" name="primo_giorno_lavorativo" id="primo_giorno_lavorativo" class="form-control" value="{{ old('primo_giorno_lavorativo', $d?->primo_giorno_lavorativo?->format('Y-m-d')) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Data licenziamento/dimissione
                        <span class="text-danger campo-obbligatorio" data-gruppo="offboarding" style="display:none">*</span>
                    </label>
                    <input type="date" name="data_cessazione" id="data_cessazione" class="form-control" value="{{ old('data_cessazione', $d?->data_cessazione?->format('Y-m-d')) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        Ultimo giorno lavorativo
                        <span class="text-danger campo-obbligatorio" data-gruppo="offboarding" style="display:none">*</span>
                    </label>
                    <input type="date" name="ultimo_giorno_lavorativo" id="ultimo_giorno_lavorativo" class="form-control" value="{{ old('ultimo_giorno_lavorativo', $d?->ultimo_giorno_lavorativo?->format('Y-m-d')) }}">
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header"><strong>Patenti e abilitazioni</strong></div>
            <div class="card-body row g-3">
                <div class="col-md-3">
                    <div class="form-check mt-4">
                        <input type="hidden" name="patente_b" value="0">
                        <input type="checkbox" name="patente_b" value="1" class="form-check-input" @checked(old('patente_b', $d?->patente_b))>
                        <label class="form-check-label">Patente B</label>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Scadenza patente B</label>
                    <input type="date" name="scadenza_patente_b" class="form-control" value="{{ old('scadenza_patente_b', $d?->scadenza_patente_b?->format('Y-m-d')) }}">
                </div>

                <div class="col-md-3">
                    <div class="form-check mt-4">
                        <input type="hidden" name="patente_muletto" value="0">
                        <input type="checkbox" name="patente_muletto" value="1" class="form-check-input" @checked(old('patente_muletto', $d?->patente_muletto))>
                        <label class="form-check-label">Patente muletto</label>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Scadenza patente muletto</label>
                    <input type="date" name="scadenza_patente_muletto" class="form-control" value="{{ old('scadenza_patente_muletto', $d?->scadenza_patente_muletto?->format('Y-m-d')) }}">
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== TAB 4: DOCUMENTI ANAGRAFICI FISSI + CV ===================== --}}
    <div class="tab-pane fade" id="tab-allegati" role="tabpanel">
        <div class="card shadow-sm mb-4">
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Carta d'identità</label>
                    <input type="file" name="carta_identita_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    @if ($d?->carta_identita_file)
                        <a class="small d-block mt-1" target="_blank" href="{{ route('anagrafiche.documenti.visualizza', ['anagrafica' => $d, 'tipo' => 'carta_identita']) }}">Visualizza file attuale</a>
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="form-label">Scadenza carta d'identità</label>
                    <input type="date" name="scadenza_carta_identita" class="form-control" value="{{ old('scadenza_carta_identita', $d?->scadenza_carta_identita?->format('Y-m-d')) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">CUD anno precedente</label>
                    <input type="file" name="cud_file" class="form-control" accept=".pdf">
                    @if ($d?->cud_file)
                        <a class="small d-block mt-1" target="_blank" href="{{ route('anagrafiche.documenti.visualizza', ['anagrafica' => $d, 'tipo' => 'cud']) }}">Visualizza file attuale</a>
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="form-label">Anno CUD</label>
                    <input type="number" name="cud_anno" class="form-control" min="2000" max="{{ date('Y') }}" value="{{ old('cud_anno', $d?->cud_anno) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Attestato corso sicurezza</label>
                    <input type="file" name="corso_sicurezza_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    @if ($d?->corso_sicurezza_file)
                        <a class="small d-block mt-1" target="_blank" href="{{ route('anagrafiche.documenti.visualizza', ['anagrafica' => $d, 'tipo' => 'corso_sicurezza']) }}">Visualizza file attuale</a>
                    @endif
                </div>

                <div class="col-md-6">
                    <label class="form-label">Scadenza corso sicurezza</label>
                    <input type="date" name="scadenza_corso_sicurezza" class="form-control" value="{{ old('scadenza_corso_sicurezza', $d?->scadenza_corso_sicurezza?->format('Y-m-d')) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Visita medica</label>
                    <input type="file" name="visita_medica_file" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    @if ($d?->visita_medica_file)
                        <a class="small d-block mt-1" target="_blank" href="{{ route('anagrafiche.documenti.visualizza', ['anagrafica' => $d, 'tipo' => 'visita_medica']) }}">Visualizza file attuale</a>
                    @endif
                </div>

                <div class="col-md-4">
                    <label class="form-label">Data visita medica</label>
                    <input type="date" name="data_visita_medica" class="form-control" value="{{ old('data_visita_medica', $d?->data_visita_medica?->format('Y-m-d')) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Scadenza visita medica</label>
                    <input type="date" name="scadenza_visita_medica" class="form-control" value="{{ old('scadenza_visita_medica', $d?->scadenza_visita_medica?->format('Y-m-d')) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Curriculum Vitae (CV)</label>
                    <input type="file" name="cv_file" class="form-control" accept=".pdf,.doc,.docx">
                    @if ($d?->cv_file)
                        <a class="small d-block mt-1" target="_blank" href="{{ route('anagrafiche.documenti.visualizza', ['anagrafica' => $d, 'tipo' => 'cv']) }}">Visualizza file attuale</a>
                    @endif
                </div>

                <div class="col-12">
                    <label class="form-label">Note</label>
                    <textarea name="note" rows="4" class="form-control">{{ old('note', $d?->note) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== TAB 5: DOCUMENTI (dinamici) ===================== --}}
    <div class="tab-pane fade" id="tab-documenti" role="tabpanel">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Documenti</strong>
                <button type="button" id="aggiungi-documento" class="btn btn-sm btn-outline-primary">+ Aggiungi documento</button>
            </div>

            <div class="card-body">
                <div id="documenti-container" data-count="{{ $d?->documenti->count() ?? 0 }}">
                    @forelse (($d?->documenti ?? collect()) as $documento)
                        @include('anagrafiche._riga_documento', ['documento' => $documento, 'indice' => $loop->index])
                    @empty
                        <p class="text-muted mb-0" id="documenti-vuoto">Nessun documento aggiunto.</p>
                    @endforelse
                </div>

                <div id="documenti-eliminati-container"></div>
            </div>
        </div>

        <template id="template-riga-documento">
            @include('anagrafiche._riga_documento', ['documento' => null, 'indice' => '__INDEX__'])
        </template>
    </div>

    {{-- ===================== TAB 6: DOTAZIONI (dinamiche) ===================== --}}
    <div class="tab-pane fade" id="tab-dotazioni" role="tabpanel">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Dotazioni</strong>
                <button type="button" id="aggiungi-dotazione" class="btn btn-sm btn-outline-primary">+ Aggiungi dotazione</button>
            </div>

            <div class="card-body">
                <div id="dotazioni-container" data-count="{{ $d?->dotazioni->count() ?? 0 }}">
                    @forelse (($d?->dotazioni ?? collect()) as $dotazione)
                        @include('anagrafiche._riga_dotazione', ['dotazione' => $dotazione, 'indice' => $loop->index])
                    @empty
                        <p class="text-muted mb-0" id="dotazioni-vuoto">Nessuna dotazione aggiunta.</p>
                    @endforelse
                </div>

                <div id="dotazioni-eliminati-container"></div>
            </div>
        </div>

        <template id="template-riga-dotazione">
            @include('anagrafiche._riga_dotazione', ['dotazione' => null, 'indice' => '__INDEX__'])
        </template>
    </div>
</div>

<button type="submit" class="btn btn-primary">Salva</button>
<a href="{{ route('anagrafiche.index') }}" class="btn btn-secondary">Annulla</a>

<script>
    (function () {
        let contatoreDocumenti = parseInt(document.getElementById('documenti-container').dataset.count, 10) || 0;
        let contatoreDotazioni = parseInt(document.getElementById('dotazioni-container').dataset.count, 10) || 0;

        function aggiungiRiga(containerId, templateId, placeholderVuotoId, contatore) {
            const container = document.getElementById(containerId);
            const template = document.getElementById(templateId);
            const placeholderVuoto = document.getElementById(placeholderVuotoId);

            if (placeholderVuoto) {
                placeholderVuoto.remove();
            }

            const html = template.innerHTML.replaceAll('__INDEX__', contatore);
            const wrapper = document.createElement('div');
            wrapper.innerHTML = html.trim();
            const riga = wrapper.firstElementChild;
            container.prepend(riga);
            aggiornaVisibilitaRiga(riga);
        }
        

        function aggiornaVisibilitaRiga(riga) {
            const stato = riga.querySelector('.riga-stato');
            if (!stato) return;

            const urgenzaWrapper = riga.querySelector('.riga-urgenza-wrapper');
            const responsabilitàWrapper = riga.querySelector('.riga-responsabilità-wrapper');
            const statoWrapper = riga.querySelector('.riga-stato-wrapper');
            const richiesta = stato.value === 'richiesta';

            if (urgenzaWrapper && responsabilitàWrapper && statoWrapper) {
                urgenzaWrapper.style.display = richiesta ? '' : 'none';
                responsabilitàWrapper.style.display = richiesta ? '' : 'none';
                statoWrapper.style.display = richiesta ? '' : 'none';
            }
        }

        document.getElementById('aggiungi-documento').addEventListener('click', function () {
            aggiungiRiga('documenti-container', 'template-riga-documento', 'documenti-vuoto', contatoreDocumenti);
            contatoreDocumenti++;
        });

        document.getElementById('aggiungi-dotazione').addEventListener('click', function () {
            aggiungiRiga('dotazioni-container', 'template-riga-dotazione', 'dotazioni-vuoto', contatoreDotazioni);
            contatoreDotazioni++;
        });

        // Mostra/nasconde l'urgenza in base allo stato selezionato, per ogni riga esistente.
        document.querySelectorAll('.riga-documento, .riga-dotazione').forEach(aggiornaVisibilitaRiga);

        document.addEventListener('change', function (evento) {
            if (!evento.target.classList.contains('riga-stato')) {
                return;
            }

            const riga = evento.target.closest('.riga-documento, .riga-dotazione');
            if (riga) {
                aggiornaVisibilitaRiga(riga);
            }
        });

        document.addEventListener('click', function (evento) {
            if (!evento.target.classList.contains('rimuovi-riga')) {
                return;
            }

            const riga = evento.target.closest('.riga-documento, .riga-dotazione');
            if (!riga) return;

            const id = riga.dataset.id;

            if (id) {
                const eliminatiContainerId = riga.classList.contains('riga-documento')
                    ? 'documenti-eliminati-container'
                    : 'dotazioni-eliminati-container';

                const nomeCampo = riga.classList.contains('riga-documento')
                    ? 'documenti_eliminati[]'
                    : 'dotazioni_eliminati[]';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = nomeCampo;
                input.value = id;
                document.getElementById(eliminatiContainerId).appendChild(input);
            }

            riga.remove();
        });
        
        const statoDipendenteSelect = document.getElementById('stato_dipendente');
        const campoDataAssunzione = document.getElementById('data_assunzione');
        const campoPrimoGiorno = document.getElementById('primo_giorno_lavorativo');
        const campoDataCessazione = document.getElementById('data_cessazione');
        const campoUltimoGiorno = document.getElementById('ultimo_giorno_lavorativo');

        function aggiornaObbligatorietaDate() {
            const stato = statoDipendenteSelect.value;
            const isOnboarding = stato === 'on_boarding';
            const isOffboarding = stato === 'off_boarding';

            campoDataAssunzione.required = isOnboarding;
            campoPrimoGiorno.required = isOnboarding;
            campoDataCessazione.required = isOffboarding;
            campoUltimoGiorno.required = isOffboarding;

            document.querySelectorAll('.campo-obbligatorio[data-gruppo="onboarding"]').forEach(function (span) {
                span.style.display = isOnboarding ? '' : 'none';
            });
            document.querySelectorAll('.campo-obbligatorio[data-gruppo="offboarding"]').forEach(function (span) {
                span.style.display = isOffboarding ? '' : 'none';
            });
        }

        // Selezionando una data di assunzione e/o primo giorno lavorativo -> stato On Boarding
        [campoDataAssunzione, campoPrimoGiorno].forEach(function (campo) {
            campo.addEventListener('change', function () {
                if (campo.value) {
                    statoDipendenteSelect.value = 'on_boarding';
                }
                aggiornaObbligatorietaDate();
            });
        });

        // Selezionando data di licenziamento e/o ultimo giorno lavorativo -> stato Off Boarding
        [campoDataCessazione, campoUltimoGiorno].forEach(function (campo) {
            campo.addEventListener('change', function () {
                if (campo.value) {
                    statoDipendenteSelect.value = 'off_boarding';
                }
                aggiornaObbligatorietaDate();
            });
        });

        // Se lo stato viene cambiato manualmente, aggiorna comunque l'obbligatorietà
        statoDipendenteSelect.addEventListener('change', aggiornaObbligatorietaDate);

        // Se un campo obbligatorio nascosto in un altro tab blocca l'invio,
        // porta automaticamente l'utente sul tab "Dati lavorativi"
        [campoDataAssunzione, campoPrimoGiorno, campoDataCessazione, campoUltimoGiorno].forEach(function (campo) {
            campo.addEventListener('invalid', function () {
                document.getElementById('tab-lavorativi-btn').click();
            });
        });

        // Stato iniziale (utile in fase di modifica di un dipendente esistente)
        aggiornaObbligatorietaDate();
    })();
</script>
