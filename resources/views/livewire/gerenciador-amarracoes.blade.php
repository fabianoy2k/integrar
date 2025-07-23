<div class="max-w-5xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Amarrações</h2>
    <div class="overflow-x-auto rounded-lg shadow">
        <table class="min-w-full bg-white divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-xs font-semibold text-gray-600">ID</th>
                    <th class="px-4 py-2 text-xs font-semibold text-gray-600">Terceiro</th>
                    <th class="px-4 py-2 text-xs font-semibold text-gray-600">Detalhes da Operação</th>
                    <th class="px-4 py-2 text-xs font-semibold text-gray-600">Débito</th>
                    <th class="px-4 py-2 text-xs font-semibold text-gray-600">Crédito</th>
                    <th class="px-4 py-2 text-xs font-semibold text-gray-600">Código Sistema</th>
                    <th class="px-4 py-2 text-xs font-semibold text-gray-600">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($this->amarracoes as $amarracao)
                    <tr class="hover:bg-blue-50 transition">
                        <td class="px-4 py-2 text-center text-sm text-gray-700">{{ $amarracao->id }}</td>
                        @if($editId === $amarracao->id)
                            <td class="px-4 py-2"><input type="text" wire:model.defer="editData.terceiro" class="border rounded w-full"></td>
                            <td class="px-4 py-2"><input type="text" wire:model.defer="editData.detalhes_operacao" class="border rounded w-full" placeholder="tag1,tag2,tag3"></td>
                            <td class="px-4 py-2"><input type="text" wire:model.defer="editData.conta_debito" class="border rounded w-full"></td>
                            <td class="px-4 py-2"><input type="text" wire:model.defer="editData.conta_credito" class="border rounded w-full"></td>
                            <td class="px-4 py-2"><input type="text" wire:model.defer="editData.codigo_sistema_empresa" class="border rounded w-full" placeholder="Código do sistema"></td>
                            <td class="px-4 py-2 text-center">
                                <button wire:click="salvar" class="px-2 py-1 bg-green-600 text-white rounded flex items-center gap-1"><svg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7' /></svg>Salvar</button>
                                <button wire:click="cancelar" class="px-2 py-1 bg-gray-400 text-white rounded ml-2 flex items-center gap-1"><svg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12' /></svg>Cancelar</button>
                            </td>
                        @else
                            <td class="px-4 py-2 text-sm text-gray-700">
                                @if(empty($amarracao->terceiro))
                                    <span class="text-xs text-gray-400 italic">Sem Terceiro</span>
                                @else
                                    {{ $amarracao->terceiro }}
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <div class="flex flex-wrap gap-1">
                                    @foreach(explode(',', $amarracao->detalhes_operacao) as $tag)
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">{{ trim($tag) }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-2 text-sm font-mono text-green-700">{{ $amarracao->conta_debito }}</td>
                            <td class="px-4 py-2 text-sm font-mono text-red-700">{{ $amarracao->conta_credito }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">
                                @if(empty($amarracao->codigo_sistema_empresa))
                                    <span class="text-xs text-gray-400 italic">Não definido</span>
                                @else
                                    {{ $amarracao->codigo_sistema_empresa }}
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center">
                                <button wire:click="editar({{ $amarracao->id }})" class="p-1 bg-blue-500 hover:bg-blue-700 text-white rounded-full" title="Editar">
                                    <svg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15.232 5.232l3.536 3.536M9 13l6-6m2 2l-6 6m2 2l-6 6m2 2l-6 6' /></svg>
                                </button>
                                <button wire:click="excluir({{ $amarracao->id }})" class="p-1 bg-red-500 hover:bg-red-700 text-white rounded-full ml-2" title="Excluir">
                                    <svg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12' /></svg>
                                </button>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
