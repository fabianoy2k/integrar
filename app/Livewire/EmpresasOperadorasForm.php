<?php

namespace App\Livewire;

use App\Models\EmpresasOperadora;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class EmpresasOperadorasForm extends Component
{
    use WithFileUploads;

    public $empresas;
    public $empresa_id;
    public $razao_social;
    public $nome_fantasia;
    public $cnpj;
    public $inscricao_estadual;
    public $telefone;
    public $email;
    public $responsavel;
    public $logo;
    public $logo_atual;
    public $configuracoes;
    public $modoEdicao = false;

    protected function rules()
    {
        return [
            'razao_social' => 'required|string|max:255',
            'nome_fantasia' => 'nullable|string|max:255',
            'cnpj' => [
                'required',
                'string',
                'size:18',
                Rule::unique('empresas_operadoras', 'cnpj')->ignore($this->empresa_id),
                function($attribute, $value, $fail) {
                    if (!$this->validaCnpj($value)) {
                        $fail('CNPJ inválido.');
                    }
                }
            ],
            'inscricao_estadual' => 'nullable|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'responsavel' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
            'configuracoes' => 'nullable',
        ];
    }

    public function mount()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Acesso não autorizado.');
        }
        $this->carregarEmpresas();
    }

    public function carregarEmpresas()
    {
        $this->empresas = EmpresasOperadora::orderBy('razao_social')->get();
    }

    public function resetarCampos()
    {
        $this->empresa_id = null;
        $this->razao_social = '';
        $this->nome_fantasia = '';
        $this->cnpj = '';
        $this->inscricao_estadual = '';
        $this->telefone = '';
        $this->email = '';
        $this->responsavel = '';
        $this->logo = null;
        $this->logo_atual = null;
        $this->configuracoes = null;
        $this->modoEdicao = false;
    }

    public function salvarEmpresa()
    {
        $dados = $this->validate();
        if ($this->logo) {
            $dados['logo'] = $this->logo->store('logos', 'public');
        } elseif ($this->logo_atual) {
            $dados['logo'] = $this->logo_atual;
        }
        if ($this->empresa_id) {
            $empresa = EmpresasOperadora::find($this->empresa_id);
            $empresa->update($dados);
        } else {
            EmpresasOperadora::create($dados);
        }
        $this->resetarCampos();
        $this->carregarEmpresas();
    }

    public function editarEmpresa($id)
    {
        $empresa = EmpresasOperadora::find($id);
        $this->empresa_id = $empresa->id;
        $this->razao_social = $empresa->razao_social;
        $this->nome_fantasia = $empresa->nome_fantasia;
        $this->cnpj = $empresa->cnpj;
        $this->inscricao_estadual = $empresa->inscricao_estadual;
        $this->telefone = $empresa->telefone;
        $this->email = $empresa->email;
        $this->responsavel = $empresa->responsavel;
        $this->logo_atual = $empresa->logo;
        $this->logo = null;
        $this->configuracoes = $empresa->configuracoes;
        $this->modoEdicao = true;
    }

    public function excluirEmpresa($id)
    {
        $empresa = EmpresasOperadora::find($id);
        if ($empresa->logo) {
            Storage::disk('public')->delete($empresa->logo);
        }
        $empresa->delete();
        $this->resetarCampos();
        $this->carregarEmpresas();
    }

    public function validaCnpj($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        if (strlen($cnpj) != 14) return false;
        if (preg_match('/(\d)\1{13}/', $cnpj)) return false;
        $t = 12;
        $d = 0;
        $c = 0;
        for ($i = 0; $i < 2; $i++) {
            $d = 0;
            $c = 0;
            for ($j = 0; $j < $t; $j++) {
                $d += $cnpj[$j] * ((($t + 1) - $j));
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cnpj[$t] != $d) return false;
            $t++;
        }
        return true;
    }

    public function render()
    {
        return view('livewire.empresas-operadoras-form');
    }
}
