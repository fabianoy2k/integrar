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
        Schema::table('importacoes', function (Blueprint $table) {
            $table->string('nome')->nullable()->after('nome_arquivo');
            $table->string('tipo')->nullable()->after('nome');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->after('empresa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('importacoes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['nome', 'tipo', 'user_id']);
        });
    }
};
