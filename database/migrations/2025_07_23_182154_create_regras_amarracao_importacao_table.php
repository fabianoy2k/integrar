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
        Schema::create('regras_amarracao_importacao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layout_importacao_id')->constrained('layouts_importacao')->onDelete('cascade');
            $table->string('nome_regra');
            $table->enum('tipo', ['automatica', 'manual'])->default('automatica');
            $table->integer('ordem')->default(1);
            $table->boolean('ativo')->default(true);
            
            // Campos para mapeamento automático
            $table->string('coluna_data')->nullable();
            $table->string('coluna_valor')->nullable();
            $table->string('coluna_descricao')->nullable();
            $table->string('coluna_documento')->nullable();
            
            // Campos para valores manuais fixos
            $table->string('conta_debito_fixa')->nullable();
            $table->string('conta_credito_fixa')->nullable();
            $table->string('historico_fixo')->nullable();
            $table->string('centro_custo_fixo')->nullable();
            
            // Campos para múltiplos valores
            $table->json('colunas_valores')->nullable(); // ['valor1', 'valor2', 'valor3']
            $table->json('contas_debito')->nullable(); // ['conta1', 'conta2', 'conta3']
            $table->json('contas_credito')->nullable(); // ['conta1', 'conta2', 'conta3']
            $table->json('historicos')->nullable(); // ['hist1', 'hist2', 'hist3']
            
            $table->timestamps();
            
            $table->index(['layout_importacao_id', 'ordem']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regras_amarracao_importacao');
    }
};
