<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amarracao extends Model
{
    protected $table = 'amarracoes';
    protected $guarded = [];
    protected $fillable = [
        'terceiro',
        'detalhes_operacao',
        'conta_debito',
        'conta_credito',
        'codigo_sistema_empresa'
    ];
    protected $casts = [
        // detalhes_operacao agora é string, não array
    ];
} 