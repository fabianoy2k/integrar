<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regras_amarracoes_descricoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->string('palavra_chave'); // palavra ou expressão para buscar
            $table->string('tipo_busca'); // contains, starts_with, ends_with, exact
            $table->string('conta_debito')->nullable(); // conta de débito padrão
            $table->string('conta_credito')->nullable(); // conta de crédito padrão
            $table->string('centro_custo')->nullable(); // centro de custo padrão
            $table->boolean('ativo')->default(true);
            $table->integer('prioridade')->default(0); // ordem de prioridade
            $table->text('descricao')->nullable(); // descrição da regra
            $table->timestamps();
            
            $table->index(['empresa_id', 'palavra_chave', 'ativo'], 'regras_empresa_palavra_ativo_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regras_amarracoes_descricoes');
    }
}; 