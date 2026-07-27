<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documenti', function (Blueprint $table) {
            $table->string('stato_richiesta')->default('non_risolta')->after('risolto');
        });

        Schema::table('dotazioni', function (Blueprint $table) {
            $table->string('stato_richiesta')->default('non_risolta')->after('risolto');
        });
    }

    public function down(): void
    {
        Schema::table('documenti', function (Blueprint $table) {
            $table->dropColumn('stato_richiesta');
        });

        Schema::table('dotazioni', function (Blueprint $table) {
            $table->dropColumn('stato_richiesta');
        });
    }
};
