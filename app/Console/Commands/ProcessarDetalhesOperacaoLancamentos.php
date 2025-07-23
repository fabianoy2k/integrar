<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lancamento;
use Illuminate\Support\Facades\Log;

class ProcessarDetalhesOperacaoLancamentos extends Command
{
    protected $signature = 'lancamentos:processar-detalhes-operacao {--limit=1000 : Limite de lançamentos para processar}';
    protected $description = 'Processa os detalhes da operação para amarração nos lançamentos existentes';

    public function handle()
    {
        $this->info('Iniciando processamento de detalhes da operação...');
        
        $limit = (int) $this->option('limit');
        $totalLancamentos = Lancamento::whereNull('detalhes_operacao_para_amarracao')->count();
        
        $this->info("Total de lançamentos sem detalhes: {$totalLancamentos}");
        $this->info("Processando até {$limit} lançamentos...");
        
        $processados = 0;
        $atualizados = 0;
        
        // Processar em lotes para não sobrecarregar a memória
        Lancamento::whereNull('detalhes_operacao_para_amarracao')
            ->limit($limit)
            ->chunk(100, function($lancamentos) use (&$processados, &$atualizados) {
                foreach ($lancamentos as $lancamento) {
                    $processados++;
                    
                    // Processar detalhes da operação
                    $detalhes = $this->processarDetalhesOperacao($lancamento);
                    
                    if (!empty($detalhes)) {
                        $lancamento->detalhes_operacao_para_amarracao = $detalhes;
                        $lancamento->save();
                        $atualizados++;
                        
                        if ($processados % 100 === 0) {
                            $this->info("Processados: {$processados}, Atualizados: {$atualizados}");
                        }
                    }
                }
            });
        
        $this->info('');
        $this->info('✅ Processamento concluído!');
        $this->info("   - Lançamentos processados: {$processados}");
        $this->info("   - Lançamentos atualizados: {$atualizados}");
        
        return 0;
    }

    private function processarDetalhesOperacao($lancamento)
    {
        $historico = $lancamento->historico ?? '';
        $nomeEmpresa = $lancamento->nome_empresa ?? '';
        
        if (empty($historico)) {
            return '';
        }
        
        // Filtrar detalhes para amarração
        $palavrasTags = array_filter(
            array_map('trim', preg_split('/\s+/', preg_replace('/[^\w\s]/u', '', $historico)))
        );
        
        if ($nomeEmpresa) {
            $palavrasTerceiro = array_filter(
                array_map('trim', preg_split('/\s+/', preg_replace('/[^\w\s]/u', '', $nomeEmpresa)))
            );
            $palavrasTags = array_filter($palavrasTags, function($palavra) use ($palavrasTerceiro) {
                return !in_array(strtolower($palavra), array_map('strtolower', $palavrasTerceiro));
            });
        }
        
        $palavrasTags = $this->filtrarPadroesIndesejados($palavrasTags, $historico);
        
        // Gerar detalhes da operação
        if (count($palavrasTags) >= 2) {
            $detalhes = trim(implode(',', $palavrasTags), '"');
            $detalhes = str_replace('"', '', $detalhes);
            return trim($detalhes);
        }
        
        return '';
    }

    private function filtrarPadroesIndesejados($palavras, $historico)
    {
        // Dicionário de padrões que não devem ir para detalhes
        $padroesIndesejados = [
            'CFE', 'NF', 'Nº', 'N'
        ];
        
        // Preposições, artigos e crase que devem ser removidos
        $palavrasIndesejadas = [
            'DE', 'A', 'O', 'DO'
        ];
        
        // Filtrar palavras que estão no dicionário de padrões
        $palavras = array_filter($palavras, function($palavra) use ($padroesIndesejados) {
            return !in_array(strtoupper($palavra), $padroesIndesejados);
        });
        
        // Filtrar preposições, artigos e crase
        $palavras = array_filter($palavras, function($palavra) use ($palavrasIndesejadas) {
            return !in_array(strtoupper($palavra), $palavrasIndesejadas);
        });
        
        // Extrair números que vêm após "CFE NF Nº" ou "CFE NF N" para removê-los
        $numerosParaRemover = [];
        if (preg_match_all('/CFE\s+NF\s+N[^a-zA-Z]*\s+([0-9\/]+)/i', $historico, $matches)) {
            foreach ($matches[1] as $numero) {
                $partes = explode('/', $numero);
                foreach ($partes as $parte) {
                    if (is_numeric($parte)) {
                        $numerosParaRemover[] = $parte;
                    }
                }
            }
        }
        
        // Extrair números que vêm após "SERVIÇO TOMADO NESTA DATA" para removê-los
        if (preg_match_all('/SERVIÇO\s+TOMADO\s+NESTA\s+DATA\s+([0-9\/]+)/i', $historico, $matches)) {
            foreach ($matches[1] as $numero) {
                $partes = explode('/', $numero);
                foreach ($partes as $parte) {
                    if (is_numeric($parte)) {
                        $numerosParaRemover[] = $parte;
                    }
                }
            }
        }
        
        // Filtrar números que devem ser removidos
        $palavras = array_filter($palavras, function($palavra) use ($numerosParaRemover) {
            return !in_array($palavra, $numerosParaRemover);
        });
        
        return $palavras;
    }
} 