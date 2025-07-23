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
            if (!Schema::hasColumn('lancamentos', 'codigo_filial_matriz')) {
                $table->string('codigo_filial_matriz')->nullable()->after('valor');
            }
            if (!Schema::hasIndex('lancamentos', 'lancamentos_codigo_filial_matriz_index')) {
                $table->index('codigo_filial_matriz');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lancamentos', function (Blueprint $table) {
            $table->dropIndex(['codigo_filial_matriz']);
            $table->dropColumn('codigo_filial_matriz');
        });
    }
};
