<?php

echo "=== TESTE DE CONFIGURAÇÕES PHP ===\n";
echo "max_execution_time: " . ini_get('max_execution_time') . "\n";
echo "memory_limit: " . ini_get('memory_limit') . "\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";

echo "\n=== TESTE DE TIMEOUT ===\n";
$inicio = microtime(true);
echo "Iniciando teste de timeout...\n";

// Simular processamento pesado
for ($i = 0; $i < 1000000; $i++) {
    if ($i % 100000 === 0) {
        $tempo = microtime(true) - $inicio;
        echo "Processados {$i} registros em " . round($tempo, 2) . "s\n";
    }
    // Simular processamento
    $dados = str_repeat('teste', 100);
}

$tempo_total = microtime(true) - $inicio;
echo "Teste concluído em " . round($tempo_total, 2) . "s\n";
echo "SUCESSO: Timeout não foi atingido!\n"; 