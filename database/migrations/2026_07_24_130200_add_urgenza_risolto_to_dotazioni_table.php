<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dotazioni', function (Blueprint $table) {
            $table->string('urgenza', 20)->nullable()->after('stato');
            $table->boolean('risolto')->default(false)->after('urgenza');
        });
    }

    public function down(): void
    {
        Schema::table('dotazioni', function (Blueprint $table) {
            $table->dropColumn(['urgenza', 'risolto']);
        });
    }
};