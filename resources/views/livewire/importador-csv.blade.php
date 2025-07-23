<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Importador de CSV</h2>
        
        @if($mensagem)
            <div class="mb-4 p-4 rounded-lg {{ str_contains($mensagem, 'Erro') ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                {{ $mensagem }}
            </div>
        @endif

        @if($processando)
            <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-blue-800">Processando importação...</h3>
                    <span class="text-sm text-blue-600">{{ $progresso }}%</span>
                </div>
                
                <div class="w-full bg-blue-200 rounded-full h-3 mb-3">
                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-300 ease-out" 
                         style="width: {{ $progresso }}%"></div>
                </div>
                
                <div class="text-sm text-blue-700">
                    <p>Linha atual: {{ $linhaAtual }} de {{ $totalLinhas }}</p>
                    <p>Registros processados: {{ $totalImportado }}</p>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="importar" class="space-y-4">
            <div>
                <label for="arquivo" class="block text-sm font-medium text-gray-700 mb-2">
                    Selecione o arquivo CSV
                </label>
                <input 
                    type="file" 
                    id="arquivo"
                    wire:model="arquivo"
                    accept=".csv,.txt"
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                >
                @error('arquivo') 
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-semibold text-gray-800 mb-2">Formato esperado do CSV:</h3>
                <p class="text-sm text-gray-600 mb-2">O arquivo deve conter as seguintes colunas separadas por ponto e vírgula (;):</p>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li><strong>Data do Lançamento:</strong> Formato DD/MM/AAAA</li>
                    <li><strong>Usuário:</strong> Nome do usuário que fez o lançamento</li>
                    <li><strong>Conta Débito:</strong> Código da conta de débito</li>
                    <li><strong>Conta Crédito:</strong> Código da conta de crédito</li>
                    <li><strong>Valor do Lançamento:</strong> Valor do lançamento (usar vírgula como separador decimal)</li>
                    <li><strong>Histórico (Complemento):</strong> Descrição do lançamento</li>
                    <li><strong>Código da Filial/Matriz:</strong> Código da filial</li>
                    <li><strong>Nome da Empresa:</strong> Nome da empresa/terceiro</li>
                    <li><strong>Número da Nota:</strong> Número da nota fiscal</li>
                </ul>
            </div>

            @if($totalImportado > 0 && $importacaoId)
                <a href="{{ route('tabela', ['importacao' => $importacaoId]) }}"
                   class="w-full block text-center bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 mt-4">
                    Abrir Importação
                </a>
            @else
                <button 
                    type="submit" 
                    wire:loading.attr="disabled"
                    wire:target="importar"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span wire:loading.remove wire:target="importar">Importar Arquivo</span>
                    <span wire:loading wire:target="importar">Processando...</span>
                </button>
            @endif
        </form>

        @if($totalImportado > 0)
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <h3 class="font-semibold text-blue-800">Resumo da Importação</h3>
                <p class="text-blue-700">{{ $totalImportado }} lançamentos importados com sucesso!</p>
                @if($importacaoId)
                    <p class="text-blue-700 mt-2">ID da Importação: {{ $importacaoId }}</p>
                @endif
            </div>
        @endif
    </div>
</div>
