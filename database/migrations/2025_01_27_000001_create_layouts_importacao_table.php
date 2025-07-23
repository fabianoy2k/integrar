<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layouts_importacao', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('tipo_arquivo'); // csv, xls, xlsx
            $table->string('delimitador')->nullable(); // para CSV
            $table->boolean('tem_cabecalho')->default(true);
            $table->json('configuracoes')->nullable(); // configurações adicionais
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['nome', 'empresa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layouts_importacao');
    }
}; 