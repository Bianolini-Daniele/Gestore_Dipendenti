<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dotazione extends Model
{
    protected $table = 'dotazioni';

    public $timestamps = false;

    /**
     * Stati possibili di una dotazione.
     */
    public const STATI = [
        'in uso' => 'In uso',
        'richiesta' => 'Richiesta',
        'restituita' => 'Restituita',
        'dismessa' => 'Dismessa',
    ];

    /**
     * Livelli di urgenza, usati solo quando lo stato è "richiesta".
     */
    public const URGENZE = [
        'bassa' => 'Bassa',
        'media' => 'Media',
        'alta' => 'Alta',
    ];

    protected $fillable = [
        'anagrafica_id',
        'tipo_dotazione',
        'marca',
        'modello',
        'numero_identificativo',
        'data_consegna',
        'data_restituzione',
        'stato',
        'urgenza',
        'responsabilita',
        'stato_richiesta',
        'risolto',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'data_consegna' => 'date',
            'data_restituzione' => 'date',
            'risolto' => 'boolean',
        ];
    }

    public function anagrafica(): BelongsTo
    {
        return $this->belongsTo(Anagrafica::class);
    }
}