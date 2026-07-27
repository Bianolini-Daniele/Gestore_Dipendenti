<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anagrafica', function (Blueprint $table) {
            $table->date('primo_giorno_lavorativo')->nullable()->after('data_assunzione');
            $table->date('ultimo_giorno_lavorativo')->nullable()->after('data_cessazione');
        });
    }

    public function down(): void
    {
        Schema::table('anagrafica', function (Blueprint $table) {
            $table->dropColumn(['primo_giorno_lavorativo', 'ultimo_giorno_lavorativo']);
        });
    }
};