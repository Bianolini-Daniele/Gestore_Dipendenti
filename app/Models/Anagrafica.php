<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anagrafica extends Model
{
    use HasFactory;
    protected $table = 'anagrafica';

    /**
     * Stati possibili del dipendente, selezionabili dall'HR.
     */
    public const STATI_DIPENDENTE = [
        'on_boarding' => 'On Boarding',
        'dipendente' => 'Dipendente',
        'off_boarding' => 'Off Boarding',
    ];

    protected $fillable = [
        'nome',
        'cognome',
        'data_nascita',
        'luogo_nascita',
        'codice_fiscale',
        'mail_personale',
        'telefono',

        'indirizzo_residenza',
        'citta_residenza',
        'provincia_residenza',
        'cap_residenza',
        'residenza_aggiornata_al',

        'iban',
        'data_assunzione',
        'primo_giorno_lavorativo',
        'data_cessazione',
        'ultimo_giorno_lavorativo',
        'mansione',
        'reparto',
        'stato_dipendente',

        'patente_b',
        'scadenza_patente_b',
        'patente_muletto',
        'scadenza_patente_muletto',

        'carta_identita_file',
        'scadenza_carta_identita',

        'cud_file',
        'cud_anno',

        'corso_sicurezza_file',
        'scadenza_corso_sicurezza',

        'visita_medica_file',
        'data_visita_medica',
        'scadenza_visita_medica',

        'cv_file',

        'note',
    ];

    protected function casts(): array
    {
        return [
            'data_nascita' => 'date',
            'residenza_aggiornata_al' => 'date',
            'data_assunzione' => 'date',
            'primo_giorno_lavorativo' => 'date',
            'data_cessazione' => 'date',
            'ultimo_giorno_lavorativo' => 'date',
            'patente_b' => 'boolean',
            'scadenza_patente_b' => 'date',
            'patente_muletto' => 'boolean',
            'scadenza_patente_muletto' => 'date',
            'scadenza_carta_identita' => 'date',
            'scadenza_corso_sicurezza' => 'date',
            'data_visita_medica' => 'date',
            'scadenza_visita_medica' => 'date',
        ];
    }

    public function getNomeCompletoAttribute(): string
    {
        return "{$this->cognome} {$this->nome}";
    }

    public function getStatoDipendenteEtichettaAttribute(): string
    {
        return self::STATI_DIPENDENTE[$this->stato_dipendente] ?? $this->stato_dipendente;
    }

    public function documenti(): HasMany
    {
        return $this->hasMany(Documento::class);
    }

    public function dotazioni(): HasMany
    {
        return $this->hasMany(Dotazione::class);
    }
}