<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Amarracao;

class ConverterAmarracoesJsonParaString extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'amarracoes:converter-json-string';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Converte o campo detalhes_operacao de JSON para string separada por vírgula nas amarrações';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Convertendo amarrações antigas...');
        $total = 0;
        $atualizados = 0;
        foreach (Amarracao::all() as $amarracao) {
            $total++;
            $valor = $amarracao->detalhes_operacao;
            if (is_array($valor)) {
                $str = implode(',', $valor);
                $amarracao->detalhes_operacao = $str;
                $amarracao->save();
                $atualizados++;
                $this->line("Amarração ID {$amarracao->id} convertida para string: {$str}");
            } elseif ($this->isJson($valor)) {
                $arr = json_decode($valor, true);
                if (is_array($arr)) {
                    $str = implode(',', $arr);
                    $amarracao->detalhes_operacao = $str;
                    $amarracao->save();
                    $atualizados++;
                    $this->line("Amarração ID {$amarracao->id} convertida de JSON para string: {$str}");
                }
            }
        }
        $this->info("Total de amarrações processadas: {$total}");
        $this->info("Total convertidas: {$atualizados}");
    }

    private function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
