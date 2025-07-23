<div class="container mt-4">
    <h2>Gerenciador de Usuários</h2>

    <form wire:submit.prevent="salvarUsuario" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="text" class="form-control mb-2" placeholder="Nome" wire:model="name" required>
            </div>
            <div class="col-md-3">
                <input type="email" class="form-control mb-2" placeholder="E-mail" wire:model="email" required>
            </div>
            <div class="col-md-3">
                <input type="password" class="form-control mb-2" placeholder="Senha" wire:model="password" @if($modoEdicao) placeholder="(deixe em branco para não alterar)" @endif>
            </div>
            <div class="col-md-2">
                <select class="form-control mb-2" wire:model="role" required>
                    <option value="admin">Administrador</option>
                    <option value="gerente">Gerente</option>
                    <option value="operador">Operador</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">
                    {{ $modoEdicao ? 'Atualizar' : 'Cadastrar' }}
                </button>
            </div>
        </div>
        @if($modoEdicao)
            <button type="button" class="btn btn-secondary btn-sm mt-2" wire:click="resetarCampos">Cancelar edição</button>
        @endif
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Nível</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->id }}</td>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ ucfirst($usuario->role) }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" wire:click="editarUsuario({{ $usuario->id }})">Editar</button>
                        <button class="btn btn-sm btn-danger" wire:click="excluirUsuario({{ $usuario->id }})" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
