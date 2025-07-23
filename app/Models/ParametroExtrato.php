<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ParametroExtrato extends Model
{
    use HasFactory;

    protected $table = 'parametros_extratos';

    protected $fillable = [
        'nome',
        'tipo_periodo',
        'ano',
        'mes',
        'data_inicial',
        'data_final',
        'conta_banco',
        'saldo_inicial',
        'saldo_final',
        'eh_conferencia',
        'empresa_id',
        'ativo',
        'observacoes',
    ];

    protected $casts = [
        'eh_conferencia' => 'boolean',
        'ativo' => 'boolean',
        'saldo_inicial' => 'decimal:2',
        'saldo_final' => 'decimal:2',
        'data_inicial' => 'date',
        'data_final' => 'date',
    ];

    // Relacionamentos
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    // Scopes
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeConferencias($query)
    {
        return $query->where('eh_conferencia', true);
    }

    public function scopePorEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    // Métodos auxiliares
    public function getPeriodoFormatadoAttribute()
    {
        if ($this->tipo_periodo === 'ano_mes') {
            $meses = [
                1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
            ];
            return $meses[$this->mes] . '/' . $this->ano;
        } else {
            return Carbon::parse($this->data_inicial)->format('d/m/Y') . ' a ' . 
                   Carbon::parse($this->data_final)->format('d/m/Y');
        }
    }

    public function getDataInicialCalculadaAttribute()
    {
        if ($this->tipo_periodo === 'ano_mes') {
            return Carbon::create($this->ano, $this->mes, 1)->startOfMonth();
        }
        return $this->data_inicial;
    }

    public function getDataFinalCalculadaAttribute()
    {
        if ($this->tipo_periodo === 'ano_mes') {
            return Carbon::create($this->ano, $this->mes, 1)->endOfMonth();
        }
        return $this->data_final;
    }

    public function getSaldoInicialFormatadoAttribute()
    {
        return $this->saldo_inicial ? 'R$ ' . number_format($this->saldo_inicial, 2, ',', '.') : '-';
    }

    public function getSaldoFinalFormatadoAttribute()
    {
        return $this->saldo_final ? 'R$ ' . number_format($this->saldo_final, 2, ',', '.') : '-';
    }

    public function getTipoPeriodoFormatadoAttribute()
    {
        return $this->tipo_periodo === 'ano_mes' ? 'Ano/Mês' : 'Data Inicial/Final';
    }

    public function getEhConferenciaFormatadoAttribute()
    {
        return $this->eh_conferencia ? 'Sim' : 'Não';
    }

    // Validação
    public static function rules()
    {
        return [
            'nome' => 'required|string|max:255',
            'tipo_periodo' => 'required|in:ano_mes,data_inicial_final',
            'ano' => 'nullable|integer|min:1900|max:2100',
            'mes' => 'nullable|integer|min:1|max:12',
            'data_inicial' => 'nullable|date',
            'data_final' => 'nullable|date|after_or_equal:data_inicial',
            'conta_banco' => 'nullable|string|max:255',
            'saldo_inicial' => 'nullable|numeric|min:0',
            'saldo_final' => 'nullable|numeric|min:0',
            'eh_conferencia' => 'boolean',
            'empresa_id' => 'nullable|exists:empresas,id',
            'ativo' => 'boolean',
            'observacoes' => 'nullable|string',
        ];
    }

    public static function messages()
    {
        return [
            'nome.required' => 'O nome é obrigatório',
            'tipo_periodo.required' => 'O tipo de período é obrigatório',
            'tipo_periodo.in' => 'O tipo de período deve ser "Ano/Mês" ou "Data Inicial/Final"',
            'ano.integer' => 'O ano deve ser um número inteiro',
            'ano.min' => 'O ano deve ser maior que 1900',
            'ano.max' => 'O ano deve ser menor que 2100',
            'mes.integer' => 'O mês deve ser um número inteiro',
            'mes.min' => 'O mês deve ser entre 1 e 12',
            'mes.max' => 'O mês deve ser entre 1 e 12',
            'data_inicial.date' => 'A data inicial deve ser uma data válida',
            'data_final.date' => 'A data final deve ser uma data válida',
            'data_final.after_or_equal' => 'A data final deve ser igual ou posterior à data inicial',
            'saldo_inicial.numeric' => 'O saldo inicial deve ser um número',
            'saldo_inicial.min' => 'O saldo inicial deve ser maior ou igual a zero',
            'saldo_final.numeric' => 'O saldo final deve ser um número',
            'saldo_final.min' => 'O saldo final deve ser maior ou igual a zero',
            'empresa_id.exists' => 'A empresa selecionada não existe',
        ];
    }
}
