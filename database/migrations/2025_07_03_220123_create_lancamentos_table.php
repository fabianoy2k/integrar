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
        Schema::create('lancamentos', function (Blueprint $table) {
            $table->id();
            $table->date('data');
            $table->string('historico');
            $table->string('conta_debito')->nullable();
            $table->string('conta_credito')->nullable();
            $table->decimal('valor', 15, 2);
            $table->string('terceiro')->nullable();
            $table->string('arquivo_origem')->nullable();
            $table->string('linha_arquivo')->nullable();
            $table->boolean('processado')->default(false);
            $table->timestamps();
            
            $table->index(['data', 'processado']);
            $table->index('terceiro');
            $table->index(['conta_debito', 'conta_credito']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lancamentos');
    }
};
