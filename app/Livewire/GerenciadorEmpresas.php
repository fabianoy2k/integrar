<?php

namespace App\Livewire;

use App\Models\Empresa;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class GerenciadorEmpresas extends Component
{
    use WithPagination, WithFileUploads;

    public $nome = '';
    public $cnpj = '';
    public $codigo_sistema = '';
    public $codigo_conta_banco = '';
    public $empresa_id = null;
    public $modo_edicao = false;
    public $busca = '';
    public $confirmando_exclusao = false;
    public $empresa_para_excluir = null;

    protected $rules = [
        'nome' => 'required|min:3|max:255',
        'cnpj' => 'required|min:14|max:18|unique:empresas,cnpj',
        'codigo_sistema' => 'nullable|max:50',
        'codigo_conta_banco' => 'nullable|max:50',
    ];

    public function updatedBusca()
    {
        $this->resetPage();
    }

    public function salvar()
    {
        if ($this->modo_edicao) {
            $this->rules['cnpj'] = 'required|min:14|max:18|unique:empresas,cnpj,' . $this->empresa_id;
        }

        $this->validate();

        if ($this->modo_edicao) {
            $empresa = Empresa::find($this->empresa_id);
            $empresa->update([
                'nome' => $this->nome,
                'cnpj' => $this->cnpj,
                'codigo_sistema' => $this->codigo_sistema,
                'codigo_conta_banco' => $this->codigo_conta_banco,
            ]);
            session()->flash('message', 'Empresa atualizada com sucesso!');
        } else {
            Empresa::create([
                'nome' => $this->nome,
                'cnpj' => $this->cnpj,
                'codigo_sistema' => $this->codigo_sistema,
                'codigo_conta_banco' => $this->codigo_conta_banco,
            ]);
            session()->flash('message', 'Empresa criada com sucesso!');
        }

        $this->limparFormulario();
    }

    public function editar($id)
    {
        $empresa = Empresa::find($id);
        $this->empresa_id = $empresa->id;
        $this->nome = $empresa->nome;
        $this->cnpj = $empresa->cnpj;
        $this->codigo_sistema = $empresa->codigo_sistema;
        $this->codigo_conta_banco = $empresa->codigo_conta_banco;
        $this->modo_edicao = true;
    }

    public function cancelarEdicao()
    {
        $this->limparFormulario();
    }

    public function confirmarExclusao($id)
    {
        $this->empresa_para_excluir = $id;
        $this->confirmando_exclusao = true;
    }

    public function excluir()
    {
        $empresa = Empresa::find($this->empresa_para_excluir);
        
        // Verificar se há importações ou lançamentos associados
        if ($empresa->importacoes()->count() > 0 || $empresa->lancamentos()->count() > 0) {
            session()->flash('error', 'Não é possível excluir uma empresa que possui importações ou lançamentos associados.');
            $this->confirmando_exclusao = false;
            $this->empresa_para_excluir = null;
            return;
        }

        $empresa->delete();
        session()->flash('message', 'Empresa excluída com sucesso!');
        $this->confirmando_exclusao = false;
        $this->empresa_para_excluir = null;
    }

    public function cancelarExclusao()
    {
        $this->confirmando_exclusao = false;
        $this->empresa_para_excluir = null;
    }

    private function limparFormulario()
    {
        $this->nome = '';
        $this->cnpj = '';
        $this->codigo_sistema = '';
        $this->codigo_conta_banco = '';
        $this->empresa_id = null;
        $this->modo_edicao = false;
        $this->resetValidation();
    }

    public function render()
    {
        $empresas = Empresa::query()
            ->when($this->busca, function ($query) {
                $query->where(function ($q) {
                    $q->where('nome', 'like', '%' . $this->busca . '%')
                      ->orWhere('cnpj', 'like', '%' . $this->busca . '%')
                      ->orWhere('codigo_sistema', 'like', '%' . $this->busca . '%');
                });
            })
            ->orderBy('nome')
            ->paginate(10);

        return view('livewire.gerenciador-empresas', [
            'empresas' => $empresas
        ]);
    }
}
