<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documenti', function (Blueprint $table) {
            $table->date('scadenza')->nullable()->after('tipo_documento');
            $table->unsignedSmallInteger('anno_conseguimento')->nullable()->after('scadenza');
            $table->text('note')->nullable()->after('anno_conseguimento');
        });
    }

    public function down(): void
    {
        Schema::table('documenti', function (Blueprint $table) {
            $table->dropColumn(['scadenza', 'anno_conseguimento', 'note']);
        });
    }
};