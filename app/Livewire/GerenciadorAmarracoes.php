<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Amarracao;

class GerenciadorAmarracoes extends Component
{
    public $amarracoes;
    public $editId = null;
    public $editData = [];

    public function mount()
    {
        $this->carregarAmarracoes();
    }

    public function carregarAmarracoes()
    {
        $this->amarracoes = Amarracao::orderBy('id', 'desc')->get();
    }

    public function editar($id)
    {
        $this->editId = $id;
        $amarracao = Amarracao::find($id);
        $this->editData = $amarracao ? $amarracao->toArray() : [];
    }

    public function salvar()
    {
        if ($this->editId) {
            $amarracao = Amarracao::find($this->editId);
            if ($amarracao) {
                $amarracao->update($this->editData);
            }
            $this->editId = null;
            $this->editData = [];
            $this->carregarAmarracoes();
        }
    }

    public function cancelar()
    {
        $this->editId = null;
        $this->editData = [];
    }

    public function excluir($id)
    {
        Amarracao::destroy($id);
        $this->carregarAmarracoes();
    }

    public function render()
    {
        return view('livewire.gerenciador-amarracoes');
    }
}
