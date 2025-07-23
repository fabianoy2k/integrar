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
        'email' => 'required|email|max:255',
        'password' => 'nullable|min:6',
        'role' => 'required|in:admin,gerente,operador',
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
        $dados = $this->validate();
        if ($this->usuario_id) {
            $usuario = User::find($this->usuario_id);
            $usuario->name = $this->name;
            $usuario->email = $this->email;
            $usuario->role = $this->role;
            if ($this->password) {
                $usuario->password = Hash::make($this->password);
            }
            $usuario->save();
        } else {
            $dados['password'] = Hash::make($this->password);
            User::create($dados);
        }
        $this->resetarCampos();
        $this->carregarUsuarios();
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
        User::find($id)->delete();
        $this->resetarCampos();
        $this->carregarUsuarios();
    }

    public function render()
    {
        return view('livewire.gerenciador-usuarios');
    }
}
