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
        Schema::table('alteracao_logs', function (Blueprint $table) {
            $table->dropColumn('usuario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alteracao_logs', function (Blueprint $table) {
            $table->string('usuario')->nullable();
        });
    }
};
