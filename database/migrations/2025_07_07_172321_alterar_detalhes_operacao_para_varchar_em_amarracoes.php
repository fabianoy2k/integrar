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
        Schema::table('amarracoes', function (Blueprint $table) {
            $table->string('detalhes_operacao', 512)->change();
            $table->index('detalhes_operacao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amarracoes', function (Blueprint $table) {
            $table->json('detalhes_operacao')->change();
            $table->dropIndex(['detalhes_operacao']);
        });
    }
};
