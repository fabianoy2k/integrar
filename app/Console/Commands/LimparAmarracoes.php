<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Amarracao;
use App\Models\Lancamento;
use App\Models\Empresa;
use Illuminate\Support\Facades\DB;

class LimparAmarracoes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amarracoes:limpar {--sistema= : Código do sistema específico para limpar} {--force : Força a limpeza sem confirmação} {--list : Lista todos os sistemas disponíveis}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpa amarrações por sistema ou todas as amarrações';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Se --list foi especificado, mostrar sistemas disponíveis
        if ($this->option('list')) {
            $this->listarSistemas();
            return 0;
        }

        $sistema = $this->option('sistema');
        
        if ($sistema) {
            $this->limparPorSistema($sistema);
        } else {
            $this->limparTodas();
        }
        
        return 0;
    }

    private function listarSistemas()
    {
        $this->info('=== SISTEMAS DISPONÍVEIS ===');
        
        // Buscar sistemas únicos das amarrações
        $sistemas = Amarracao::whereNotNull('codigo_sistema_empresa')
            ->distinct()
            ->pluck('codigo_sistema_empresa')
            ->filter()
            ->sort()
            ->values();
        
        // Buscar empresas para mostrar nomes
        $empresas = Empresa::whereNotNull('codigo_sistema')
            ->pluck('nome', 'codigo_sistema')
            ->toArray();
        
        if ($sistemas->isEmpty()) {
            $this->warn('Nenhum sistema encontrado nas amarrações.');
            return;
        }
        
        $this->table(
            ['Código Sistema', 'Nome da Empresa', 'Total Amarrações'],
            $sistemas->map(function($codigo) use ($empresas) {
                $nome = $empresas[$codigo] ?? 'Não encontrado';
                $total = Amarracao::where('codigo_sistema_empresa', $codigo)->count();
                return [$codigo, $nome, $total];
            })->toArray()
        );
        
        $this->info('');
        $this->info('Para limpar um sistema específico, use:');
        $this->info('php artisan amarracoes:limpar --sistema=CODIGO');
        $this->info('');
        $this->info('Para limpar todas as amarrações, use:');
        $this->info('php artisan amarracoes:limpar');
    }

    private function limparPorSistema($codigoSistema)
    {
        $empresa = Empresa::where('codigo_sistema', $codigoSistema)->first();
        $nomeEmpresa = $empresa ? $empresa->nome : 'Sistema não encontrado';
        
        $totalAmarracoes = Amarracao::where('codigo_sistema_empresa', $codigoSistema)->count();
        $totalLancamentos = Lancamento::whereHas('amarracao', function($q) use ($codigoSistema) {
            $q->where('codigo_sistema_empresa', $codigoSistema);
        })->count();
        
        $this->info('=== LIMPEZA DE AMARRAÇÕES POR SISTEMA ===');
        $this->info("Sistema: {$codigoSistema}");
        $this->info("Empresa: {$nomeEmpresa}");
        $this->info("Total de amarrações: {$totalAmarracoes}");
        $this->info("Total de lançamentos afetados: {$totalLancamentos}");
        $this->warn('');
        $this->warn('⚠️  ATENÇÃO: Esta operação irá:');
        $this->warn("   - Remover amarrações do sistema: {$codigoSistema}");
        $this->warn("   - Definir amarracao_id como NULL nos lançamentos relacionados");
        $this->warn('   - Esta ação NÃO pode ser desfeita!');
        $this->warn('');
        
        if (!$this->option('force')) {
            if (!$this->confirm('Tem certeza que deseja continuar?')) {
                $this->info('Operação cancelada pelo usuário.');
                return;
            }
        }
        
        $this->info('Iniciando limpeza...');
        
        try {
            DB::beginTransaction();
            
            // Buscar IDs das amarrações do sistema
            $amarracaoIds = Amarracao::where('codigo_sistema_empresa', $codigoSistema)
                ->pluck('id')
                ->toArray();
            
            // Remover referências de amarrações nos lançamentos
            $this->info('Removendo referências de amarrações nos lançamentos...');
            $lancamentosAtualizados = Lancamento::whereIn('amarracao_id', $amarracaoIds)
                ->update(['amarracao_id' => null]);
            
            $this->info("Lançamentos atualizados: {$lancamentosAtualizados}");
            
            // Remover amarrações do sistema
            $this->info('Removendo amarrações do sistema...');
            $amarracoesRemovidas = Amarracao::where('codigo_sistema_empresa', $codigoSistema)->delete();
            
            $this->info("Amarrações removidas: {$amarracoesRemovidas}");
            
            DB::commit();
            
            $this->info('');
            $this->info('✅ Limpeza concluída com sucesso!');
            $this->info("   - Sistema: {$codigoSistema}");
            $this->info("   - Empresa: {$nomeEmpresa}");
            $this->info("   - {$amarracoesRemovidas} amarrações removidas");
            $this->info("   - {$lancamentosAtualizados} lançamentos atualizados");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Erro durante a limpeza: ' . $e->getMessage());
        }
    }

    private function limparTodas()
    {
        $totalAmarracoes = Amarracao::count();
        $totalLancamentos = Lancamento::whereNotNull('amarracao_id')->count();
        
        $this->info('=== LIMPEZA COMPLETA DE AMARRAÇÕES ===');
        $this->info("Total de amarrações: {$totalAmarracoes}");
        $this->info("Total de lançamentos com amarração: {$totalLancamentos}");
        $this->warn('');
        $this->warn('⚠️  ATENÇÃO: Esta operação irá:');
        $this->warn('   - Remover TODAS as amarrações da tabela');
        $this->warn('   - Definir amarracao_id como NULL em todos os lançamentos');
        $this->warn('   - Esta ação NÃO pode ser desfeita!');
        $this->warn('');
        
        if (!$this->option('force')) {
            if (!$this->confirm('Tem certeza que deseja continuar?')) {
                $this->info('Operação cancelada pelo usuário.');
                return;
            }
            
            if (!$this->confirm('Confirma novamente? Esta ação é irreversível!')) {
                $this->info('Operação cancelada pelo usuário.');
                return;
            }
        }
        
        $this->info('Iniciando limpeza...');
        
        try {
            // Remover referências de amarrações nos lançamentos
            $this->info('Removendo referências de amarrações nos lançamentos...');
            $lancamentosAtualizados = Lancamento::whereNotNull('amarracao_id')
                ->update(['amarracao_id' => null]);
            
            $this->info("Lançamentos atualizados: {$lancamentosAtualizados}");
            
            // Limpar tabela de amarrações
            $this->info('Limpando tabela de amarrações...');
            $amarracoesRemovidas = Amarracao::count();
            Amarracao::truncate();
            
            $this->info("Amarrações removidas: {$amarracoesRemovidas}");
            
            $this->info('');
            $this->info('✅ Limpeza concluída com sucesso!');
            $this->info("   - {$amarracoesRemovidas} amarrações removidas");
            $this->info("   - {$lancamentosAtualizados} lançamentos atualizados");
            $this->info('   - Tabela de amarrações está vazia');
            
        } catch (\Exception $e) {
            $this->error('❌ Erro durante a limpeza: ' . $e->getMessage());
        }
    }
}
