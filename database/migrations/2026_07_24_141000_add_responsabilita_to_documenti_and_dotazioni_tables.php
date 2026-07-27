<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documenti', function (Blueprint $table) {
            $table->string('responsabilita')->nullable()->after('urgenza');
        });

        Schema::table('dotazioni', function (Blueprint $table) {
            $table->string('responsabilita')->nullable()->after('urgenza');
        });
    }

    public function down(): void
    {
        Schema::table('documenti', function (Blueprint $table) {
            $table->dropColumn('responsabilita');
        });

        Schema::table('dotazioni', function (Blueprint $table) {
            $table->dropColumn('responsabilita');
        });
    }
};
