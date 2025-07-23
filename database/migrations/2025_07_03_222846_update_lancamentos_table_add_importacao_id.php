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
        Schema::table('lancamentos', function (Blueprint $table) {
            // Adicionar foreign key para importação
            $table->foreignId('importacao_id')->nullable()->constrained('importacoes')->onDelete('cascade');
            
            // Adicionar todas as colunas do CSV
            $table->string('usuario')->nullable();
            $table->string('codigo_filial_matriz')->nullable();
            $table->string('nome_empresa')->nullable();
            $table->string('numero_nota')->nullable();
            
            // Adicionar foreign key para terceiro
            $table->foreignId('terceiro_id')->nullable()->constrained('terceiros')->onDelete('set null');
            
            // Remover campos antigos que não existem no CSV
            $table->dropColumn(['arquivo_origem', 'linha_arquivo', 'processado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lancamentos', function (Blueprint $table) {
            $table->dropForeign(['importacao_id']);
            $table->dropForeign(['terceiro_id']);
            $table->dropColumn(['importacao_id', 'usuario', 'codigo_filial_matriz', 'nome_empresa', 'numero_nota', 'terceiro_id']);
            
            // Restaurar campos antigos
            $table->string('arquivo_origem')->nullable();
            $table->string('linha_arquivo')->nullable();
            $table->boolean('processado')->default(false);
        });
    }
};
