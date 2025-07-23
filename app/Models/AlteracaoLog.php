<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlteracaoLog extends Model
{
    protected $fillable = [
        'lancamento_id',
        'campo_alterado',
        'valor_anterior',
        'valor_novo',
        'tipo_alteracao',
        'data_alteracao'
    ];

    protected $casts = [
        'data_alteracao' => 'datetime',
    ];

    public function lancamento(): BelongsTo
    {
        return $this->belongsTo(Lancamento::class);
    }
}
