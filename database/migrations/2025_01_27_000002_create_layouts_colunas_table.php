<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layouts_colunas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layout_importacao_id')->constrained('layouts_importacao')->onDelete('cascade');
            $table->string('coluna_arquivo'); // nome da coluna no arquivo
            $table->string('campo_lancamento'); // campo na tabela lancamentos
            $table->string('tipo_transformacao')->nullable(); // date, number, text, etc
            $table->json('configuracao_transformacao')->nullable(); // configurações da transformação
            $table->boolean('obrigatorio')->default(false);
            $table->integer('ordem')->default(0);
            $table->timestamps();
            
            $table->unique(['layout_importacao_id', 'coluna_arquivo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layouts_colunas');
    }
}; 