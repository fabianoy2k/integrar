<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegraAmarracaoImportacao extends Model
{
    protected $table = 'regras_amarracao_importacao';
    
    protected $fillable = [
        'layout_importacao_id',
        'nome_regra',
        'tipo',
        'ordem',
        'ativo',
        'coluna_data',
        'coluna_valor',
        'coluna_descricao',
        'coluna_documento',
        'conta_debito_fixa',
        'conta_credito_fixa',
        'historico_fixo',
        'centro_custo_fixo',
        'colunas_valores',
        'contas_debito',
        'contas_credito',
        'historicos',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'colunas_valores' => 'array',
        'contas_debito' => 'array',
        'contas_credito' => 'array',
        'historicos' => 'array',
    ];

    public function layoutImportacao(): BelongsTo
    {
        return $this->belongsTo(LayoutImportacao::class);
    }

    public function getMapeamentoColunasAttribute()
    {
        $mapeamento = [];
        
        if ($this->coluna_data) {
            $mapeamento[$this->coluna_data] = 'data';
        }
        if ($this->coluna_valor) {
            $mapeamento[$this->coluna_valor] = 'valor';
        }
        if ($this->coluna_descricao) {
            $mapeamento[$this->coluna_descricao] = 'descricao';
        }
        if ($this->coluna_documento) {
            $mapeamento[$this->coluna_documento] = 'documento';
        }
        
        return $mapeamento;
    }

    public function getValoresFixosAttribute()
    {
        return [
            'conta_debito' => $this->conta_debito_fixa,
            'conta_credito' => $this->conta_credito_fixa,
            'historico' => $this->historico_fixo,
            'centro_custo' => $this->centro_custo_fixo,
        ];
    }

    public function getMapeamentoMultiploAttribute()
    {
        return [
            'colunas_valores' => $this->colunas_valores ?? [],
            'contas_debito' => $this->contas_debito ?? [],
            'contas_credito' => $this->contas_credito ?? [],
            'historicos' => $this->historicos ?? [],
        ];
    }
} 