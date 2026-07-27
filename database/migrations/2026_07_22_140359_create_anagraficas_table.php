<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anagrafica', function (Blueprint $table) {
            $table->id();

            // Dati anagrafici
            $table->string('nome', 100);
            $table->string('cognome', 100);
            $table->date('data_nascita')->nullable();
            $table->string('luogo_nascita', 150)->nullable();
            $table->string('codice_fiscale', 16)->unique();
            $table->string('mail_personale')->nullable();
            $table->string('telefono', 30)->nullable();

            // Residenza
            $table->string('indirizzo_residenza')->nullable();
            $table->string('citta_residenza', 100)->nullable();
            $table->string('provincia_residenza', 2)->nullable();
            $table->string('cap_residenza', 10)->nullable();
            $table->date('residenza_aggiornata_al')->nullable();

            // Dati amministrativi
            $table->string('iban', 34)->nullable();
            $table->date('data_assunzione')->nullable();
            $table->date('data_cessazione')->nullable();
            $table->string('mansione')->nullable();
            $table->string('reparto')->nullable();

            // Patenti e abilitazioni
            $table->boolean('patente_b')->default(false);
            $table->date('scadenza_patente_b')->nullable();

            $table->boolean('patente_muletto')->default(false);
            $table->date('scadenza_patente_muletto')->nullable();

            // Percorsi degli allegati
            $table->string('carta_identita_file')->nullable();
            $table->date('scadenza_carta_identita')->nullable();

            $table->string('cud_file')->nullable();
            $table->unsignedSmallInteger('cud_anno')->nullable();

            $table->string('corso_sicurezza_file')->nullable();
            $table->date('scadenza_corso_sicurezza')->nullable();

            $table->string('visita_medica_file')->nullable();
            $table->date('data_visita_medica')->nullable();
            $table->date('scadenza_visita_medica')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anagrafica');
    }
};