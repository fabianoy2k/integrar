<?php

namespace App\Livewire;

use App\Models\ParametroExtrato;
use App\Models\Empresa;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class GerenciadorParametrosExtratos extends Component
{
    use WithPagination;

    // Propriedades para listagem
    public $search = '';
    public $filtroEmpresa = '';
    public $filtroTipo = '';
    public $filtroConferencia = '';

    // Propriedades para modal de criação/edição
    public $modalAberto = false;
    public $editando = false;
    public $parametroId = null;

    // Propriedades do formulário
    public $nome = '';
    public $tipo_periodo = 'ano_mes';
    public $ano = '';
    public $mes = '';
    public $data_inicial = '';
    public $data_final = '';
    public $conta_banco = '';
    public $saldo_inicial = '';
    public $saldo_final = '';
    public $eh_conferencia = false;
    public $empresa_id = '';
    public $ativo = true;
    public $observacoes = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filtroEmpresa' => ['except' => ''],
        'filtroTipo' => ['except' => ''],
        'filtroConferencia' => ['except' => ''],
    ];

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->ano = date('Y');
        $this->mes = date('n');
    }

    public function render()
    {
        $query = ParametroExtrato::with('empresa');

        // Aplicar filtros
        if ($this->search) {
            $query->where(function($q) {
                $q->where('nome', 'like', '%' . $this->search . '%')
                  ->orWhere('conta_banco', 'like', '%' . $this->search . '%')
                  ->orWhere('observacoes', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filtroEmpresa) {
            $query->where('empresa_id', $this->filtroEmpresa);
        }

        if ($this->filtroTipo) {
            $query->where('tipo_periodo', $this->filtroTipo);
        }

        if ($this->filtroConferencia !== '') {
            $query->where('eh_conferencia', $this->filtroConferencia);
        }

        $parametros = $query->orderBy('created_at', 'desc')->paginate(10);
        $empresas = Empresa::orderBy('nome')->get();

        return view('livewire.gerenciador-parametros-extratos', [
            'parametros' => $parametros,
            'empresas' => $empresas,
        ]);
    }

    public function abrirModal()
    {
        $this->resetForm();
        $this->modalAberto = true;
        $this->editando = false;
    }

    public function editarParametro($id)
    {
        $parametro = ParametroExtrato::find($id);
        if (!$parametro) {
            session()->flash('error', 'Parâmetro não encontrado.');
            return;
        }

        $this->parametroId = $parametro->id;
        $this->nome = $parametro->nome;
        $this->tipo_periodo = $parametro->tipo_periodo;
        $this->ano = $parametro->ano;
        $this->mes = $parametro->mes;
        $this->data_inicial = $parametro->data_inicial ? $parametro->data_inicial->format('Y-m-d') : '';
        $this->data_final = $parametro->data_final ? $parametro->data_final->format('Y-m-d') : '';
        $this->conta_banco = $parametro->conta_banco;
        $this->saldo_inicial = $parametro->saldo_inicial;
        $this->saldo_final = $parametro->saldo_final;
        $this->eh_conferencia = $parametro->eh_conferencia;
        $this->empresa_id = $parametro->empresa_id;
        $this->ativo = $parametro->ativo;
        $this->observacoes = $parametro->observacoes;

        $this->modalAberto = true;
        $this->editando = true;
    }

    public function salvarParametro()
    {
        $rules = ParametroExtrato::rules();
        $messages = ParametroExtrato::messages();

        // Validações condicionais
        if ($this->tipo_periodo === 'ano_mes') {
            $rules['ano'] = 'required|integer|min:1900|max:2100';
            $rules['mes'] = 'required|integer|min:1|max:12';
            $rules['data_inicial'] = 'nullable';
            $rules['data_final'] = 'nullable';
        } else {
            $rules['data_inicial'] = 'required|date';
            $rules['data_final'] = 'required|date|after_or_equal:data_inicial';
            $rules['ano'] = 'nullable';
            $rules['mes'] = 'nullable';
        }

        // Validações para conferência
        if ($this->eh_conferencia) {
            $rules['conta_banco'] = 'required|string|max:255';
            $rules['saldo_inicial'] = 'required|numeric|min:0';
            $rules['saldo_final'] = 'required|numeric|min:0';
        }

        $this->validate($rules, $messages);

        try {
            $dados = [
                'nome' => $this->nome,
                'tipo_periodo' => $this->tipo_periodo,
                'ano' => $this->tipo_periodo === 'ano_mes' ? $this->ano : null,
                'mes' => $this->tipo_periodo === 'ano_mes' ? $this->mes : null,
                'data_inicial' => $this->tipo_periodo === 'data_inicial_final' ? $this->data_inicial : null,
                'data_final' => $this->tipo_periodo === 'data_inicial_final' ? $this->data_final : null,
                'conta_banco' => $this->conta_banco,
                'saldo_inicial' => $this->saldo_inicial,
                'saldo_final' => $this->saldo_final,
                'eh_conferencia' => $this->eh_conferencia,
                'empresa_id' => $this->empresa_id ?: null,
                'ativo' => $this->ativo,
                'observacoes' => $this->observacoes,
            ];

            if ($this->editando) {
                $parametro = ParametroExtrato::find($this->parametroId);
                $parametro->update($dados);
                session()->flash('message', 'Parâmetro atualizado com sucesso!');
                
                Log::info("Parâmetro de extrato atualizado", [
                    'parametro_id' => $parametro->id,
                    'nome' => $parametro->nome,
                    'tipo_periodo' => $parametro->tipo_periodo,
                    'eh_conferencia' => $parametro->eh_conferencia,
                    'empresa_id' => $parametro->empresa_id,
                ]);
            } else {
                $parametro = ParametroExtrato::create($dados);
                session()->flash('message', 'Parâmetro criado com sucesso!');
                
                Log::info("Parâmetro de extrato criado", [
                    'parametro_id' => $parametro->id,
                    'nome' => $parametro->nome,
                    'tipo_periodo' => $parametro->tipo_periodo,
                    'eh_conferencia' => $parametro->eh_conferencia,
                    'empresa_id' => $parametro->empresa_id,
                ]);
            }

            $this->fecharModal();
            $this->dispatch('refresh');

        } catch (\Exception $e) {
            Log::error("Erro ao salvar parâmetro de extrato", [
                'erro' => $e->getMessage(),
                'dados' => $dados ?? []
            ]);
            
            session()->flash('error', 'Erro ao salvar parâmetro: ' . $e->getMessage());
        }
    }

    public function excluirParametro($id)
    {
        try {
            $parametro = ParametroExtrato::find($id);
            if (!$parametro) {
                session()->flash('error', 'Parâmetro não encontrado.');
                return;
            }

            Log::info("Parâmetro de extrato excluído", [
                'parametro_id' => $parametro->id,
                'nome' => $parametro->nome,
                'tipo_periodo' => $parametro->tipo_periodo,
            ]);

            $parametro->delete();
            session()->flash('message', 'Parâmetro excluído com sucesso!');
            $this->dispatch('refresh');

        } catch (\Exception $e) {
            Log::error("Erro ao excluir parâmetro de extrato", [
                'parametro_id' => $id,
                'erro' => $e->getMessage()
            ]);
            
            session()->flash('error', 'Erro ao excluir parâmetro: ' . $e->getMessage());
        }
    }

    public function toggleAtivo($id)
    {
        try {
            $parametro = ParametroExtrato::find($id);
            if (!$parametro) {
                session()->flash('error', 'Parâmetro não encontrado.');
                return;
            }

            $parametro->ativo = !$parametro->ativo;
            $parametro->save();

            $status = $parametro->ativo ? 'ativado' : 'desativado';
            session()->flash('message', "Parâmetro {$status} com sucesso!");

        } catch (\Exception $e) {
            Log::error("Erro ao alterar status do parâmetro", [
                'parametro_id' => $id,
                'erro' => $e->getMessage()
            ]);
            
            session()->flash('error', 'Erro ao alterar status: ' . $e->getMessage());
        }
    }

    public function fecharModal()
    {
        $this->modalAberto = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->parametroId = null;
        $this->nome = '';
        $this->tipo_periodo = 'ano_mes';
        $this->ano = date('Y');
        $this->mes = date('n');
        $this->data_inicial = '';
        $this->data_final = '';
        $this->conta_banco = '';
        $this->saldo_inicial = '';
        $this->saldo_final = '';
        $this->eh_conferencia = false;
        $this->empresa_id = '';
        $this->ativo = true;
        $this->observacoes = '';
        $this->editando = false;
    }

    public function limparFiltros()
    {
        $this->search = '';
        $this->filtroEmpresa = '';
        $this->filtroTipo = '';
        $this->filtroConferencia = '';
        $this->resetPage();
    }
}
