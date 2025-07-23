<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Amarracao;
use Illuminate\Support\Facades\DB;

class LimparAmarracoesDuplicadas extends Command
{
    protected $signature = 'amarracoes:limpar-duplicadas';
    protected $description = 'Remove amarrações duplicadas baseadas em terceiro e detalhes_operacao';

    public function handle()
    {
        $this->info('Iniciando limpeza de amarrações duplicadas...');
        
        // Contar amarrações antes da limpeza
        $totalAntes = Amarracao::count();
        $this->info("Total de amarrações antes: {$totalAntes}");
        
        // Encontrar duplicatas
        $duplicatas = DB::table('amarracoes')
            ->select('terceiro', 'detalhes_operacao', DB::raw('COUNT(*) as total'))
            ->groupBy('terceiro', 'detalhes_operacao')
            ->having('total', '>', 1)
            ->get();
        
        $this->info("Encontradas " . count($duplicatas) . " combinações duplicadas");
        
        $removidas = 0;
        
        foreach ($duplicatas as $duplicata) {
            $this->info("Processando: terceiro='{$duplicata->terceiro}', detalhes='{$duplicata->detalhes_operacao}' ({$duplicata->total} registros)");
            
            // Manter o primeiro registro e remover os demais
            $registros = Amarracao::where('terceiro', $duplicata->terceiro)
                ->where('detalhes_operacao', $duplicata->detalhes_operacao)
                ->orderBy('id')
                ->get();
            
            // Manter o primeiro (mais antigo)
            $primeiro = $registros->first();
            $paraRemover = $registros->slice(1);
            
            foreach ($paraRemover as $registro) {
                // Atualizar lançamentos que usam esta amarração para usar a primeira
                DB::table('lancamentos')
                    ->where('amarracao_id', $registro->id)
                    ->update(['amarracao_id' => $primeiro->id]);
                
                // Remover a amarração duplicada
                $registro->delete();
                $removidas++;
            }
        }
        
        $totalDepois = Amarracao::count();
        
        $this->info("Limpeza concluída!");
        $this->info("Amarrações removidas: {$removidas}");
        $this->info("Total de amarrações após limpeza: {$totalDepois}");
        $this->info("Economia: " . ($totalAntes - $totalDepois) . " registros");
        
        return 0;
    }
} 