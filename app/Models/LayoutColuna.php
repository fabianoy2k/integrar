<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LayoutColuna extends Model
{
    use HasFactory;

    protected $table = 'layouts_colunas';

    protected $fillable = [
        'layout_importacao_id',
        'coluna_arquivo',
        'campo_lancamento',
        'tipo_transformacao',
        'configuracao_transformacao',
        'obrigatorio',
        'ordem',
    ];

    protected $casts = [
        'obrigatorio' => 'boolean',
        'configuracao_transformacao' => 'array',
    ];

    public function layoutImportacao(): BelongsTo
    {
        return $this->belongsTo(LayoutImportacao::class, 'layout_importacao_id');
    }
} 