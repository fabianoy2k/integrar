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
        Schema::create('importacoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_arquivo');
            $table->integer('total_registros')->default(0);
            $table->integer('registros_processados')->default(0);
            $table->enum('status', ['pendente', 'processando', 'concluida', 'erro'])->default('pendente');
            $table->text('erro_mensagem')->nullable();
            $table->string('usuario')->nullable();
            $table->string('codigo_empresa', 7)->nullable();
            $table->string('cnpj_empresa', 14)->nullable();
            $table->date('data_inicial')->nullable();
            $table->date('data_final')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('importacoes');
    }
};
