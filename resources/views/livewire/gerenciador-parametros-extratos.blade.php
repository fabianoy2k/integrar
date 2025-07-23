<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Parâmetros de Extratos Bancários</h2>
        <button wire:click="abrirModal" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
            Novo Parâmetro
        </button>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Nome, conta ou observações..." 
                       class="w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Empresa</label>
                <select wire:model.live="filtroEmpresa" class="w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Todas</option>
                    @foreach($empresas as $empresa)
                        <option value="{{ $empresa->id }}">{{ $empresa->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                <select wire:model.live="filtroTipo" class="w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Todos</option>
                    <option value="ano_mes">Ano/Mês</option>
                    <option value="data_inicial_final">Data Inicial/Final</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Conferência</label>
                <select wire:model.live="filtroConferencia" class="w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Todos</option>
                    <option value="1">Sim</option>
                    <option value="0">Não</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tabela -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Período</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Empresa</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Conferência</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($parametros as $parametro)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $parametro->nome }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $parametro->periodo_formatado }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $parametro->empresa ? $parametro->empresa->nome : '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($parametro->eh_conferencia)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Sim
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Não
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="toggleAtivo({{ $parametro->id }})" 
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $parametro->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $parametro->ativo ? 'Ativo' : 'Inativo' }}
                            </button>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <button wire:click="editarParametro({{ $parametro->id }})" class="text-blue-600 hover:text-blue-900">
                                    Editar
                                </button>
                                <button wire:click="excluirParametro({{ $parametro->id }})" 
                                        onclick="return confirm('Tem certeza?')"
                                        class="text-red-600 hover:text-red-900">
                                    Excluir
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Nenhum parâmetro encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $parametros->links() }}
        </div>
    </div>

    <!-- Modal -->
    @if($modalAberto)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <h3 class="text-lg font-semibold mb-4">{{ $editando ? 'Editar' : 'Novo' }} Parâmetro</h3>
                
                <form wire:submit.prevent="salvarParametro">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                            <input type="text" wire:model="nome" class="w-full rounded-md border-gray-300 shadow-sm">
                            @error('nome') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Período *</label>
                            <select wire:model="tipo_periodo" class="w-full rounded-md border-gray-300 shadow-sm">
                                <option value="ano_mes">Ano/Mês</option>
                                <option value="data_inicial_final">Data Inicial/Final</option>
                            </select>
                        </div>

                        @if($tipo_periodo === 'ano_mes')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ano *</label>
                                <input type="number" wire:model="ano" min="1900" max="2100" class="w-full rounded-md border-gray-300 shadow-sm">
                                @error('ano') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Mês *</label>
                                <select wire:model="mes" class="w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Selecione...</option>
                                    <option value="1">Janeiro</option>
                                    <option value="2">Fevereiro</option>
                                    <option value="3">Março</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Maio</option>
                                    <option value="6">Junho</option>
                                    <option value="7">Julho</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Setembro</option>
                                    <option value="10">Outubro</option>
                                    <option value="11">Novembro</option>
                                    <option value="12">Dezembro</option>
                                </select>
                                @error('mes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        @if($tipo_periodo === 'data_inicial_final')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Data Inicial *</label>
                                <input type="date" wire:model="data_inicial" class="w-full rounded-md border-gray-300 shadow-sm">
                                @error('data_inicial') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Data Final *</label>
                                <input type="date" wire:model="data_final" class="w-full rounded-md border-gray-300 shadow-sm">
                                @error('data_final') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Empresa</label>
                            <select wire:model="empresa_id" class="w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Selecione...</option>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}">{{ $empresa->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="eh_conferencia" id="eh_conferencia" class="rounded border-gray-300">
                            <label for="eh_conferencia" class="ml-2 text-sm font-medium text-gray-700">Conferência de extrato</label>
                        </div>

                        @if($eh_conferencia)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Conta Bancária *</label>
                                <input type="text" wire:model="conta_banco" class="w-full rounded-md border-gray-300 shadow-sm">
                                @error('conta_banco') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Saldo Inicial *</label>
                                <input type="number" wire:model="saldo_inicial" step="0.01" min="0" class="w-full rounded-md border-gray-300 shadow-sm">
                                @error('saldo_inicial') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Saldo Final *</label>
                                <input type="number" wire:model="saldo_final" step="0.01" min="0" class="w-full rounded-md border-gray-300 shadow-sm">
                                @error('saldo_final') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="ativo" id="ativo" class="rounded border-gray-300">
                            <label for="ativo" class="ml-2 text-sm font-medium text-gray-700">Ativo</label>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                            <textarea wire:model="observacoes" rows="3" class="w-full rounded-md border-gray-300 shadow-sm resize-none"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 mt-6">
                        <button type="button" wire:click="fecharModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            {{ $editando ? 'Atualizar' : 'Criar' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
