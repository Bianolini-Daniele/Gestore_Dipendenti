<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documenti', function (Blueprint $table) {
            $table->string('stato', 20)->default('in uso')->after('tipo_documento');
            $table->string('urgenza', 20)->nullable()->after('stato');
            $table->boolean('risolto')->default(false)->after('urgenza');
        });
    }

    public function down(): void
    {
        Schema::table('documenti', function (Blueprint $table) {
            $table->dropColumn(['stato', 'urgenza', 'risolto']);
        });
    }
};