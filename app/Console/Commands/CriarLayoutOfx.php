<?php

namespace App\Console\Commands;

use App\Models\LayoutImportacao;
use App\Models\LayoutColuna;
use App\Models\Empresa;
use Illuminate\Console\Command;

class CriarLayoutOfx extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'layout:criar-ofx {empresa_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria o layout de importação para arquivos OFX (Open Financial Exchange)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $empresaId = $this->argument('empresa_id');
        
        if (!$empresaId) {
            // Listar empresas disponíveis
            $empresas = Empresa::all();
            if ($empresas->isEmpty()) {
                $this->error('Nenhuma empresa encontrada. Crie uma empresa primeiro.');
                return 1;
            }
            
            $this->info('Empresas disponíveis:');
            foreach ($empresas as $empresa) {
                $this->line("ID: {$empresa->id} - Nome: {$empresa->nome}");
            }
            
            $empresaId = $this->ask('Digite o ID da empresa para criar o layout');
        }
        
        $empresa = Empresa::find($empresaId);
        if (!$empresa) {
            $this->error("Empresa com ID {$empresaId} não encontrada.");
            return 1;
        }
        
        $this->info("Criando layout para empresa: {$empresa->nome}");
        
        // Verificar se já existe um layout com esse nome
        $layoutExistente = LayoutImportacao::where('nome', 'Formato OFX')
            ->where('empresa_id', $empresa->id)
            ->first();
            
        if ($layoutExistente) {
            if ($this->confirm('Já existe um layout "Formato OFX" para esta empresa. Deseja recriar?')) {
                $layoutExistente->delete();
                $this->info('Layout existente removido.');
            } else {
                $this->info('Operação cancelada.');
                return 0;
            }
        }
        
        // Criar o layout
        $layout = LayoutImportacao::create([
            'nome' => 'Formato OFX',
            'tipo_arquivo' => 'ofx',
            'delimitador' => null,
            'tem_cabecalho' => false,
            'configuracoes' => [
                'banco' => '041',
                'tipo_conta' => 'CHECKING',
                'formato_data' => 'DD/MM/YYYY',
                'formato_valor' => 'BR',
                'encoding' => 'utf-8'
            ],
            'empresa_id' => $empresa->id,
            'user_id' => 1, // Assumindo que o usuário admin tem ID 1
        ]);
        
        $this->info("Layout criado com ID: {$layout->id}");
        
        // Definir as colunas do layout
        $colunas = [
            [
                'coluna_arquivo' => 'Data do Lançamento',
                'campo_lancamento' => 'data',
                'tipo_transformacao' => 'date',
                'configuracao_transformacao' => ['formato_entrada' => 'DD/MM/YYYY', 'formato_saida' => 'Y-m-d'],
                'obrigatorio' => true,
                'ordem' => 1
            ],
            [
                'coluna_arquivo' => 'Usuário',
                'campo_lancamento' => 'usuario',
                'tipo_transformacao' => 'text',
                'configuracao_transformacao' => null,
                'obrigatorio' => false,
                'ordem' => 2
            ],
            [
                'coluna_arquivo' => 'Conta Débito',
                'campo_lancamento' => 'conta_debito',
                'tipo_transformacao' => 'text',
                'configuracao_transformacao' => null,
                'obrigatorio' => false,
                'ordem' => 3
            ],
            [
                'coluna_arquivo' => 'Conta Crédito',
                'campo_lancamento' => 'conta_credito',
                'tipo_transformacao' => 'text',
                'configuracao_transformacao' => null,
                'obrigatorio' => false,
                'ordem' => 4
            ],
            [
                'coluna_arquivo' => 'Valor do Lançamento',
                'campo_lancamento' => 'valor',
                'tipo_transformacao' => 'number',
                'configuracao_transformacao' => ['formato_entrada' => 'BR', 'separador_decimal' => ',', 'separador_milhares' => '.'],
                'obrigatorio' => true,
                'ordem' => 5
            ],
            [
                'coluna_arquivo' => 'Histórico',
                'campo_lancamento' => 'historico',
                'tipo_transformacao' => 'text',
                'configuracao_transformacao' => null,
                'obrigatorio' => true,
                'ordem' => 6
            ],
            [
                'coluna_arquivo' => 'Código da Filial/Matriz',
                'campo_lancamento' => 'codigo_filial_matriz',
                'tipo_transformacao' => 'text',
                'configuracao_transformacao' => null,
                'obrigatorio' => false,
                'ordem' => 7
            ],
            [
                'coluna_arquivo' => 'Nome da Empresa',
                'campo_lancamento' => 'terceiro_nome',
                'tipo_transformacao' => 'text',
                'configuracao_transformacao' => null,
                'obrigatorio' => false,
                'ordem' => 8
            ],
            [
                'coluna_arquivo' => 'Número da Nota',
                'campo_lancamento' => 'numero_nota',
                'tipo_transformacao' => 'text',
                'configuracao_transformacao' => null,
                'obrigatorio' => false,
                'ordem' => 9
            ],
            [
                'coluna_arquivo' => 'Tipo Transação',
                'campo_lancamento' => 'tipo_transacao',
                'tipo_transformacao' => 'text',
                'configuracao_transformacao' => null,
                'obrigatorio' => false,
                'ordem' => 10
            ],
            [
                'coluna_arquivo' => 'ID Transação',
                'campo_lancamento' => 'id_transacao',
                'tipo_transformacao' => 'text',
                'configuracao_transformacao' => null,
                'obrigatorio' => false,
                'ordem' => 11
            ],
            [
                'coluna_arquivo' => 'Número Cheque',
                'campo_lancamento' => 'numero_cheque',
                'tipo_transformacao' => 'text',
                'configuracao_transformacao' => null,
                'obrigatorio' => false,
                'ordem' => 12
            ],
            [
                'coluna_arquivo' => 'Banco',
                'campo_lancamento' => 'banco',
                'tipo_transformacao' => 'text',
                'configuracao_transformacao' => null,
                'obrigatorio' => false,
                'ordem' => 13
            ],
            [
                'coluna_arquivo' => 'Conta',
                'campo_lancamento' => 'conta',
                'tipo_transformacao' => 'text',
                'configuracao_transformacao' => null,
                'obrigatorio' => false,
                'ordem' => 14
            ],
            [
                'coluna_arquivo' => 'Tipo Conta',
                'campo_lancamento' => 'tipo_conta',
                'tipo_transformacao' => 'text',
                'configuracao_transformacao' => null,
                'obrigatorio' => false,
                'ordem' => 15
            ]
        ];
        
        // Criar as colunas
        foreach ($colunas as $coluna) {
            LayoutColuna::create([
                'layout_importacao_id' => $layout->id,
                'coluna_arquivo' => $coluna['coluna_arquivo'],
                'campo_lancamento' => $coluna['campo_lancamento'],
                'tipo_transformacao' => $coluna['tipo_transformacao'],
                'configuracao_transformacao' => $coluna['configuracao_transformacao'],
                'obrigatorio' => $coluna['obrigatorio'],
                'ordem' => $coluna['ordem'],
            ]);
        }
        
        $this->info("Layout 'Formato OFX' criado com sucesso para a empresa {$empresa->nome}!");
        $this->info("Total de colunas criadas: " . count($colunas));
        $this->info("Layout ID: {$layout->id}");
        
        return 0;
    }
}


