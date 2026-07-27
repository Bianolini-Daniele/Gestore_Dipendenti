<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
    {
        Schema::create('documenti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anagrafica_id')
                ->constrained('anagrafica')
                ->cascadeOnDelete();

            $table->string('nome');
            $table->string('tipo_documento');
            $table->string('file_path');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documenti');
    }
};