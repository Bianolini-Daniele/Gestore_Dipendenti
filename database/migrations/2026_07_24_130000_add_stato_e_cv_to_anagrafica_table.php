<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('anagrafica', function (Blueprint $table) {
       
        $table->string('stato_dipendente', 20)->default('dipendente')->after('reparto');
            $table->string('cv_file')->nullable()->after('scadenza_visita_medica');
        });
    }

    public function down(): void
    {
        Schema::table('anagrafica', function (Blueprint $table) {
            $table->dropColumn(['stato_dipendente', 'cv_file']);
        });
    }
};