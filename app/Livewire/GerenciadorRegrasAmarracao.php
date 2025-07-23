<?php

namespace App\Livewire;

use App\Models\RegraAmarracaoDescricao;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class GerenciadorRegrasAmarracao extends Component
{
    use WithPagination;

    #[Rule('required|string|max:255')]
    public $palavra_chave = '';

    #[Rule('required|in:contains,starts_with,ends_with,exact')]
    public $tipo_busca = 'contains';

    #[Rule('nullable|string|max:255')]
    public $conta_debito = '';

    #[Rule('nullable|string|max:255')]
    public $conta_credito = '';

    #[Rule('nullable|string|max:255')]
    public $centro_custo = '';

    #[Rule('nullable|string')]
    public $descricao = '';

    public $prioridade = 0;
    public $ativo = true;
    public $editando = false;
    public $regraId = null;

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->palavra_chave = '';
        $this->tipo_busca = 'contains';
        $this->conta_debito = '';
        $this->conta_credito = '';
        $this->centro_custo = '';
        $this->descricao = '';
        $this->prioridade = 0;
        $this->ativo = true;
        $this->editando = false;
        $this->regraId = null;
    }

    public function salvar()
    {
        $this->validate();

        $empresaId = auth()->user()?->empresa_id ?? 1;

        if ($this->editando) {
            $regra = RegraAmarracaoDescricao::find($this->regraId);
            if ($regra) {
                $regra->update([
                    'palavra_chave' => $this->palavra_chave,
                    'tipo_busca' => $this->tipo_busca,
                    'conta_debito' => $this->conta_debito,
                    'conta_credito' => $this->conta_credito,
                    'centro_custo' => $this->centro_custo,
                    'descricao' => $this->descricao,
                    'prioridade' => $this->prioridade,
                    'ativo' => $this->ativo,
                ]);
                session()->flash('message', 'Regra atualizada com sucesso!');
            }
        } else {
            RegraAmarracaoDescricao::create([
                'empresa_id' => $empresaId,
                'palavra_chave' => $this->palavra_chave,
                'tipo_busca' => $this->tipo_busca,
                'conta_debito' => $this->conta_debito,
                'conta_credito' => $this->conta_credito,
                'centro_custo' => $this->centro_custo,
                'descricao' => $this->descricao,
                'prioridade' => $this->prioridade,
                'ativo' => $this->ativo,
            ]);
            session()->flash('message', 'Regra criada com sucesso!');
        }

        $this->resetForm();
    }

    public function editar($id)
    {
        $regra = RegraAmarracaoDescricao::find($id);
        if ($regra) {
            $this->regraId = $regra->id;
            $this->palavra_chave = $regra->palavra_chave;
            $this->tipo_busca = $regra->tipo_busca;
            $this->conta_debito = $regra->conta_debito;
            $this->conta_credito = $regra->conta_credito;
            $this->centro_custo = $regra->centro_custo;
            $this->descricao = $regra->descricao;
            $this->prioridade = $regra->prioridade;
            $this->ativo = $regra->ativo;
            $this->editando = true;
        }
    }

    public function cancelar()
    {
        $this->resetForm();
    }

    public function excluir($id)
    {
        $regra = RegraAmarracaoDescricao::find($id);
        if ($regra) {
            $regra->delete();
            session()->flash('message', 'Regra excluída com sucesso!');
        }
    }

    public function toggleAtivo($id)
    {
        $regra = RegraAmarracaoDescricao::find($id);
        if ($regra) {
            $regra->update(['ativo' => !$regra->ativo]);
        }
    }

    public function render()
    {
        $empresaId = auth()->user()?->empresa_id ?? 1;
        $regras = RegraAmarracaoDescricao::where('empresa_id', $empresaId)
            ->orderBy('prioridade', 'desc')
            ->orderBy('palavra_chave')
            ->paginate(10);

        return view('livewire.gerenciador-regras-amarracao', [
            'regras' => $regras
        ]);
    }
} 