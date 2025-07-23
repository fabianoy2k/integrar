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
        Schema::create('alteracao_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lancamento_id')->constrained('lancamentos')->onDelete('cascade');
            $table->string('campo_alterado');
            $table->text('valor_anterior')->nullable();
            $table->text('valor_novo');
            $table->string('tipo_alteracao'); // terceiro, conta, valor
            $table->string('usuario')->nullable();
            $table->timestamp('data_alteracao');
            $table->timestamps();
            
            $table->index(['lancamento_id', 'tipo_alteracao']);
            $table->index('data_alteracao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alteracao_logs');
    }
};
