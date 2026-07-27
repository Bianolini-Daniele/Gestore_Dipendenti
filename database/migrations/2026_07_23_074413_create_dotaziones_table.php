<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dotazioni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anagrafica_id')
                ->constrained('anagrafica')
                ->cascadeOnDelete();

            $table->string('tipo_dotazione');
            $table->string('marca')->nullable();
            $table->string('modello')->nullable();
            $table->string('numero_identificativo')->nullable();

            $table->date('data_consegna')->nullable();
            $table->date('data_restituzione')->nullable();
            $table->string('stato')->default('in uso');

            $table->text('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dotaziones');
    }
};
