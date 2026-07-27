<?php

namespace App\Http\Controllers;

use App\Models\Anagrafica;
use App\Models\Dotazione;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DotazioneController extends Controller
{
    public function index(Anagrafica $anagrafica)
    {
        return view('dotazioni.index', compact('anagrafica'));
    }

    public function create(Anagrafica $anagrafica)
    {
        return view('dotazioni.create', compact('anagrafica'));
    }

    public function store(Request $request, Anagrafica $anagrafica)
    {
        $dati = $this->validaDati($request);
        $dati['urgenza'] = $dati['stato'] === 'richiesta' ? ($dati['urgenza'] ?? null) : null;
        $dati['responsabilita'] = $dati['stato'] === 'richiesta' ? ($dati['responsabilita'] ?? null) : null;
        $dati['stato_richiesta'] = $dati['stato'] === 'richiesta' ? ($dati['stato_richiesta'] ?? 'non_risolta') : 'non_risolta';
        $dati['risolto'] = $dati['stato'] === 'richiesta' ? ($dati['stato_richiesta'] === 'risolta') : false;

        $anagrafica->dotazioni()->create($dati);

        return redirect()
            ->route('anagrafiche.show', $anagrafica)
            ->with('success', 'Dotazione aggiunta correttamente.');
    }

    public function show(Dotazione $dotazione)
    {
        return view('dotazioni.show', compact('dotazione'));
    }

    public function edit(Anagrafica $anagrafica, Dotazione $dotazione)
    {
        return view('dotazioni.edit', compact('anagrafica', 'dotazione'));
    }

    public function update(Request $request, Anagrafica $anagrafica, Dotazione $dotazione)
    {
        $dati = $this->validaDati($request);
        $dati['urgenza'] = $dati['stato'] === 'richiesta' ? ($dati['urgenza'] ?? null) : null;
        $dati['responsabilita'] = $dati['stato'] === 'richiesta' ? ($dati['responsabilita'] ?? null) : null;
        $dati['stato_richiesta'] = $dati['stato'] === 'richiesta' ? ($dati['stato_richiesta'] ?? 'non_risolta') : 'non_risolta';
        $dati['risolto'] = $dati['stato'] === 'richiesta' ? ($dati['stato_richiesta'] === 'risolta') : false;

        $dotazione->update($dati);

        return redirect()
            ->route('anagrafiche.show', $anagrafica)
            ->with('success', 'Dotazione aggiornata correttamente.');
    }

    public function destroy(Anagrafica $anagrafica, Dotazione $dotazione)
    {
        $dotazione->delete();

        return redirect()
            ->route('anagrafiche.show', $anagrafica)
            ->with('success', 'Dotazione eliminata.');
    }

    /**
     * Segna/dessegna una dotazione richiesta come risolta.
     * Accessibile a tutte le aree, non solo HR (vedi routes/web.php).
     */
    public function risolvi(Request $request, Anagrafica $anagrafica, Dotazione $dotazione)
    {
        $stato = $request->input('stato_richiesta', 'non_risolta');

        if ($stato === 'risolta') {
            $dotazione->update([
                'stato' => 'in uso',
                'stato_richiesta' => 'risolta',
                'risolto' => true,
                'urgenza' => null,
                'responsabilita' => null,
            ]);
        } else {
            $dotazione->update([
                'stato_richiesta' => $stato,
                'risolto' => false,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Stato della richiesta aggiornato.');
    }

    private function validaDati(Request $request): array
    {
        return $request->validate([
            'tipo_dotazione' => ['required', 'string', 'max:100'],
            'marca' => ['nullable', 'string', 'max:100'],
            'modello' => ['nullable', 'string', 'max:100'],
            'numero_identificativo' => ['nullable', 'string', 'max:100'],
            'data_consegna' => ['nullable', 'date'],
            'data_restituzione' => [
                'nullable', 'date', 'after_or_equal:data_consegna',
            ],
            'stato' => ['required', 'string', Rule::in(array_keys(Dotazione::STATI))],
            'urgenza' => ['nullable', 'string', Rule::in(array_keys(Dotazione::URGENZE))],
            'responsabilita' => ['required_if:stato,richiesta', 'nullable', 'string', Rule::in(['IT', 'Admin', 'Altri'])],
            'stato_richiesta' => ['nullable', 'string', Rule::in(['non_risolta', 'in_risoluzione', 'risolta'])],
            'note' => ['nullable', 'string'],
        ]);
    }
}