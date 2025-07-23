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
        Schema::create('amarracoes', function (Blueprint $table) {
            $table->id();
            $table->string('terceiro');
            $table->json('detalhes_operacao');
            $table->string('conta_debito');
            $table->string('conta_credito');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amarracoes');
    }
};
