<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Terceiro extends Model
{
    protected $fillable = [
        'nome',
        'cnpj_cpf',
        'tipo',
        'observacoes',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function lancamentos(): HasMany
    {
        return $this->hasMany(Lancamento::class);
    }
}
