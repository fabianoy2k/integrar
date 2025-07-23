<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegraAmarracaoDescricao extends Model
{
    use HasFactory;

    protected $table = 'regras_amarracoes_descricoes';

    protected $fillable = [
        'empresa_id',
        'palavra_chave',
        'tipo_busca',
        'conta_debito',
        'conta_credito',
        'centro_custo',
        'ativo',
        'prioridade',
        'descricao',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function aplicarRegra(string $descricao): ?array
    {
        if (!$this->ativo) {
            return null;
        }

        $descricaoLower = strtolower($descricao);
        $palavraChaveLower = strtolower($this->palavra_chave);

        $match = false;
        switch ($this->tipo_busca) {
            case 'contains':
                $match = str_contains($descricaoLower, $palavraChaveLower);
                break;
            case 'starts_with':
                $match = str_starts_with($descricaoLower, $palavraChaveLower);
                break;
            case 'ends_with':
                $match = str_ends_with($descricaoLower, $palavraChaveLower);
                break;
            case 'exact':
                $match = $descricaoLower === $palavraChaveLower;
                break;
        }

        if (!$match) {
            return null;
        }

        return [
            'conta_debito' => $this->conta_debito,
            'conta_credito' => $this->conta_credito,
            'centro_custo' => $this->centro_custo,
        ];
    }
} 