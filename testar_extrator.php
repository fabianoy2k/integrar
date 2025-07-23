<?php

require_once 'vendor/autoload.php';

use App\Models\Lancamento;
use App\Models\Empresa;
use Carbon\Carbon;

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Parâmetros de teste (baseado nos logs)
$empresaId = 2;
$contaBanco = "8";
$dataInicial = "2025-03-01";
$dataFinal = "2025-03-31";
$saldoInicial = 2781.08;
$saldoFinal = 37393.91;

echo "=== TESTE DO EXTRATOR BANCÁRIO ===\n";
echo "Empresa ID: {$empresaId}\n";
echo "Conta Banco: {$contaBanco}\n";
echo "Período: {$dataInicial} a {$dataFinal}\n";
echo "Saldo Inicial: R$ " . number_format($saldoInicial, 2, ',', '.') . "\n";
echo "Saldo Final: R$ " . number_format($saldoFinal, 2, ',', '.') . "\n\n";

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

echo "Total de lançamentos encontrados: " . $lancamentos->count() . "\n\n";

// Calcular movimentação
$movimentacaoTotal = 0;
$debitos = 0;
$creditos = 0;

foreach ($lancamentos as $lancamento) {
    $valor = (float) $lancamento->valor;
    
    if ($lancamento->conta_debito === $contaBanco) {
        $movimentacaoTotal += $valor;
        $debitos += $valor;
        echo "DÉBITO: R$ " . number_format($valor, 2, ',', '.') . " - " . $lancamento->historico . "\n";
    } elseif ($lancamento->conta_credito === $contaBanco) {
        $movimentacaoTotal -= $valor;
        $creditos += $valor;
        echo "CRÉDITO: R$ " . number_format($valor, 2, ',', '.') . " - " . $lancamento->historico . "\n";
    }
}

echo "\n=== RESUMO ===\n";
echo "Débitos: R$ " . number_format($debitos, 2, ',', '.') . "\n";
echo "Créditos: R$ " . number_format($creditos, 2, ',', '.') . "\n";
echo "Movimentação Total: R$ " . number_format($movimentacaoTotal, 2, ',', '.') . "\n";

$saldoCalculado = $saldoInicial + $movimentacaoTotal;
echo "Saldo Calculado: R$ " . number_format($saldoCalculado, 2, ',', '.') . "\n";
echo "Saldo Final Informado: R$ " . number_format($saldoFinal, 2, ',', '.') . "\n";

$diferenca = $saldoFinal - $saldoCalculado;
echo "Diferença: R$ " . number_format($diferenca, 2, ',', '.') . "\n";

if ($diferenca == 0) {
    echo "✅ Saldos conferem!\n";
} else {
    echo "❌ Saldos não conferem!\n";
}

echo "\n=== DETALHES DOS PRIMEIROS 5 LANÇAMENTOS ===\n";
foreach ($lancamentos->take(5) as $lancamento) {
    echo "Data: " . $lancamento->data->format('Y-m-d') . "\n";
    echo "Valor: R$ " . number_format($lancamento->valor, 2, ',', '.') . "\n";
    echo "Débito: " . $lancamento->conta_debito . "\n";
    echo "Crédito: " . $lancamento->conta_credito . "\n";
    echo "Histórico: " . $lancamento->historico . "\n";
    echo "---\n";
} 