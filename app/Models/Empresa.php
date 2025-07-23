<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cnpj',
        'codigo_sistema',
        'codigo_conta_banco',
    ];

    public function importacoes()
    {
        return $this->hasMany(Importacao::class);
    }

    public function lancamentos()
    {
        return $this->hasMany(Lancamento::class);
    }
}
