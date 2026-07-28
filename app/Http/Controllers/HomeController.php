<?php

namespace App\Http\Controllers;

use App\Models\Anagrafica;
use App\Models\Documento;
use App\Models\Dotazione;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function it(Request $request): View
    {
        $tipo = $request->input('tipo', 'tutti');
        $statoRichiesta = $request->input('stato_richiesta', 'tutti');
        $statoDipendente = $request->input('stato_dipendente', 'tutti');
        $urgenza = $request->input('urgenza', 'tutti');
        $search = trim((string) $request->input('search', ''));

        $queryDocumenti = Documento::with('anagrafica')
            ->where('stato', 'richiesta')
            ->where('responsabilita', 'IT')
            ->whereHas('anagrafica', function ($query) {
                $query->where('stato_dipendente', '!=', Anagrafica::STATO_DISABILITATO);
            });

        $queryDotazioni = Dotazione::with('anagrafica')
            ->where('stato', 'richiesta')
            ->where('responsabilita', 'IT')
            ->whereHas('anagrafica', function ($query) {
                $query->where('stato_dipendente', '!=', Anagrafica::STATO_DISABILITATO);
            });

        if ($statoRichiesta !== 'tutti') {
            $queryDocumenti->where('stato_richiesta', $statoRichiesta);
            $queryDotazioni->where('stato_richiesta', $statoRichiesta);
        }

        if ($statoDipendente !== 'tutti') {
            $queryDocumenti->whereHas('anagrafica', function ($query) use ($statoDipendente) {
                $query->where('stato_dipendente', $statoDipendente);
            });
            $queryDotazioni->whereHas('anagrafica', function ($query) use ($statoDipendente) {
                $query->where('stato_dipendente', $statoDipendente);
            });
        }

        if ($urgenza !== 'tutti') {
            $queryDocumenti->where('urgenza', $urgenza);
            $queryDotazioni->where('urgenza', $urgenza);
        }

        if ($search !== '') {
            $term = "%{$search}%";
            $queryDocumenti->whereHas('anagrafica', function ($query) use ($term) {
                $query->where(function ($subQuery) use ($term) {
                    $subQuery->where('nome', 'like', $term)
                        ->orWhere('cognome', 'like', $term)
                        ->orWhereRaw("CONCAT(nome, ' ', cognome) LIKE ?", [$term])
                        ->orWhereRaw("CONCAT(cognome, ' ', nome) LIKE ?", [$term]);
                });
            });
            $queryDotazioni->whereHas('anagrafica', function ($query) use ($term) {
                $query->where(function ($subQuery) use ($term) {
                    $subQuery->where('nome', 'like', $term)
                        ->orWhere('cognome', 'like', $term)
                        ->orWhereRaw("CONCAT(nome, ' ', cognome) LIKE ?", [$term])
                        ->orWhereRaw("CONCAT(cognome, ' ', nome) LIKE ?", [$term]);
                });
            });
        }

        $documenti = $queryDocumenti->get()
            ->map(function (Documento $documento): array {
                return [
                    'id' => $documento->id,
                    'tipo_richiesta' => 'documento',
                    'richiesta' => $documento->nome,
                    'urgenza' => $documento->urgenza
                        ? (Documento::URGENZE[$documento->urgenza] ?? ucfirst($documento->urgenza))
                        : 'Non specificata',
                    'urgenza_valore' => $documento->urgenza ?? '',
                    'stato_richiesta' => $documento->stato_richiesta,
                    'stato_richiesta_etichetta' => $this->etichettaStatoRichiesta($documento->stato_richiesta),
                    'risolta' => (bool) $documento->risolto,
                    'dipendente' => $documento->anagrafica?->nome_completo ?? 'N/D',
                    'stato_dipendente' => $documento->anagrafica?->stato_dipendente_etichetta ?? 'N/D',
                    'stato_dipendente_valore' => $documento->anagrafica?->stato_dipendente ?? 'N/D',
                    'model' => $documento,
                ];
            });

        $dotazioni = $queryDotazioni->get()
            ->map(function (Dotazione $dotazione): array {
                return [
                    'id' => $dotazione->id,
                    'tipo_richiesta' => 'dotazione',
                    'richiesta' => $dotazione->tipo_dotazione,
                    'urgenza' => $dotazione->urgenza
                        ? (Dotazione::URGENZE[$dotazione->urgenza] ?? ucfirst($dotazione->urgenza))
                        : 'Non specificata',
                    'urgenza_valore' => $dotazione->urgenza ?? '',
                    'stato_richiesta' => $dotazione->stato_richiesta,
                    'stato_richiesta_etichetta' => $this->etichettaStatoRichiesta($dotazione->stato_richiesta),
                    'risolta' => (bool) $dotazione->risolto,
                    'dipendente' => $dotazione->anagrafica?->nome_completo ?? 'N/D',
                    'stato_dipendente' => $dotazione->anagrafica?->stato_dipendente_etichetta ?? 'N/D',
                    'stato_dipendente_valore' => $dotazione->anagrafica?->stato_dipendente ?? 'N/D',
                    'model' => $dotazione,
                ];
            });

        $richieste = match ($tipo) {
            'documenti' => $documenti,
            'dotazioni' => $dotazioni,
            default => $documenti->concat($dotazioni)->values(),
        };

        return view('home.it', compact('richieste', 'tipo', 'statoRichiesta', 'statoDipendente', 'urgenza', 'search'));
    }

    private function etichettaStatoRichiesta(string $stato): string
    {
        return match ($stato) {
            'risolta' => 'Risolta',
            'in_risoluzione' => 'In risoluzione',
            default => 'Non risolta',
        };
    }

    public function admin(Request $request): View
    {
        $tipo = $request->input('tipo', 'tutti');
        $statoRichiesta = $request->input('stato_richiesta', 'tutti');
        $statoDipendente = $request->input('stato_dipendente', 'tutti');
        $urgenza = $request->input('urgenza', 'tutti');
        $search = trim((string) $request->input('search', ''));

        $queryDocumenti = Documento::with('anagrafica')
            ->where('stato', 'richiesta')
            ->where('responsabilita', 'Admin')
            ->whereHas('anagrafica', function ($query) {
                $query->where('stato_dipendente', '!=', Anagrafica::STATO_DISABILITATO);
            });

        $queryDotazioni = Dotazione::with('anagrafica')
            ->where('stato', 'richiesta')
            ->where('responsabilita', 'Admin')
            ->whereHas('anagrafica', function ($query) {
                $query->where('stato_dipendente', '!=', Anagrafica::STATO_DISABILITATO);
            });

        if ($statoRichiesta !== 'tutti') {
            $queryDocumenti->where('stato_richiesta', $statoRichiesta);
            $queryDotazioni->where('stato_richiesta', $statoRichiesta);
        }

        if ($statoDipendente !== 'tutti') {
            $queryDocumenti->whereHas('anagrafica', function ($query) use ($statoDipendente) {
                $query->where('stato_dipendente', $statoDipendente);
            });
            $queryDotazioni->whereHas('anagrafica', function ($query) use ($statoDipendente) {
                $query->where('stato_dipendente', $statoDipendente);
            });
        }

        if ($urgenza !== 'tutti') {
            $queryDocumenti->where('urgenza', $urgenza);
            $queryDotazioni->where('urgenza', $urgenza);
        }

        if ($search !== '') {
            $term = "%{$search}%";
            $queryDocumenti->whereHas('anagrafica', function ($query) use ($term) {
                $query->where(function ($subQuery) use ($term) {
                    $subQuery->where('nome', 'like', $term)
                        ->orWhere('cognome', 'like', $term)
                        ->orWhereRaw("CONCAT(nome, ' ', cognome) LIKE ?", [$term])
                        ->orWhereRaw("CONCAT(cognome, ' ', nome) LIKE ?", [$term]);
                });
            });
            $queryDotazioni->whereHas('anagrafica', function ($query) use ($term) {
                $query->where(function ($subQuery) use ($term) {
                    $subQuery->where('nome', 'like', $term)
                        ->orWhere('cognome', 'like', $term)
                        ->orWhereRaw("CONCAT(nome, ' ', cognome) LIKE ?", [$term])
                        ->orWhereRaw("CONCAT(cognome, ' ', nome) LIKE ?", [$term]);
                });
            });
        }

        $documenti = $queryDocumenti->get()
            ->map(function (Documento $documento): array {
                return [
                    'id' => $documento->id,
                    'tipo_richiesta' => 'documento',
                    'richiesta' => $documento->nome,
                    'urgenza' => $documento->urgenza
                        ? (Documento::URGENZE[$documento->urgenza] ?? ucfirst($documento->urgenza))
                        : 'Non specificata',
                    'urgenza_valore' => $documento->urgenza ?? '',
                    'stato_richiesta' => $documento->stato_richiesta,
                    'stato_richiesta_etichetta' => $this->etichettaStatoRichiesta($documento->stato_richiesta),
                    'risolta' => (bool) $documento->risolto,
                    'dipendente' => $documento->anagrafica?->nome_completo ?? 'N/D',
                    'stato_dipendente' => $documento->anagrafica?->stato_dipendente_etichetta ?? 'N/D',
                    'stato_dipendente_valore' => $documento->anagrafica?->stato_dipendente ?? 'N/D',
                    'model' => $documento,
                ];
            });

        $dotazioni = $queryDotazioni->get()
            ->map(function (Dotazione $dotazione): array {
                return [
                    'id' => $dotazione->id,
                    'tipo_richiesta' => 'dotazione',
                    'richiesta' => $dotazione->tipo_dotazione,
                    'urgenza' => $dotazione->urgenza
                        ? (Dotazione::URGENZE[$dotazione->urgenza] ?? ucfirst($dotazione->urgenza))
                        : 'Non specificata',
                    'urgenza_valore' => $dotazione->urgenza ?? '',
                    'stato_richiesta' => $dotazione->stato_richiesta,
                    'stato_richiesta_etichetta' => $this->etichettaStatoRichiesta($dotazione->stato_richiesta),
                    'risolta' => (bool) $dotazione->risolto,
                    'dipendente' => $dotazione->anagrafica?->nome_completo ?? 'N/D',
                    'stato_dipendente' => $dotazione->anagrafica?->stato_dipendente_etichetta ?? 'N/D',
                    'stato_dipendente_valore' => $dotazione->anagrafica?->stato_dipendente ?? 'N/D',
                    'model' => $dotazione,
                ];
            });

        $richieste = match ($tipo) {
            'documenti' => $documenti,
            'dotazioni' => $dotazioni,
            default => $documenti->concat($dotazioni)->values(),
        };

        return view('home.admin', compact('richieste', 'tipo', 'statoRichiesta', 'statoDipendente', 'urgenza', 'search'));
    }

    public function altro(Request $request): View
    {
        $tipo = $request->input('tipo', 'tutti');
        $statoRichiesta = $request->input('stato_richiesta', 'tutti');
        $statoDipendente = $request->input('stato_dipendente', 'tutti');
        $urgenza = $request->input('urgenza', 'tutti');
        $search = trim((string) $request->input('search', ''));

        $queryDocumenti = Documento::with('anagrafica')
            ->where('stato', 'richiesta')
            ->where('responsabilita', 'Altri')
            ->whereHas('anagrafica', function ($query) {
                $query->where('stato_dipendente', '!=', Anagrafica::STATO_DISABILITATO);
            });

        $queryDotazioni = Dotazione::with('anagrafica')
            ->where('stato', 'richiesta')
            ->where('responsabilita', 'Altri')
            ->whereHas('anagrafica', function ($query) {
                $query->where('stato_dipendente', '!=', Anagrafica::STATO_DISABILITATO);
            });

        if ($statoRichiesta !== 'tutti') {
            $queryDocumenti->where('stato_richiesta', $statoRichiesta);
            $queryDotazioni->where('stato_richiesta', $statoRichiesta);
        }

        if ($statoDipendente !== 'tutti') {
            $queryDocumenti->whereHas('anagrafica', function ($query) use ($statoDipendente) {
                $query->where('stato_dipendente', $statoDipendente);
            });
            $queryDotazioni->whereHas('anagrafica', function ($query) use ($statoDipendente) {
                $query->where('stato_dipendente', $statoDipendente);
            });
        }

        if ($urgenza !== 'tutti') {
            $queryDocumenti->where('urgenza', $urgenza);
            $queryDotazioni->where('urgenza', $urgenza);
        }

        if ($search !== '') {
            $term = "%{$search}%";
            $queryDocumenti->whereHas('anagrafica', function ($query) use ($term) {
                $query->where(function ($subQuery) use ($term) {
                    $subQuery->where('nome', 'like', $term)
                        ->orWhere('cognome', 'like', $term)
                        ->orWhereRaw("CONCAT(nome, ' ', cognome) LIKE ?", [$term])
                        ->orWhereRaw("CONCAT(cognome, ' ', nome) LIKE ?", [$term]);
                });
            });
            $queryDotazioni->whereHas('anagrafica', function ($query) use ($term) {
                $query->where(function ($subQuery) use ($term) {
                    $subQuery->where('nome', 'like', $term)
                        ->orWhere('cognome', 'like', $term)
                        ->orWhereRaw("CONCAT(nome, ' ', cognome) LIKE ?", [$term])
                        ->orWhereRaw("CONCAT(cognome, ' ', nome) LIKE ?", [$term]);
                });
            });
        }

        $documenti = $queryDocumenti->get()
            ->map(function (Documento $documento): array {
                return [
                    'id' => $documento->id,
                    'tipo_richiesta' => 'documento',
                    'richiesta' => $documento->nome,
                    'urgenza' => $documento->urgenza
                        ? (Documento::URGENZE[$documento->urgenza] ?? ucfirst($documento->urgenza))
                        : 'Non specificata',
                    'urgenza_valore' => $documento->urgenza ?? '',
                    'stato_richiesta' => $documento->stato_richiesta,
                    'stato_richiesta_etichetta' => $this->etichettaStatoRichiesta($documento->stato_richiesta),
                    'risolta' => (bool) $documento->risolto,
                    'dipendente' => $documento->anagrafica?->nome_completo ?? 'N/D',
                    'stato_dipendente' => $documento->anagrafica?->stato_dipendente_etichetta ?? 'N/D',
                    'stato_dipendente_valore' => $documento->anagrafica?->stato_dipendente ?? 'N/D',
                    'model' => $documento,
                ];
            });

        $dotazioni = $queryDotazioni->get()
            ->map(function (Dotazione $dotazione): array {
                return [
                    'id' => $dotazione->id,
                    'tipo_richiesta' => 'dotazione',
                    'richiesta' => $dotazione->tipo_dotazione,
                    'urgenza' => $dotazione->urgenza
                        ? (Dotazione::URGENZE[$dotazione->urgenza] ?? ucfirst($dotazione->urgenza))
                        : 'Non specificata',
                    'urgenza_valore' => $dotazione->urgenza ?? '',
                    'stato_richiesta' => $dotazione->stato_richiesta,
                    'stato_richiesta_etichetta' => $this->etichettaStatoRichiesta($dotazione->stato_richiesta),
                    'risolta' => (bool) $dotazione->risolto,
                    'dipendente' => $dotazione->anagrafica?->nome_completo ?? 'N/D',
                    'stato_dipendente' => $dotazione->anagrafica?->stato_dipendente_etichetta ?? 'N/D',
                    'stato_dipendente_valore' => $dotazione->anagrafica?->stato_dipendente ?? 'N/D',
                    'model' => $dotazione,
                ];
            });

        $richieste = match ($tipo) {
            'documenti' => $documenti,
            'dotazioni' => $dotazioni,
            default => $documenti->concat($dotazioni)->values(),
        };

        return view('home.altro', compact('richieste', 'tipo', 'statoRichiesta', 'statoDipendente', 'urgenza', 'search'));
    }
}