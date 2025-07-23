<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpresasOperadora extends Model
{
    use HasFactory;

    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'inscricao_estadual',
        'telefone',
        'email',
        'responsavel',
        'logo',
        'configuracoes',
    ];

    protected $casts = [
        'configuracoes' => 'array',
    ];
}
