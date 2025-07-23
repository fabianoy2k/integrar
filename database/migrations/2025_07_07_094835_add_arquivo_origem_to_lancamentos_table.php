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
            $table->string('arquivo_origem')->nullable()->after('empresa_id');
            $table->integer('linha_arquivo')->nullable()->after('arquivo_origem');
            $table->boolean('processado')->default(true)->after('linha_arquivo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lancamentos', function (Blueprint $table) {
            $table->dropColumn(['arquivo_origem', 'linha_arquivo', 'processado']);
        });
    }
};
