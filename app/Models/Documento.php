<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documento extends Model
{
    protected $table = 'documenti';

    public $timestamps = false;

    /**
     * Stati possibili di un documento.
     */
    public const STATI = [
        'in uso' => 'In uso',
        'richiesta' => 'Richiesta',
        'restituito' => 'Restituito',
        'dismesso' => 'Dismesso',
    ];

    /**
     * Livelli di urgenza, usati solo quando lo stato è "richiesta".
     */
    public const URGENZE = [
        'bassa' => 'Bassa',
        'media' => 'Media',
        'alta' => 'Alta',
    ];

    /**
     * Aree responsabili della gestione del documento.
     */
    public const RESPONSABILITA = [
        'IT' => 'IT',
        'Admin' => 'Amministrazione',
        'Altri' => 'Altri',
    ];

    /**
     * Stati possibili della richiesta collegata al documento.
     */
    public const STATI_RICHIESTA = [
        'non_risolta' => 'Non risolta',
        'in_risoluzione' => 'In risoluzione',
        'risolta' => 'Risolta',
    ];

    protected $fillable = [
        'anagrafica_id',
        'nome',
        'tipo_documento',
        'stato',
        'urgenza',
        'responsabilita',
        'stato_richiesta',
        'risolto',
        'file_path',
        'scadenza',
        'anno_conseguimento',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'scadenza' => 'date',
            'risolto' => 'boolean',
        ];
    }

    public function anagrafica(): BelongsTo
    {
        return $this->belongsTo(Anagrafica::class);
    }
}