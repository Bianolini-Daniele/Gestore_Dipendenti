<?php

namespace App\Http\Controllers;

use App\Models\Anagrafica;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DocumentoController extends Controller
{
    public function index(Anagrafica $anagrafica)
    {
        return view('documenti.index', compact('anagrafica'));
    }
    public function create(Anagrafica $anagrafica)
    {
        return view('documenti.create', compact('anagrafica'));
    }

    public function store(Request $request, Anagrafica $anagrafica)
    {
        $dati = $request->validate([
            'nome' => ['required', 'string', 'max:150'],
            'tipo_documento' => ['required', 'string', 'max:100'],
            'stato' => ['required', 'string', Rule::in(array_keys(Documento::STATI))],
            'urgenza' => ['nullable', 'string', Rule::in(array_keys(Documento::URGENZE))],
            'responsabilita' => ['required_if:stato,richiesta', 'nullable', 'string', Rule::in(['IT', 'Admin', 'Altri'])],
            'stato_richiesta' => ['nullable', 'string', Rule::in(['non_risolta', 'in_risoluzione', 'risolta'])],
            'scadenza' => ['nullable', 'date'],
            'anno_conseguimento' => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'note' => ['nullable', 'string'],
            'file' => [
                Rule::requiredIf(fn () => $request->input('stato') !== 'richiesta'),
                'nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120',
            ],
        ]);

        $path = $request->hasFile('file')
            ? $request->file('file')->store('documenti', 'public')
            : '';

        $anagrafica->documenti()->create([
            'nome' => $dati['nome'],
            'tipo_documento' => $dati['tipo_documento'],
            'stato' => $dati['stato'],
            'urgenza' => $dati['stato'] === 'richiesta' ? ($dati['urgenza'] ?? null) : null,
            'responsabilita' => $dati['stato'] === 'richiesta' ? ($dati['responsabilita'] ?? null) : null,
            'stato_richiesta' => $dati['stato'] === 'richiesta' ? ($dati['stato_richiesta'] ?? 'non_risolta') : 'non_risolta',
            'risolto' => $dati['stato'] === 'richiesta' ? ($dati['stato_richiesta'] === 'risolta') : false,
            'scadenza' => $dati['scadenza'] ?? null,
            'anno_conseguimento' => $dati['anno_conseguimento'] ?? null,
            'note' => $dati['note'] ?? null,
            'file_path' => $path,
        ]);

        return redirect()
            ->route('anagrafiche.show', $anagrafica)
            ->with('success', 'Documento caricato correttamente.');
    }

    public function show(Documento $documento)
    {
        return view('documenti.show', compact('documento'));
    }

    public function edit(Anagrafica $anagrafica, Documento $documento)
    {
        return view('documenti.edit', compact('anagrafica', 'documento'));
    }

    public function update(Request $request, Anagrafica $anagrafica, Documento $documento)
    {
        $dati = $request->validate([
            'nome' => ['required', 'string', 'max:150'],
            'tipo_documento' => ['required', 'string', 'max:100'],
            'stato' => ['required', 'string', Rule::in(array_keys(Documento::STATI))],
            'urgenza' => ['nullable', 'string', Rule::in(array_keys(Documento::URGENZE))],
            'responsabilita' => ['required_if:stato,richiesta', 'nullable', 'string', Rule::in(['IT', 'Admin', 'Altri'])],
            'stato_richiesta' => ['nullable', 'string', Rule::in(['non_risolta', 'in_risoluzione', 'risolta'])],
            'scadenza' => ['nullable', 'date'],
            'anno_conseguimento' => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 1)],
            'note' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        if ($request->hasFile('file')) {
            if ($documento->file_path) {
                Storage::disk('public')->delete($documento->file_path);
            }
            $dati['file_path'] = $request->file('file')->store('documenti', 'public');
        }

        $dati['urgenza'] = $dati['stato'] === 'richiesta' ? ($dati['urgenza'] ?? null) : null;
        $dati['responsabilita'] = $dati['stato'] === 'richiesta' ? ($dati['responsabilita'] ?? null) : null;
        $dati['stato_richiesta'] = $dati['stato'] === 'richiesta' ? ($dati['stato_richiesta'] ?? 'non_risolta') : 'non_risolta';
        $dati['risolto'] = $dati['stato'] === 'richiesta' ? ($dati['stato_richiesta'] === 'risolta') : false;

        $documento->update($dati);

        return redirect()
            ->route('anagrafiche.show', $anagrafica)
            ->with('success', 'Documento aggiornato correttamente.');
    }

    public function destroy(Anagrafica $anagrafica, Documento $documento)
    {
        if ($documento->file_path) {
            Storage::disk('public')->delete($documento->file_path);
        }
        $documento->delete();

        return redirect()
            ->route('anagrafiche.show', $anagrafica)
            ->with('success', 'Documento eliminato.');
    }

    /**
     * Segna/dessegna un documento richiesto come risolto.
     * Accessibile a tutte le aree, non solo HR (vedi routes/web.php).
     */
    public function risolvi(Request $request, Anagrafica $anagrafica, Documento $documento)
    {
        $stato = $request->input('stato_richiesta', 'non_risolta');

        if ($stato === 'risolta') {
            $documento->update([
                'stato' => 'in uso',
                'stato_richiesta' => 'risolta',
                'risolto' => true,
                'urgenza' => null,
                'responsabilita' => null,
            ]);
        } else {
            $documento->update([
                'stato_richiesta' => $stato,
                'risolto' => false,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Stato della richiesta aggiornato.');
    }

    public function download(Documento $documento)
    {
        $estensione = pathinfo($documento->file_path, PATHINFO_EXTENSION);

        return response()->download(
            Storage::disk('public')->path($documento->file_path),
            $documento->nome . '.' . $estensione
        );
    }

    public function visualizza(Documento $documento)
    {
        abort_unless(
            Storage::disk('public')->exists($documento->file_path),
            404
        );

        return response()->file(
            Storage::disk('public')->path($documento->file_path)
        );
    }
}