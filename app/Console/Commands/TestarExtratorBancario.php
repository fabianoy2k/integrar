<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lancamento;
use App\Models\Empresa;
use Carbon\Carbon;

class TestarExtratorBancario extends Command
{
    protected $signature = 'testar:extrator-bancario {empresa_id} {conta_banco} {data_inicial} {data_final} {saldo_inicial} {saldo_final}';
    protected $description = 'Testa o extrator bancário com parâmetros específicos';

    public function handle()
    {
        $empresaId = $this->argument('empresa_id');
        $contaBanco = $this->argument('conta_banco');
        $dataInicial = $this->argument('data_inicial');
        $dataFinal = $this->argument('data_final');
        $saldoInicial = (float) $this->argument('saldo_inicial');
        $saldoFinal = (float) $this->argument('saldo_final');

        $this->info("Testando Extrator Bancário:");
        $this->info("Empresa ID: {$empresaId}");
        $this->info("Conta Banco: {$contaBanco}");
        $this->info("Período: {$dataInicial} a {$dataFinal}");
        $this->info("Saldo Inicial: R$ " . number_format($saldoInicial, 2, ',', '.'));
        $this->info("Saldo Final: R$ " . number_format($saldoFinal, 2, ',', '.'));
        $this->info("");

        // Buscar lançamentos
        $lancamentos = Lancamento::where('empresa_id', $empresaId)
            ->where(function ($query) use ($contaBanco) {
                $query->where('conta_debito', $contaBanco)
                      ->orWhere('conta_credito', $contaBanco);
            })
            ->whereBetween('data', [$dataInicial, $dataFinal])
            ->orderBy('data')
            ->orderBy('id')
            ->get();

        $this->info("Total de lançamentos encontrados: " . $lancamentos->count());
        $this->info("");

        // Calcular movimentação
        $movimentacaoTotal = 0;
        $debitos = 0;
        $creditos = 0;

        foreach ($lancamentos as $lancamento) {
            $valor = (float) $lancamento->valor;
            
            if ($lancamento->conta_debito === $contaBanco) {
                $movimentacaoTotal += $valor;
                $debitos += $valor;
                $this->line("DÉBITO: R$ " . number_format($valor, 2, ',', '.') . " - " . $lancamento->historico);
            } elseif ($lancamento->conta_credito === $contaBanco) {
                $movimentacaoTotal -= $valor;
                $creditos += $valor;
                $this->line("CRÉDITO: R$ " . number_format($valor, 2, ',', '.') . " - " . $lancamento->historico);
            }
        }

        $this->info("");
        $this->info("RESUMO:");
        $this->info("Débitos: R$ " . number_format($debitos, 2, ',', '.'));
        $this->info("Créditos: R$ " . number_format($creditos, 2, ',', '.'));
        $this->info("Movimentação Total: R$ " . number_format($movimentacaoTotal, 2, ',', '.'));
        
        $saldoCalculado = $saldoInicial + $movimentacaoTotal;
        $this->info("Saldo Calculado: R$ " . number_format($saldoCalculado, 2, ',', '.'));
        $this->info("Saldo Final Informado: R$ " . number_format($saldoFinal, 2, ',', '.'));
        
        $diferenca = $saldoFinal - $saldoCalculado;
        $this->info("Diferença: R$ " . number_format($diferenca, 2, ',', '.'));
        
        if ($diferenca == 0) {
            $this->info("✅ Saldos conferem!");
        } else {
            $this->error("❌ Saldos não conferem!");
        }

        return 0;
    }
} 