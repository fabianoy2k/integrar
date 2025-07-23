<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Importacao;

class LogsImportacao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:importacao {--recente : Mostrar apenas logs das últimas 24 horas} {--erro : Mostrar apenas erros}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analisa logs específicos de importação e mostra informações detalhadas sobre erros';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recente = $this->option('recente');
        $erro = $this->option('erro');
        
        $logFile = storage_path('logs/laravel.log');
        
        if (!file_exists($logFile)) {
            $this->error('Arquivo de log não encontrado: ' . $logFile);
            return 1;
        }
        
        $this->info('=== ANÁLISE DE LOGS DE IMPORTAÇÃO ===');
        $this->line('');
        
        // Ler o arquivo de log
        $conteudo = file_get_contents($logFile);
        $linhasLog = explode("\n", $conteudo);
        
        // Filtrar linhas relacionadas a importação
        $logsImportacao = [];
        $errosImportacao = [];
        
        foreach ($linhasLog as $linha) {
            if (empty($linha)) continue;
            
            // Verificar se é log de importação
            if (strpos($linha, 'importação') !== false || 
                strpos($linha, 'Importador') !== false ||
                strpos($linha, 'conversão') !== false ||
                strpos($linha, 'Python') !== false) {
                
                $logsImportacao[] = $linha;
                
                // Se for erro, adicionar à lista de erros
                if (strpos($linha, 'ERROR') !== false) {
                    $errosImportacao[] = $linha;
                }
            }
        }
        
        // Filtrar por data se solicitado
        if ($recente) {
            $ontem = date('Y-m-d', strtotime('-1 day'));
            $logsImportacao = array_filter($logsImportacao, function($linha) use ($ontem) {
                return strpos($linha, $ontem) !== false || strpos($linha, date('Y-m-d')) !== false;
            });
        }
        
        // Mostrar apenas erros se solicitado
        if ($erro) {
            $logsImportacao = $errosImportacao;
        }
        
        if (empty($logsImportacao)) {
            $this->warn('Nenhum log de importação encontrado' . ($recente ? ' nas últimas 24 horas' : ''));
            return 0;
        }
        
        // Pegar as últimas 100 linhas
        $logsImportacao = array_slice($logsImportacao, -100);
        
        $this->info('Logs encontrados: ' . count($logsImportacao));
        $this->line('');
        
        // Agrupar logs por tipo
        $logsPorTipo = [
            'erro' => [],
            'info' => [],
            'warning' => []
        ];
        
        foreach ($logsImportacao as $linha) {
            if (strpos($linha, 'ERROR') !== false) {
                $logsPorTipo['erro'][] = $linha;
            } elseif (strpos($linha, 'WARNING') !== false) {
                $logsPorTipo['warning'][] = $linha;
            } elseif (strpos($linha, 'INFO') !== false) {
                $logsPorTipo['info'][] = $linha;
            }
        }
        
        // Mostrar erros primeiro
        if (!empty($logsPorTipo['erro'])) {
            $this->error('=== ERROS ===');
            foreach ($logsPorTipo['erro'] as $linha) {
                $this->error($linha);
            }
            $this->line('');
        }
        
        // Mostrar warnings
        if (!empty($logsPorTipo['warning'])) {
            $this->warn('=== WARNINGS ===');
            foreach ($logsPorTipo['warning'] as $linha) {
                $this->warn($linha);
            }
            $this->line('');
        }
        
        // Mostrar informações
        if (!empty($logsPorTipo['info'])) {
            $this->info('=== INFORMAÇÕES ===');
            foreach ($logsPorTipo['info'] as $linha) {
                $this->info($linha);
            }
            $this->line('');
        }
        
        // Estatísticas
        $this->info('=== ESTATÍSTICAS ===');
        $this->line("Total de logs: " . count($logsImportacao));
        $this->line("Erros: " . count($logsPorTipo['erro']));
        $this->line("Warnings: " . count($logsPorTipo['warning']));
        $this->line("Informações: " . count($logsPorTipo['info']));
        
        // Verificar importações recentes no banco
        $this->line('');
        $this->info('=== IMPORTAÇÕES RECENTES NO BANCO ===');
        
        $importacoes = Importacao::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        if ($importacoes->isEmpty()) {
            $this->warn('Nenhuma importação encontrada no banco de dados');
        } else {
            $headers = ['ID', 'Arquivo', 'Status', 'Registros', 'Processados', 'Data'];
            $rows = [];
            
            foreach ($importacoes as $importacao) {
                $rows[] = [
                    $importacao->id,
                    $importacao->nome_arquivo,
                    $importacao->status,
                    $importacao->total_registros,
                    $importacao->registros_processados,
                    $importacao->created_at->format('d/m/Y H:i:s')
                ];
            }
            
            $this->table($headers, $rows);
        }
        
        return 0;
    }
}
