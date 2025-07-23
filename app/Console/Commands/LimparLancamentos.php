<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lancamento;
use App\Models\AlteracaoLog;
use App\Models\Importacao;
use Illuminate\Support\Facades\DB;

class LimparLancamentos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lancamentos:limpar {--confirm : Confirma a limpeza sem perguntar} {--importacao= : Limpa apenas lançamentos de uma importação específica}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpa a tabela lancamentos e seus relacionamentos de forma segura';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $importacaoId = $this->option('importacao');
        $confirm = $this->option('confirm');

        if ($importacaoId) {
            // Limpar apenas lançamentos de uma importação específica
            $this->limparPorImportacao($importacaoId, $confirm);
        } else {
            // Limpar todos os lançamentos
            $this->limparTodos($confirm);
        }

        return 0;
    }

    private function limparPorImportacao($importacaoId, $confirm)
    {
        $importacao = Importacao::find($importacaoId);
        
        if (!$importacao) {
            $this->error("Importação com ID {$importacaoId} não encontrada.");
            return;
        }

        $totalLancamentos = Lancamento::where('importacao_id', $importacaoId)->count();
        $totalLogs = AlteracaoLog::whereHas('lancamento', function($query) use ($importacaoId) {
            $query->where('importacao_id', $importacaoId);
        })->count();

        $this->info("Importação: {$importacao->nome_arquivo}");
        $this->info("Total de lançamentos: {$totalLancamentos}");
        $this->info("Total de logs de alteração: {$totalLogs}");

        if (!$confirm) {
            if (!$this->confirm("Deseja realmente limpar os lançamentos desta importação?")) {
                $this->info("Operação cancelada.");
                return;
            }
        }

        $this->info("Iniciando limpeza...");

        DB::transaction(function () use ($importacaoId) {
            // Deletar logs de alteração primeiro (devido à foreign key)
            $lancamentoIds = Lancamento::where('importacao_id', $importacaoId)->pluck('id');
            
            if ($lancamentoIds->count() > 0) {
                AlteracaoLog::whereIn('lancamento_id', $lancamentoIds)->delete();
                $this->info("Logs de alteração deletados.");
            }

            // Deletar lançamentos
            $deleted = Lancamento::where('importacao_id', $importacaoId)->delete();
            $this->info("{$deleted} lançamentos deletados.");

            // Atualizar status da importação
            Importacao::where('id', $importacaoId)->update([
                'total_registros' => 0,
                'registros_processados' => 0,
                'status' => 'pendente'
            ]);
        });

        $this->info("Limpeza concluída com sucesso!");
    }

    private function limparTodos($confirm)
    {
        $totalLancamentos = Lancamento::count();
        $totalLogs = AlteracaoLog::count();
        $totalImportacoes = Importacao::count();

        $this->info("=== RESUMO DA LIMPEZA ===");
        $this->info("Total de lançamentos: {$totalLancamentos}");
        $this->info("Total de logs de alteração: {$totalLogs}");
        $this->info("Total de importações: {$totalImportacoes}");
        $this->line("");

        if (!$confirm) {
            if (!$this->confirm("ATENÇÃO: Esta operação irá deletar TODOS os lançamentos e logs. Deseja continuar?")) {
                $this->info("Operação cancelada.");
                return;
            }

            if (!$this->confirm("Tem certeza absoluta? Esta ação não pode ser desfeita!")) {
                $this->info("Operação cancelada.");
                return;
            }
        }

        $this->info("Iniciando limpeza completa...");

        DB::transaction(function () {
            // Deletar logs de alteração primeiro (devido à foreign key)
            $deletedLogs = AlteracaoLog::count();
            AlteracaoLog::query()->delete();
            $this->info("{$deletedLogs} logs de alteração deletados.");

            // Deletar lançamentos
            $deletedLancamentos = Lancamento::count();
            Lancamento::query()->delete();
            $this->info("{$deletedLancamentos} lançamentos deletados.");

            // Resetar importações
            Importacao::query()->update([
                'total_registros' => 0,
                'registros_processados' => 0,
                'status' => 'pendente'
            ]);
            $this->info("Importações resetadas.");
        });

        $this->info("Limpeza completa concluída com sucesso!");
    }
}
