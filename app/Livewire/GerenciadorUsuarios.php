<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class GerenciadorUsuarios extends Component
{
    public $usuarios;
    public $usuario_id;
    public $name;
    public $email;
    public $password;
    public $role = 'operador';
    public $modoEdicao = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . null,
        'password' => 'nullable|min:6',
        'role' => 'required|in:admin,gerente,operador',
    ];

    protected $messages = [
        'name.required' => 'O nome é obrigatório.',
        'name.max' => 'O nome não pode ter mais de 255 caracteres.',
        'email.required' => 'O e-mail é obrigatório.',
        'email.email' => 'Digite um e-mail válido.',
        'email.unique' => 'Este e-mail já está em uso.',
        'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
        'role.required' => 'O nível de acesso é obrigatório.',
        'role.in' => 'Nível de acesso inválido.',
    ];

    public function mount()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role, ['admin', 'gerente'])) {
            abort(403, 'Acesso não autorizado.');
        }
        $this->carregarUsuarios();
    }

    public function carregarUsuarios()
    {
        $this->usuarios = User::all();
    }

    public function resetarCampos()
    {
        $this->usuario_id = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = 'operador';
        $this->modoEdicao = false;
    }

    public function salvarUsuario()
    {
        // Ajustar regra de validação para edição
        if ($this->usuario_id) {
            $this->rules['email'] = 'required|email|max:255|unique:users,email,' . $this->usuario_id;
        }
        
        $dados = $this->validate();
        
        try {
            if ($this->usuario_id) {
                $usuario = User::find($this->usuario_id);
                $usuario->name = $this->name;
                $usuario->email = $this->email;
                $usuario->role = $this->role;
                if ($this->password) {
                    $usuario->password = Hash::make($this->password);
                }
                $usuario->save();
                session()->flash('message', 'Usuário atualizado com sucesso!');
            } else {
                $dados['password'] = Hash::make($this->password);
                User::create($dados);
                session()->flash('message', 'Usuário cadastrado com sucesso!');
            }
            
            $this->resetarCampos();
            $this->carregarUsuarios();
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao salvar usuário: ' . $e->getMessage());
        }
    }

    public function editarUsuario($id)
    {
        $usuario = User::find($id);
        $this->usuario_id = $usuario->id;
        $this->name = $usuario->name;
        $this->email = $usuario->email;
        $this->role = $usuario->role;
        $this->password = '';
        $this->modoEdicao = true;
    }



    public function excluirUsuario($id)
    {
        try {
            $usuario = User::find($id);
            
            // Não permitir excluir o próprio usuário
            if ($usuario->id === Auth::id()) {
                session()->flash('error', 'Você não pode excluir seu próprio usuário!');
                return;
            }
            
            $usuario->delete();
            session()->flash('message', 'Usuário excluído com sucesso!');
            $this->resetarCampos();
            $this->carregarUsuarios();
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao excluir usuário: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.gerenciador-usuarios');
    }
}
