<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VerificarLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:verificar {--tipo=erro : Tipo de log (erro, info, warning)} {--linhas=50 : Número de linhas para mostrar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica os logs do sistema, especialmente relacionados a importações';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tipo = $this->option('tipo');
        $linhas = $this->option('linhas');
        
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            $this->error('Arquivo de log não encontrado: ' . $logFile);
            return 1;
        }
        
        $this->info("Verificando logs do tipo '{$tipo}' (últimas {$linhas} linhas):");
        $this->line('');
        
        // Ler o arquivo de log
        $conteudo = file_get_contents($logFile);
        $linhasLog = explode("\n", $conteudo);
        
        // Filtrar por tipo se especificado
        $linhasFiltradas = [];
        foreach ($linhasLog as $linha) {
            if (empty($linha)) continue;
            
            if ($tipo === 'todos' || strpos(strtolower($linha), strtolower($tipo)) !== false) {
                $linhasFiltradas[] = $linha;
            }
        }
        
        // Pegar as últimas linhas
        $linhasFiltradas = array_slice($linhasFiltradas, -$linhas);
        
        if (empty($linhasFiltradas)) {
            $this->warn("Nenhum log encontrado para o tipo '{$tipo}'");
            return 0;
        }
        
        // Exibir logs com formatação
        foreach ($linhasFiltradas as $linha) {
            if (strpos($linha, 'ERROR') !== false) {
                $this->error($linha);
            } elseif (strpos($linha, 'WARNING') !== false) {
                $this->warn($linha);
            } elseif (strpos($linha, 'INFO') !== false) {
                $this->info($linha);
            } else {
                $this->line($linha);
            }
        }
        
        $this->line('');
        $this->info('Total de linhas encontradas: ' . count($linhasFiltradas));
        
        return 0;
    }
}
