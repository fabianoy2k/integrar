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
        Schema::create('parametros_extratos', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->comment('Nome descritivo do parâmetro');
            $table->enum('tipo_periodo', ['ano_mes', 'data_inicial_final'])->comment('Tipo de período: ano/mês ou data inicial/final');
            
            // Campos para período ano/mês
            $table->integer('ano')->nullable()->comment('Ano do período');
            $table->integer('mes')->nullable()->comment('Mês do período (1-12)');
            
            // Campos para período data inicial/final
            $table->date('data_inicial')->nullable()->comment('Data inicial do período');
            $table->date('data_final')->nullable()->comment('Data final do período');
            
            // Campos para conferência de extrato
            $table->string('conta_banco')->nullable()->comment('Conta bancária');
            $table->decimal('saldo_inicial', 15, 2)->nullable()->comment('Saldo inicial do período');
            $table->decimal('saldo_final', 15, 2)->nullable()->comment('Saldo final do período');
            $table->boolean('eh_conferencia')->default(false)->comment('Indica se é uma conferência de extrato');
            
            // Relacionamento com empresa
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->onDelete('set null');
            
            // Campos de controle
            $table->boolean('ativo')->default(true)->comment('Indica se o parâmetro está ativo');
            $table->text('observacoes')->nullable()->comment('Observações adicionais');
            $table->timestamps();
            
            // Índices para melhor performance
            $table->index(['empresa_id', 'ativo']);
            $table->index(['tipo_periodo', 'ano', 'mes']);
            $table->index(['tipo_periodo', 'data_inicial', 'data_final']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_extratos');
    }
};
