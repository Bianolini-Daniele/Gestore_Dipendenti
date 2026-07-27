<?php

namespace App\Http\Controllers;

use App\Models\Anagrafica;
use App\Models\Documento;
use App\Models\Dotazione;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AnagraficaController extends Controller
{
    public function index(Request $request)
    {
        $filtro = $request->query('stato', 'tutti');
        $search = trim((string) $request->query('search', ''));

        $query = Anagrafica::query()
            ->when(
                $filtro !== 'tutti' && array_key_exists($filtro, Anagrafica::STATI_DIPENDENTE),
                fn ($query) => $query->where('stato_dipendente', $filtro)
            );

        if ($search !== '') {
            $words = preg_split('/\s+/', trim($search));

            $query->where(function ($subQuery) use ($words) {
                foreach ($words as $word) {
                    $term = "%{$word}%";
                    $subQuery->where(function ($q) use ($term) {
                        $q->where('nome', 'like', $term)
                          ->orWhere('cognome', 'like', $term);
                    });
                }
            });
        }

        $matching = (clone $query)->limit(2)->get();

        // Auto-redirect to the specific employee only when the full name (nome + cognome) is typed.
        $wordCount = count(preg_split('/\s+/', trim($search)));
        if ($search !== '' && $wordCount >= 2 && $matching->count() === 1) {
            return redirect()->route('anagrafiche.show', $matching->first());
        }

        $anagrafiche = $query
            ->withCount([
                'documenti as documenti_da_risolvere_count' => function ($query) {
                    $query->where('stato', 'richiesta')->where('risolto', false);
                },
                'dotazioni as dotazioni_da_risolvere_count' => function ($query) {
                    $query->where('stato', 'richiesta')->where('risolto', false);
                },
            ])
            ->orderBy('cognome')
            ->orderBy('nome')
            ->paginate(20)
            ->withQueryString();

        return view('anagrafiche.index', compact('anagrafiche', 'filtro', 'search'));
    }

    public function create()
    {
        return view('anagrafiche.create');
    }

    public function store(Request $request)
    {
        $dati = $this->validaDati($request);

        $dati['patente_b'] = $request->boolean('patente_b');
        $dati['patente_muletto'] = $request->boolean('patente_muletto');

        $dati = $this->salvaAllegati($request, $dati);

        $anagrafica = Anagrafica::create($dati);

        $this->sincronizzaDocumenti($request, $anagrafica);
        $this->sincronizzaDotazioni($request, $anagrafica);

        return redirect()
            ->route('anagrafiche.index')
            ->with('success', 'Dipendente inserito correttamente.');


    }

    public function show(Anagrafica $anagrafica)
    {
        return view('anagrafiche.show', compact('anagrafica'));
    }

    public function edit(Anagrafica $anagrafica)
    {
        return view('anagrafiche.edit', compact('anagrafica'));
    }

    public function update(Request $request, Anagrafica $anagrafica)
    {
        $dati = $this->validaDati($request, $anagrafica);

        $dati['patente_b'] = $request->boolean('patente_b');
        $dati['patente_muletto'] = $request->boolean('patente_muletto');

        $dati = $this->salvaAllegati($request, $dati, $anagrafica);

        $anagrafica->update($dati);

        $this->sincronizzaDocumenti($request, $anagrafica);
        $this->sincronizzaDotazioni($request, $anagrafica);

        return redirect()
            ->route('anagrafiche.index')
            ->with('success', 'Dati aggiornati correttamente.');

    }

    public function destroy(Anagrafica $anagrafica)
    {
        $this->eliminaAllegati($anagrafica);

        $anagrafica->delete();

        return redirect()
            ->route('anagrafiche.index')
            ->with('success', 'Dipendente eliminato correttamente.');
    }

    public function updateStato(Request $request, Anagrafica $anagrafica): RedirectResponse
    {
        $request->validate([
            'stato_dipendente' => ['required', 'string', Rule::in(array_keys(Anagrafica::STATI_DIPENDENTE))],
        ]);

        $anagrafica->update([
            'stato_dipendente' => $request->input('stato_dipendente'),
        ]);

        return redirect()
            ->route('anagrafiche.index')
            ->with('success', 'Stato del dipendente aggiornato correttamente.');
    }

    public function download(
        Anagrafica $anagrafica,
        string $tipo
    ) {
        $campiConsentiti = [
            'carta_identita' => 'carta_identita_file',
            'cud' => 'cud_file',
            'corso_sicurezza' => 'corso_sicurezza_file',
            'visita_medica' => 'visita_medica_file',
            'cv' => 'cv_file',
        ];

        abort_unless(isset($campiConsentiti[$tipo]), 404);

        $campo = $campiConsentiti[$tipo];
        $percorso = $anagrafica->$campo;

        abort_unless(
            $percorso && Storage::disk('local')->exists($percorso),
            404
        );

        return response()->download(
            Storage::disk('local')->path($percorso)
        );

        //return Storage::disk('local')->download($percorso);
    }

    public function visualizza(
        Anagrafica $anagrafica,
        string $tipo
    ) {
       $campiConsentiti = [
            'carta_identita' => 'carta_identita_file',
            'cud' => 'cud_file',
            'corso_sicurezza' => 'corso_sicurezza_file',
            'visita_medica' => 'visita_medica_file',
            'cv' => 'cv_file',
        ];

        abort_unless(isset($campiConsentiti[$tipo]), 404);

        $campo = $campiConsentiti[$tipo];
        $percorso = $anagrafica->$campo;

        abort_unless(
            $percorso && Storage::disk('local')->exists($percorso),
            404
        );

        return response()->file(
            Storage::disk('local')->path($percorso)
        );
    }

    private function validaDati(
        Request $request,
        ?Anagrafica $anagrafica = null
    ): array {
        return $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'cognome' => ['required', 'string', 'max:100'],
            'data_nascita' => ['nullable', 'date', 'before:today'],
            'luogo_nascita' => ['nullable', 'string', 'max:150'],

            'codice_fiscale' => [
                'required',
                'string',
                'size:16',
                Rule::unique('anagrafica', 'codice_fiscale')
                    ->ignore($anagrafica?->id),
            ],

            'mail_personale' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],

            'indirizzo_residenza' => ['nullable', 'string', 'max:255'],
            'citta_residenza' => ['nullable', 'string', 'max:100'],
            'provincia_residenza' => ['nullable', 'string', 'size:2'],
            'cap_residenza' => ['nullable', 'string', 'max:10'],
            'residenza_aggiornata_al' => ['nullable', 'date'],

            'iban' => ['nullable', 'string', 'max:34'],
            'data_assunzione' => ['nullable', 'date'],
            'primo_giorno_lavorativo' => [
                'nullable',
                'date',
                'after_or_equal:data_assunzione',
            ],
            'data_cessazione' => [
                'nullable',
                'date',
                'after_or_equal:data_assunzione',
            ],
            'ultimo_giorno_lavorativo' => [
                'nullable',
                'date',
                'after_or_equal:primo_giorno_lavorativo',
            ],
            'mansione' => ['nullable', 'string', 'max:255'],
            'reparto' => ['nullable', 'string', 'max:255'],
            'stato_dipendente' => [
                'required',
                'string',
                Rule::in(array_keys(Anagrafica::STATI_DIPENDENTE)),
            ],

            'patente_b' => ['nullable', 'boolean'],
            'scadenza_patente_b' => ['nullable', 'date'],
            'patente_muletto' => ['nullable', 'boolean'],
            'scadenza_patente_muletto' => ['nullable', 'date'],

            'scadenza_carta_identita' => ['nullable', 'date'],
            'cud_anno' => [
                'nullable',
                'integer',
                'min:2000',
                'max:' . date('Y'),
            ],
            'scadenza_corso_sicurezza' => ['nullable', 'date'],
            'data_visita_medica' => ['nullable', 'date'],
            'scadenza_visita_medica' => [
                'nullable',
                'date',
                'after_or_equal:data_visita_medica',
            ],

            'carta_identita_file' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
            'cud_file' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:5120',
            ],
            'corso_sicurezza_file' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
            'visita_medica_file' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:5120',
            ],
            'cv_file' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120',
            ],

            'note' => ['nullable', 'string'],

            'documenti' => ['nullable', 'array'],
            'documenti.*.id' => ['nullable', 'integer'],
            'documenti.*.nome' => ['nullable', 'string', 'max:150'],
            'documenti.*.tipo_documento' => ['nullable', 'string', 'max:100'],
            'documenti.*.stato' => [
                'nullable', 'string', Rule::in(array_keys(Documento::STATI)),
            ],
            'documenti.*.urgenza' => [
                'nullable', 'string', Rule::in(array_keys(Documento::URGENZE)),
            ],
            'documenti.*.responsabilita' => [
                'nullable', 'string', Rule::in(array_keys(Documento::RESPONSABILITA)),
            ],
            'documenti.*.stato_richiesta' => [
                'nullable', 'string', Rule::in(array_keys(Documento::STATI_RICHIESTA)),
            ],
            'documenti.*.risolto' => ['nullable', 'boolean'],
            'documenti.*.scadenza' => ['nullable', 'date'],
            'documenti.*.anno_conseguimento' => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'documenti.*.note' => ['nullable', 'string'],
            'documenti.*.file' => [
                'nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120',
            ],
            'documenti_eliminati' => ['nullable', 'array'],
            'documenti_eliminati.*' => ['integer'],

            'dotazioni' => ['nullable', 'array'],
            'dotazioni.*.id' => ['nullable', 'integer'],
            'dotazioni.*.tipo_dotazione' => ['nullable', 'string', 'max:100'],
            'dotazioni.*.marca' => ['nullable', 'string', 'max:100'],
            'dotazioni.*.modello' => ['nullable', 'string', 'max:100'],
            'dotazioni.*.numero_identificativo' => ['nullable', 'string', 'max:100'],
            'dotazioni.*.data_consegna' => ['nullable', 'date'],
            'dotazioni.*.data_restituzione' => ['nullable', 'date'],
            'dotazioni.*.stato' => [
                'nullable', 'string', Rule::in(array_keys(Dotazione::STATI)),
            ],
            'dotazioni.*.urgenza' => [
                'nullable', 'string', Rule::in(array_keys(Dotazione::URGENZE)),
            ],
            'dotazioni.*.risolto' => ['nullable', 'boolean'],
            'dotazioni.*.note' => ['nullable', 'string'],
            'dotazioni_eliminati' => ['nullable', 'array'],
            'dotazioni_eliminati.*' => ['integer'],

        ]);
    }
    private function salvaAllegati(
        Request $request,
        array $dati,
        ?Anagrafica $anagrafica = null
    ): array {
        $allegati = [
            'carta_identita_file',
            'cud_file',
            'corso_sicurezza_file',
            'visita_medica_file',
            'cv_file',
        ];

        foreach ($allegati as $campo) {
            if ($request->hasFile($campo)) {
                if (
                    $anagrafica &&
                    $anagrafica->$campo &&
                    Storage::disk('local')->exists($anagrafica->$campo)
                ) {
                    Storage::disk('local')->delete($anagrafica->$campo);
                }

                $dati[$campo] = $request
                    ->file($campo)
                    ->store("anagrafiche/{$campo}", 'local');
            }
        }

        return $dati;
    }

    private function sincronizzaDocumenti(Request $request, Anagrafica $anagrafica): void
    {
        $daEliminare = $anagrafica->documenti()
            ->whereIn('id', $request->input('documenti_eliminati', []))
            ->get();

        foreach ($daEliminare as $documento) {
            if ($documento->file_path) {
                Storage::disk('public')->delete($documento->file_path);
            }
            $documento->delete();
        }

        foreach ($request->input('documenti', []) as $indice => $riga) {
            $file = $request->file("documenti.$indice.file");
            $nome = trim($riga['nome'] ?? '');
            $tipo = $riga['tipo_documento'] ?? null;
            $scadenza = $riga['scadenza'] ?? null;
            $annoConseguimento = $riga['anno_conseguimento'] ?? null;
            $note = $riga['note'] ?? null;
            $stato = $riga['stato'] ?? 'in uso';
            $urgenza = $stato === 'richiesta' ? ($riga['urgenza'] ?? null) : null;
            $risolto = filter_var($riga['risolto'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $responsabilita = $riga['responsabilita'] ?? null;
            $statoRichiesta = $riga['stato_richiesta'] ?? null;

            if (!empty($riga['id'])) {
                $documento = $anagrafica->documenti()->find($riga['id']);

                if (!$documento) {
                    continue;
                }

                if ($nome !== '') {
                    $documento->nome = $nome;
                }

                if ($tipo) {
                    $documento->tipo_documento = $tipo;
                }

                $documento->scadenza = $scadenza ?: null;
                $documento->anno_conseguimento = $annoConseguimento ?: null;
                $documento->note = $note ?: null;
                $documento->stato = $stato;
                $documento->urgenza = $urgenza;
                $documento->risolto = $risolto;
                $documento->responsabilita = $responsabilita;
                $documento->stato_richiesta = $statoRichiesta;

                if ($file) {
                    if ($documento->file_path) {
                        Storage::disk('public')->delete($documento->file_path);
                    }
                    $documento->file_path = $file->store('documenti', 'public');
                }

                $documento->save();
                continue;
            }


            if ($nome === '' || !$tipo) {
                continue;
            }

            // Un documento "richiesto" può non avere ancora un file allegato.
            if (!$file && $stato !== 'richiesta') {
                continue;
            }

            $anagrafica->documenti()->create([
                'nome' => $nome,
                'tipo_documento' => $tipo,
                'scadenza' => $scadenza ?: null,
                'anno_conseguimento' => $annoConseguimento ?: null,
                'note' => $note ?: null,
                'stato' => $stato,
                'urgenza' => $urgenza,
                'risolto' => $risolto,
                'responsabilita' => $responsabilita,
                'stato_richiesta' => $statoRichiesta,
                'file_path' => $file ? $file->store('documenti', 'public') : '',
            ]);
        }
    }

    private function sincronizzaDotazioni(Request $request, Anagrafica $anagrafica): void
    {
        $daEliminare = $anagrafica->dotazioni()
            ->whereIn('id', $request->input('dotazioni_eliminati', []))
            ->get();

        foreach ($daEliminare as $dotazione) {
            $dotazione->delete();
        }

        foreach ($request->input('dotazioni', []) as $riga) {
            $tipo = trim($riga['tipo_dotazione'] ?? '');
            $stato = $riga['stato'] ?? 'in uso';

            $campi = [
                'tipo_dotazione' => $tipo,
                'marca' => $riga['marca'] ?? null,
                'modello' => $riga['modello'] ?? null,
                'numero_identificativo' => $riga['numero_identificativo'] ?? null,
                'data_consegna' => $riga['data_consegna'] ?? null,
                'data_restituzione' => $riga['data_restituzione'] ?? null,
                'stato' => $stato,
                'urgenza' => $stato === 'richiesta' ? ($riga['urgenza'] ?? null) : null,
                'risolto' => filter_var($riga['risolto'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'note' => $riga['note'] ?? null,
            ];

            if (!empty($riga['id'])) {
                $dotazione = $anagrafica->dotazioni()->find($riga['id']);

                if (!$dotazione || $tipo === '') {
                    continue;
                }

                $dotazione->update($campi);
                continue;
            }

            if ($tipo === '') {
                continue;
            }

            $anagrafica->dotazioni()->create($campi);
        }
    }

    private function eliminaAllegati(Anagrafica $anagrafica): void
    {
        $allegati = [
            $anagrafica->carta_identita_file,
            $anagrafica->cud_file,
            $anagrafica->corso_sicurezza_file,
            $anagrafica->visita_medica_file,
            $anagrafica->cv_file,
        ];

        foreach (array_filter($allegati) as $percorso) {
            Storage::disk('local')->delete($percorso);
        }
    }
}