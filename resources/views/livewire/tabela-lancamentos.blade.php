<div class="w-full px-2 md:px-6 py-4">
    <div class="flex justify-end mb-2">
        <label for="perPage" class="mr-2 text-sm text-gray-700">Exibir:</label>
        <select id="perPage" wire:model.lazy="perPage" class="border-gray-300 rounded-md text-sm">
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="200">200</option>
        </select>
        <span class="ml-2 text-sm text-gray-700">por página</span>
    </div>
            <div class="bg-white rounded-lg shadow-md">
            <!-- Mensagens de Feedback -->
            @if (session()->has('message'))
                <div class="p-4 bg-green-100 border-b border-green-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-800">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="p-4 bg-red-100 border-b border-red-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Cabeçalho -->
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800">Lançamentos Contábeis</h2>
                <p class="text-gray-600 mt-1">Gerencie e edite os lançamentos importados</p>
            </div>

        <!-- Filtros globais acima da tabela -->
        <div class="flex flex-wrap gap-3 items-end mb-4">
            <div class="mr-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">Importação</label>
                <select wire:model.live="filtroImportacao" class="h-10 px-4 rounded-lg border border-gray-300 bg-white text-xs placeholder-gray-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all w-full shadow outline-none">
                        <option value="">Todas</option>
                        @foreach($importacoes as $importacao)
                            <option value="{{ $importacao->id }}">{{ $importacao->id }} - {{ $importacao->nome_arquivo }}</option>
                        @endforeach
                    </select>
                </div>
            <div class="mr-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="filtroConferido" class="h-10 px-4 rounded-lg border border-gray-300 bg-white text-xs placeholder-gray-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all w-full shadow outline-none">
                        <option value="">Todos</option>
                        <option value="conferidos">Conferidos</option>
                        <option value="nao_conferidos">Não Conferidos</option>
                    </select>
                </div>
            <div class="mr-2">
                <label class="block text-xs font-medium text-gray-700 mb-1">Déb/Créd</label>
                <input type="text" wire:model.live.debounce.300ms="filtroContaAmbas" placeholder="Débito ou Crédito..." class="h-10 px-4 rounded-lg border border-gray-300 bg-white text-xs placeholder-gray-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all w-full shadow outline-none" />
            </div>
            <div class="mr-2">
                <button wire:click="limparFiltros" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white text-xs shadow transition-all">Limpar</button>
            </div>
            <div>
                <button wire:click="abrirModalNovoLancamento" class="px-5 py-2 bg-green-600 hover:bg-green-700 rounded-lg text-white text-xs shadow transition-all flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Novo Lançamento
                </button>
            </div>
        </div>

        <!-- Ações em Massa -->
        @if(!empty($selecionados))
            <div class="p-4 bg-blue-50 border-b border-blue-200">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-blue-800">
                        {{ count($selecionados) }} item(s) selecionado(s)
                    </span>
                    <div class="space-x-2">
                        <button wire:click="edicaoMassa = true" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                            Editar em Massa
                        </button>

                        <button wire:click="abrirModalExclusao" class="px-3 py-1 bg-red-600 text-white text-sm rounded hover:bg-red-700">
                            Excluir Selecionados
                        </button>
                        <button wire:click="deselecionarTodos" class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                            Deselecionar Todos
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal de Confirmação de Exclusão -->
        @if($confirmarExclusao)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 w-full max-w-md">
                    <h3 class="text-lg font-semibold mb-4 text-red-600">Confirmar Exclusão</h3>
                    <p class="mb-4 text-gray-700">
                        Tem certeza que deseja excluir <strong>{{ count($selecionados) }}</strong> lançamento(s) selecionado(s)?
                    </p>
                    <p class="mb-4 text-sm text-red-600">
                        ⚠️ Esta ação não pode ser desfeita!
                    </p>
                    <div class="flex justify-end space-x-2">
                        <button wire:click="cancelarExclusao" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Cancelar
                        </button>
                        <button wire:click="excluirLancamentos" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Confirmar Exclusão
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal de Edição em Massa -->
        @if($edicaoMassa)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 w-full max-w-md">
                    <h3 class="text-lg font-semibold mb-4">Edição em Massa</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Data</label>
                            <input type="date" wire:model="dataMassa" class="w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Conta Débito</label>
                            <input type="text" wire:model="contaDebitoMassa" class="w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Conta Crédito</label>
                            <input type="text" wire:model="contaCreditoMassa" class="w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Terceiro</label>
                            <input type="text" wire:model="terceiroMassa" class="w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Histórico</label>
                            <textarea wire:model="historicoMassa" rows="3" class="w-full rounded-md border-gray-300 shadow-sm resize-none" placeholder="Digite o histórico"></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 mt-6">
                        <button wire:click="edicaoMassa = false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Cancelar
                        </button>
                        <button wire:click="aplicarEdicaoMassa" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Aplicar
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal de Novo Lançamento -->
        @if($modalNovoLancamento)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                    <h3 class="text-lg font-semibold mb-4">Novo Lançamento</h3>
                    <form wire:submit.prevent="salvarNovoLancamento">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Importação *</label>
                                <select wire:model="novoLancamento.importacao_id" wire:change="carregarDadosImportacao" class="w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="">Selecione uma importação</option>
                                    @foreach($importacoes as $importacao)
                                        <option value="{{ $importacao->id }}">{{ $importacao->id }} - {{ $importacao->nome_arquivo }} @if($importacao->empresa) ({{ $importacao->empresa->nome }}) @endif</option>
                                    @endforeach
                                </select>
                                @error('novoLancamento.importacao_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Data *</label>
                                <input type="date" wire:model="novoLancamento.data" class="w-full rounded-md border-gray-300 shadow-sm">
                                @error('novoLancamento.data') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Conta Débito *</label>
                                <input type="text" wire:model="novoLancamento.conta_debito" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="Ex: 1.1.1.01.001">
                                @error('novoLancamento.conta_debito') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Conta Crédito *</label>
                                <input type="text" wire:model="novoLancamento.conta_credito" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="Ex: 2.1.1.01.001">
                                @error('novoLancamento.conta_credito') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Valor *</label>
                                <input type="number" wire:model="novoLancamento.valor" step="0.01" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="0,00">
                                @error('novoLancamento.valor') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Terceiro</label>
                                <input type="text" wire:model="novoLancamento.nome_empresa" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="Nome do terceiro">
                                @error('novoLancamento.nome_empresa') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Histórico *</label>
                                <textarea wire:model="novoLancamento.historico" rows="3" class="w-full rounded-md border-gray-300 shadow-sm resize-none" placeholder="Digite o histórico do lançamento"></textarea>
                                @error('novoLancamento.historico') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Código Filial/Matriz</label>
                                <input type="text" wire:model="novoLancamento.codigo_filial_matriz" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="Código da filial ou matriz">
                                <p class="text-xs text-gray-500 mt-1">Preenchido automaticamente ao selecionar importação</p>
                                @error('novoLancamento.codigo_filial_matriz') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Arquivo Origem</label>
                                <input type="text" wire:model="novoLancamento.arquivo_origem" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="Ex: Lançamento Manual - Ajuste, NF 123456, Sistema ERP">
                                <p class="text-xs text-gray-500 mt-1">Opcional: Documento, sistema ou referência de origem</p>
                                @error('novoLancamento.arquivo_origem') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="flex justify-end space-x-2 mt-6">
                            <button type="button" wire:click="fecharModalNovoLancamento" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                Cancelar
                            </button>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Salvar Lançamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Modal de Edição de Lançamento -->
        @if($modalEditarLancamento)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                    <h3 class="text-lg font-semibold mb-4">Editar Lançamento</h3>
                    <form wire:submit.prevent="salvarEdicaoLancamento">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Data *</label>
                                <input type="date" wire:model="dadosEdicao.data" class="w-full rounded-md border-gray-300 shadow-sm">
                                @error('dadosEdicao.data') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Valor *</label>
                                <input type="number" wire:model="dadosEdicao.valor" step="0.01" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="0,00">
                                @error('dadosEdicao.valor') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Conta Débito *</label>
                                <input type="text" wire:model="dadosEdicao.conta_debito" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="Ex: 1.1.1.01.001">
                                @error('dadosEdicao.conta_debito') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Conta Crédito *</label>
                                <input type="text" wire:model="dadosEdicao.conta_credito" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="Ex: 2.1.1.01.001">
                                @error('dadosEdicao.conta_credito') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Terceiro</label>
                                <input type="text" wire:model="dadosEdicao.nome_empresa" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="Nome do terceiro">
                                @error('dadosEdicao.nome_empresa') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Código Filial/Matriz</label>
                                <input type="text" wire:model="dadosEdicao.codigo_filial_matriz" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="Código da filial ou matriz">
                                @error('dadosEdicao.codigo_filial_matriz') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Histórico *</label>
                                <textarea wire:model="dadosEdicao.historico" rows="3" class="w-full rounded-md border-gray-300 shadow-sm resize-none" placeholder="Digite o histórico do lançamento"></textarea>
                                @error('dadosEdicao.historico') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Arquivo Origem</label>
                                <input type="text" wire:model="dadosEdicao.arquivo_origem" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="Ex: Lançamento Manual - Ajuste, NF 123456, Sistema ERP">
                                <p class="text-xs text-gray-500 mt-1">Opcional: Documento, sistema ou referência de origem</p>
                                @error('dadosEdicao.arquivo_origem') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="flex justify-end space-x-2 mt-6">
                            <button type="button" wire:click="fecharModalEdicao" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                Cancelar
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        @php
            $showModalAmarracao = $confirmarSalvarAmarracao || $edicaoTipo === 'amarracao';
        @endphp
        <div x-data="{ showAmarracao: @js($edicaoTipo === 'amarracao') }" @keydown.window.enter.prevent="if(showAmarracao){ $refs.btnNaoAmarracao.focus(); }">
            <template x-if="showAmarracao">
                <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-40">
                    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                        <h3 class="text-lg font-semibold mb-4">Salvar na amarração</h3>
                        <p class="mb-4">Deseja salvar essa conta na amarração?</p>
                        <div class="flex justify-end gap-2">
                            <button @click="$wire.confirmarSalvarContaAmarracao(); showAmarracao=false" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Sim</button>
                            <button x-ref="btnNaoAmarracao" @click="$wire.cancelarConfirmacaoEdicao(); showAmarracao=false" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Não</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Tabela -->
        <div>
            <table class="min-w-full table-auto divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider break-words whitespace-normal">
                            <input type="checkbox" wire:click="selecionarTodos" class="rounded border-gray-300">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider break-words whitespace-normal">
                            Conf.
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider break-words whitespace-normal cursor-pointer" wire:click="ordenar('data')">
                            Data
                            @if($ordenacao === 'data')
                                <span class="ml-1">{{ $direcao === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider break-words whitespace-normal cursor-pointer" wire:click="ordenar('nome_empresa')">
                            Terceiro
                            @if($ordenacao === 'nome_empresa')
                                <span class="ml-1">{{ $direcao === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider break-words whitespace-normal cursor-pointer" wire:click="ordenar('conta_debito')">
                            Débito
                            @if($ordenacao === 'conta_debito')
                                <span class="ml-1">{{ $direcao === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider break-words whitespace-normal cursor-pointer" wire:click="ordenar('conta_credito')">
                            Crédito
                            @if($ordenacao === 'conta_credito')
                                <span class="ml-1">{{ $direcao === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider break-words whitespace-normal cursor-pointer" wire:click="ordenar('valor')">
                            Valor
                            @if($ordenacao === 'valor')
                                <span class="ml-1">{{ $direcao === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider break-words whitespace-normal cursor-pointer" wire:click="ordenar('historico')">
                            Histórico
                            @if($ordenacao === 'historico')
                                <span class="ml-1">{{ $direcao === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider break-words whitespace-normal cursor-pointer" wire:click="ordenar('detalhes_operacao')">
                            Detalhes da Operação
                            @if($ordenacao === 'detalhes_operacao')
                                <span class="ml-1">{{ $direcao === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider break-words whitespace-normal cursor-pointer" wire:click="ordenar('codigo_filial_matriz')">
                            Código Filial/Matriz
                            @if($ordenacao === 'codigo_filial_matriz')
                                <span class="ml-1">{{ $direcao === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider break-words whitespace-normal">
                            Ações
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>
                            <input type="date" wire:model.live="filtroData" class="h-9 px-3 rounded-md border border-gray-300 bg-white text-xs placeholder-gray-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all w-full shadow-sm outline-none" />
                        </th>
                        <th>
                            <input type="text" wire:model.live.debounce.300ms="filtroTerceiro" placeholder="Buscar..." class="h-9 px-3 rounded-md border border-gray-300 bg-white text-xs placeholder-gray-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all w-full shadow-sm outline-none" />
                        </th>
                        <th>
                            <input type="text" wire:model.live.debounce.300ms="filtroContaDebito" placeholder="Débito..." class="h-9 px-3 rounded-md border border-gray-300 bg-white text-xs placeholder-gray-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all w-full shadow-sm outline-none" />
                        </th>
                        <th>
                            <input type="text" wire:model.live.debounce.300ms="filtroContaCredito" placeholder="Crédito..." class="h-9 px-3 rounded-md border border-gray-300 bg-white text-xs placeholder-gray-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all w-full shadow-sm outline-none" />
                        </th>
                        <th>
                            <input type="text" wire:model.live.debounce.300ms="filtroValor" placeholder="R$ 0,00" class="h-9 px-3 rounded-md border border-gray-300 bg-white text-xs placeholder-gray-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all w-full shadow-sm outline-none" />
                        </th>
                        <th>
                            <input type="text" wire:model.live.debounce.300ms="filtroHistorico" placeholder="Buscar..." class="h-9 px-3 rounded-md border border-gray-300 bg-white text-xs placeholder-gray-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all w-full shadow-sm outline-none" />
                        </th>
                        <th></th>
                        <th>
                            <input type="text" wire:model.live.debounce.300ms="filtroCodigoFilial" placeholder="Filial/Matriz..." class="h-9 px-3 rounded-md border border-gray-300 bg-white text-xs placeholder-gray-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 transition-all w-full shadow-sm outline-none" />
                        </th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lancamentos as $lancamento)
                        @php
                            $temAlteracaoConta = $lancamento->alteracoes()->where('tipo_alteracao', 'conta')->exists();
                            $debitoAtual = $lancamento->conta_debito;
                            $debitoOriginal = $lancamento->conta_debito_original;
                            $creditoAtual = $lancamento->conta_credito;
                            $creditoOriginal = $lancamento->conta_credito_original;
                            $temAmarracaoDebito = isset($lancamento->amarracao) && $lancamento->amarracao->conta_debito && $lancamento->amarracao->conta_debito !== $debitoAtual;
                            $temAmarracaoCredito = isset($lancamento->amarracao) && $lancamento->amarracao->conta_credito && $lancamento->amarracao->conta_credito !== $creditoAtual;
                        @endphp
                        <tr class="hover:bg-gray-50 {{ $temAlteracaoConta ? 'bg-yellow-50' : '' }} {{ $lancamento->conferido ? 'bg-green-50' : '' }}" 
                            wire:click="toggleConferido({{ $lancamento->id }})" 
                            wire:key="lancamento-{{ $lancamento->id }}"
                            data-lancamento-id="{{ $lancamento->id }}"
                            style="cursor: pointer;">
                            <td class="px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                                <input type="checkbox" wire:click="toggleSelecao({{ $lancamento->id }})" 
                                       @if(in_array($lancamento->id, $selecionados)) checked @endif
                                       class="rounded border-gray-300">
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 break-words whitespace-normal" onclick="event.stopPropagation()">
                                <div class="w-4 h-4 rounded-full border-2 {{ $lancamento->conferido ? 'bg-green-400 border-green-600' : 'bg-white border-gray-300' }}"></div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 break-words whitespace-normal" onclick="event.stopPropagation()">
                                @if($editandoId === $lancamento->id && $editandoCampo === 'data')
                                    <input type="date" 
                                           wire:model="valorEditando"
                                           wire:keydown.enter="salvarEdicao"
                                           wire:keydown.escape="cancelarEdicao"
                                           class="w-full rounded border-2 border-blue-500 text-sm bg-white focus:border-blue-600"
                                           value="{{ $lancamento->data->format('Y-m-d') }}"
                                           autofocus>
                                @else
                                    <div class="cursor-pointer hover:bg-blue-50 p-1 rounded" 
                                         wire:click="iniciarEdicao({{ $lancamento->id }}, 'data', '{{ $lancamento->data->format('Y-m-d') }}')"
                                         title="Clique para editar a data">
                                        {{ $lancamento->data->format('d/m/Y') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 break-words whitespace-normal">
                                @if($lancamento->terceiro_id && $lancamento->nome_terceiro)
                                    {{ $lancamento->nome_terceiro }}
                                @else
                                    {{ $lancamento->nome_empresa }}
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="flex items-center gap-1" onclick="event.stopPropagation()">
                                    <input type="text"
                                           value="{{ $debitoAtual }}"
                                           wire:blur="iniciarEdicao({{ $lancamento->id }}, 'conta_debito', $event.target.value)"
                                           class="w-20 rounded border-2 border-black text-sm bg-white focus:border-blue-500 input-debito"
                                           placeholder="Débito"
                                           data-lancamento-id="{{ $lancamento->id }}"
                                           onkeydown="handleKeyDown(event, this)">
                                    @if($debitoOriginal && $debitoOriginal !== $debitoAtual)
                                        <span class="text-xs text-gray-500 flex items-center" style="gap: 2px;">
                                            <button type="button"
                                                class="text-blue-600 hover:text-blue-900"
                                                onclick="voltarContaOriginalDebito(this, '{{ $debitoOriginal }}', '{{ $lancamento->id }}')"
                                                title="Voltar para valor original da importação">
                                                ⟳
                                            </button>
                                            <span class="ml-1" title="Valor original da importação">→ {{ $debitoOriginal }}</span>
                                        </span>
                                    @endif
                                    @if($temAmarracaoDebito)
                                        <span class="text-xs text-gray-500 flex items-center" style="gap: 2px;">
                                            <button type="button"
                                                class="text-green-600 hover:text-green-900"
                                                onclick="alternarContaAmarracaoDebito(this, '{{ $lancamento->amarracao->conta_debito }}', '{{ $lancamento->id }}')"
                                                title="Alternar para valor sugerido pela amarração">
                                                ↔
                                            </button>
                                            <span class="ml-1" title="Valor sugerido pela amarração">→ {{ $lancamento->amarracao->conta_debito }}</span>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="flex items-center gap-1" onclick="event.stopPropagation()">
                                    <input type="text"
                                           value="{{ $creditoAtual }}"
                                           wire:blur="iniciarEdicao({{ $lancamento->id }}, 'conta_credito', $event.target.value)"
                                           class="w-20 rounded border-2 border-black text-sm bg-white focus:border-blue-500 input-credito"
                                           placeholder="Crédito"
                                           data-lancamento-id="{{ $lancamento->id }}"
                                           onkeydown="handleKeyDownCredito(event, this)">
                                    @if($creditoOriginal && $creditoOriginal !== $creditoAtual)
                                        <span class="text-xs text-gray-500 flex items-center" style="gap: 2px;">
                                            <button type="button"
                                                class="text-blue-600 hover:text-blue-900"
                                                onclick="voltarContaOriginalCredito(this, '{{ $creditoOriginal }}', '{{ $lancamento->id }}')"
                                                title="Voltar para valor original da importação">
                                                ⟳
                                            </button>
                                            <span class="ml-1" title="Valor original da importação">→ {{ $creditoOriginal }}</span>
                                        </span>
                                    @endif
                                    @if($temAmarracaoCredito)
                                        <span class="text-xs text-gray-500 flex items-center" style="gap: 2px;">
                                            <button type="button"
                                                class="text-green-600 hover:text-green-900"
                                                onclick="alternarContaAmarracaoCredito(this, '{{ $lancamento->amarracao->conta_credito }}', '{{ $lancamento->id }}')"
                                                title="Alternar para valor sugerido pela amarração">
                                                ↔
                                            </button>
                                            <span class="ml-1" title="Valor sugerido pela amarração">→ {{ $lancamento->amarracao->conta_credito }}</span>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 break-words whitespace-normal" onclick="event.stopPropagation()">
                                @if($editandoId === $lancamento->id && $editandoCampo === 'valor')
                                    <input type="number" 
                                           wire:model="valorEditando"
                                           wire:keydown.enter="salvarEdicao"
                                           wire:keydown.escape="cancelarEdicao"
                                           step="0.01"
                                           min="0"
                                           class="w-full rounded border-2 border-blue-500 text-sm bg-white focus:border-blue-600"
                                           value="{{ $lancamento->valor }}"
                                           autofocus>
                                @else
                                    <div class="cursor-pointer hover:bg-blue-50 p-1 rounded" 
                                         wire:click="iniciarEdicao({{ $lancamento->id }}, 'valor', '{{ $lancamento->valor }}')"
                                         title="Clique para editar o valor">
                                        R$ {{ number_format($lancamento->valor, 2, ',', '.') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 break-words whitespace-normal" onclick="event.stopPropagation()">
                                @if($editandoId === $lancamento->id && $editandoCampo === 'historico')
                                    <div class="space-y-2">
                                        <textarea 
                                               wire:model="valorEditando"
                                               wire:keydown.escape="cancelarEdicao"
                                               class="w-full rounded border-2 border-blue-500 text-sm bg-white focus:border-blue-600 resize-none"
                                               rows="3"
                                               placeholder="Digite o histórico"
                                               autofocus>{{ $lancamento->historico }}</textarea>
                                        <div class="flex justify-end space-x-2">
                                            <button wire:click="cancelarEdicao" class="px-2 py-1 text-xs bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                                                Cancelar
                                            </button>
                                            <button wire:click="salvarEdicao" class="px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                                Salvar
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="cursor-pointer hover:bg-blue-50 p-1 rounded" 
                                         wire:click="iniciarEdicao({{ $lancamento->id }}, 'historico', '{{ addslashes($lancamento->historico) }}')"
                                         title="Clique para editar o histórico">
                                        {{ $lancamento->historico }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 break-words whitespace-normal">
                                <div x-data="{
                                    tags: [],
                                    input: '',
                                    lancamentoId: {{ $lancamento->id }},
                                    init() {
                                        this.loadTags();
                                    },
                                    loadTags() {
                                        const detalhes = '{{ $lancamento->detalhes_operacao ?? '' }}';
                                        const novasTags = detalhes ? detalhes.split(',').filter(t => t.trim() !== '') : [];
                                        this.tags = novasTags;
                                    },
                                    addTag() {
                                        if(this.input.trim() && !this.tags.includes(this.input.trim())) {
                                            this.tags.push(this.input.trim());
                                            this.input = '';
                                            this.updateLivewire();
                                        }
                                    },
                                    removeTag(index) {
                                        if (index >= 0 && index < this.tags.length) {
                                            this.tags.splice(index, 1);
                                            this.tags = [...this.tags]; // força reatividade
                                            this.updateLivewire();
                                        }
                                    },
                                    updateLivewire() {
                                        // Usar Livewire global
                                        if (window.Livewire) {
                                            try {
                                                const wireId = document.querySelector('[wire\\:id]').getAttribute('wire:id');
                                                const component = window.Livewire.find(wireId);
                                                
                                                if (component) {
                                                    component.call('atualizarDetalhesOperacao', this.lancamentoId, this.tags.join(','));
                                                }
                                            } catch (error) {
                                                console.error('Erro ao chamar método:', error);
                                            }
                                        }
                                        
                                        // Aguardar resposta do Livewire e forçar atualização
                                        setTimeout(() => {
                                            this.loadTags();
                                        }, 500);
                                    }
                                }" class="flex flex-wrap gap-1 items-center" wire:key="detalhes-{{ $lancamento->id }}" onclick="event.stopPropagation()">
                                    <template x-for="(tag, index) in tags" :key="`tag-{{ $lancamento->id }}-${index}`">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full flex items-center text-xs">
                                            <span x-text="tag"></span>
                                            <button type="button" class="ml-1 text-blue-600 hover:text-red-600" @click="removeTag(index)">&times;</button>
                                        </span>
                                    </template>
                                    <input type="text" x-model="input" @keydown.enter.prevent="addTag()" @blur="addTag()" class="border rounded px-2 py-1 text-xs focus:outline-none" placeholder="Digite e pressione Enter">
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 break-words whitespace-normal">
                                {{ $lancamento->codigo_filial_matriz }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap" onclick="event.stopPropagation()">
                                <div class="relative">
                                    <button wire:click="abrirMenuAcoes({{ $lancamento->id }})" 
                                            class="w-8 h-8 flex items-center justify-center rounded-md border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                        </svg>
                                    </button>
                                    
                                    @if($menuAcoesAberto === $lancamento->id)
                                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-50 border border-gray-200">
                                            <div class="py-1">
                                                <button wire:click="editarLancamento({{ $lancamento->id }})" 
                                                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Editar
                                                </button>
                                                <button wire:click="duplicarLancamento({{ $lancamento->id }})" 
                                                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                    </svg>
                                                    Duplicar
                                                </button>
                                                <button wire:click="excluirLancamento({{ $lancamento->id }})" 
                                                        class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Excluir
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-4 text-center text-gray-500">
                                Nenhum lançamento encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $lancamentos->links() }}
        </div>
    </div>
</div>

<script>
function handleKeyDown(event, input) {
    if (event.key === 'Enter' || event.key === 'ArrowDown' || event.key === 'ArrowUp') {
        event.preventDefault();
        
        const currentValue = input.value;
        const currentId = input.dataset.lancamentoId;
        const direction = (event.key === 'ArrowUp') ? 'up' : 'down';
        
        // Salvar o valor primeiro
        if (window.Livewire) {
            const wireId = document.querySelector('[wire\\:id]').getAttribute('wire:id');
            window.Livewire.find(wireId).call('iniciarEdicao', currentId, 'conta_debito', currentValue);
            window.Livewire.find(wireId).call('salvarEdicao');
        }
        
        // Se for Enter, marcar como conferido independentemente de alteração
        if (event.key === 'Enter') {
            setTimeout(() => {
                if (window.Livewire) {
                    const wireId = document.querySelector('[wire\\:id]').getAttribute('wire:id');
                    window.Livewire.find(wireId).call('marcarComoConferido', currentId);
                }
            }, 50);
        }
        
        // Navegar para o próximo input
        setTimeout(() => {
            const debitoInputs = document.querySelectorAll('.input-debito');
            let targetInput = null;
            
            // Encontrar o input atual
            let currentIndex = -1;
            for (let i = 0; i < debitoInputs.length; i++) {
                if (debitoInputs[i].dataset.lancamentoId == currentId) {
                    currentIndex = i;
                    break;
                }
            }
            
            if (currentIndex !== -1) {
                if (direction === 'down' && currentIndex + 1 < debitoInputs.length) {
                    // Navegar para baixo
                    targetInput = debitoInputs[currentIndex + 1];
                } else if (direction === 'up' && currentIndex - 1 >= 0) {
                    // Navegar para cima
                    targetInput = debitoInputs[currentIndex - 1];
                }
            }
            
            // Focar no input alvo se existir
            if (targetInput) {
                targetInput.focus();
                targetInput.select(); // Selecionar todo o texto para facilitar a edição
            }
        }, 100); // Pequeno delay para garantir que o valor foi salvo
    }
}

function handleKeyDownCredito(event, input) {
    if (event.key === 'Enter' || event.key === 'ArrowDown' || event.key === 'ArrowUp') {
        event.preventDefault();
        
        const currentValue = input.value;
        const currentId = input.dataset.lancamentoId;
        const direction = (event.key === 'ArrowUp') ? 'up' : 'down';
        
        // Salvar o valor primeiro
        if (window.Livewire) {
            const wireId = document.querySelector('[wire\\:id]').getAttribute('wire:id');
            window.Livewire.find(wireId).call('iniciarEdicao', currentId, 'conta_credito', currentValue);
            window.Livewire.find(wireId).call('salvarEdicao');
        }
        
        // Se for Enter, marcar como conferido independentemente de alteração
        if (event.key === 'Enter') {
            setTimeout(() => {
                if (window.Livewire) {
                    const wireId = document.querySelector('[wire\\:id]').getAttribute('wire:id');
                    window.Livewire.find(wireId).call('marcarComoConferido', currentId);
                }
            }, 50);
        }
        
        // Navegar para o próximo input
        setTimeout(() => {
            const creditoInputs = document.querySelectorAll('.input-credito');
            let targetInput = null;
            
            // Encontrar o input atual
            let currentIndex = -1;
            for (let i = 0; i < creditoInputs.length; i++) {
                if (creditoInputs[i].dataset.lancamentoId == currentId) {
                    currentIndex = i;
                    break;
                }
            }
            
            if (currentIndex !== -1) {
                if (direction === 'down' && currentIndex + 1 < creditoInputs.length) {
                    // Navegar para baixo
                    targetInput = creditoInputs[currentIndex + 1];
                } else if (direction === 'up' && currentIndex - 1 >= 0) {
                    // Navegar para cima
                    targetInput = creditoInputs[currentIndex - 1];
                }
            }
            
            // Focar no input alvo se existir
            if (targetInput) {
                targetInput.focus();
                targetInput.select(); // Selecionar todo o texto para facilitar a edição
            }
        }, 100); // Pequeno delay para garantir que o valor foi salvo
    }
}

function reverterContaDebito(btn, valorOriginal, lancamentoId) {
    let input = btn.closest('.flex').querySelector('.input-debito');
    if (!input) {
        input = document.querySelector('.input-debito[data-lancamento-id="' + lancamentoId + '"]');
    }
    let valorFora = document.querySelector('.valor-fora[data-tipo="debito"][data-lancamento-id="' + lancamentoId + '"]');
    if (input && valorFora) {
        const valorAtual = input.value;
        input.value = valorFora.textContent;
        valorFora.textContent = valorAtual;
        // Atualiza o tooltip
        if (valorFora.getAttribute('title') === 'Valor da amarração') {
            valorFora.setAttribute('title', 'Valor original da importação');
        } else {
            valorFora.setAttribute('title', 'Valor da amarração');
        }
        input.dispatchEvent(new Event('blur', { bubbles: true }));
        input.focus();
        input.select();
    }
}
function reverterContaCredito(btn, valorOriginal, lancamentoId) {
    let input = btn.closest('.flex').querySelector('.input-credito');
    if (!input) {
        input = document.querySelector('.input-credito[data-lancamento-id="' + lancamentoId + '"]');
    }
    let valorFora = document.querySelector('.valor-fora[data-tipo="credito"][data-lancamento-id="' + lancamentoId + '"]');
    if (input && valorFora) {
        const valorAtual = input.value;
        input.value = valorFora.textContent;
        valorFora.textContent = valorAtual;
        // Atualiza o tooltip
        if (valorFora.getAttribute('title') === 'Valor da amarração') {
            valorFora.setAttribute('title', 'Valor original da importação');
        } else {
            valorFora.setAttribute('title', 'Valor da amarração');
        }
        input.dispatchEvent(new Event('blur', { bubbles: true }));
        input.focus();
        input.select();
    }
}

function voltarContaOriginalDebito(btn, valorOriginal, lancamentoId) {
    let input = btn.closest('.flex').querySelector('.input-debito');
    if (!input) {
        input = document.querySelector('.input-debito[data-lancamento-id="' + lancamentoId + '"]');
    }
    if (input) {
        input.value = valorOriginal;
        input.dispatchEvent(new Event('blur', { bubbles: true }));
        input.focus();
        input.select();
    }
}
function alternarContaAmarracaoDebito(btn, valorAmarracao, lancamentoId) {
    let input = btn.closest('.flex').querySelector('.input-debito');
    if (!input) {
        input = document.querySelector('.input-debito[data-lancamento-id="' + lancamentoId + '"]');
    }
    if (input) {
        input.value = valorAmarracao;
        input.dispatchEvent(new Event('blur', { bubbles: true }));
        input.focus();
        input.select();
    }
}
function voltarContaOriginalCredito(btn, valorOriginal, lancamentoId) {
    let input = btn.closest('.flex').querySelector('.input-credito');
    if (!input) {
        input = document.querySelector('.input-credito[data-lancamento-id="' + lancamentoId + '"]');
    }
    if (input) {
        input.value = valorOriginal;
        input.dispatchEvent(new Event('blur', { bubbles: true }));
        input.focus();
        input.select();
    }
}
function alternarContaAmarracaoCredito(btn, valorAmarracao, lancamentoId) {
    let input = btn.closest('.flex').querySelector('.input-credito');
    if (!input) {
        input = document.querySelector('.input-credito[data-lancamento-id="' + lancamentoId + '"]');
    }
    if (input) {
        input.value = valorAmarracao;
        input.dispatchEvent(new Event('blur', { bubbles: true }));
        input.focus();
        input.select();
    }
}

// Garantir que os eventos de clique funcionem após ordenação
document.addEventListener('livewire:init', () => {
    // Preservar estado de edição durante atualizações
    Livewire.on('preservar-edicao', (data) => {
        // O estado será preservado automaticamente pelo Livewire
    });
    // Função para garantir que o cursor pointer seja aplicado nas linhas
    function aplicarCursorPointer() {
        const rows = document.querySelectorAll('tr[wire\\:click*="toggleConferido"]');
        rows.forEach(row => {
            row.style.cursor = 'pointer';
        });
    }
    
    // Aplicar cursor pointer inicialmente
    aplicarCursorPointer();
    
    // Reaplicar após ordenação
    Livewire.on('ordenacao-alterada', () => {
        setTimeout(aplicarCursorPointer, 100);
    });
    
    // Reaplicar após qualquer atualização do Livewire
    Livewire.on('$refresh', () => {
        setTimeout(aplicarCursorPointer, 100);
    });
    
    // Escutar evento de tags atualizadas
    Livewire.on('tags-atualizadas', (data) => {
        // Se for array, pegue o primeiro elemento
        if (Array.isArray(data)) {
            data = data[0];
        }
        const afetados = Array.isArray(data.lancamentos_afetados) ? data.lancamentos_afetados : [];
        
        // Forçar atualização completa do Livewire
        if (window.Livewire) {
            const wireId = document.querySelector('[wire\\:id]').getAttribute('wire:id');
            const component = window.Livewire.find(wireId);
            if (component) {
                component.call('$refresh');
            }
        }
        
        // Aguardar um pouco e então atualizar os componentes Alpine
        setTimeout(() => {
            document.querySelectorAll('[x-data*="tags"]').forEach(tagsContainer => {
                const lancamentoRow = tagsContainer.closest('tr[data-lancamento-id]');
                if (!lancamentoRow) return;
                const lancamentoId = parseInt(lancamentoRow.getAttribute('data-lancamento-id'));
                if (afetados.includes(lancamentoId) || lancamentoId === data.lancamento_origem_id) {
                    const alpineComponent = Alpine.$data(tagsContainer);
                    if (alpineComponent && alpineComponent.loadTags) {
                        // Recarregar as tags do Alpine.js
                        alpineComponent.loadTags();
                        tagsContainer.style.backgroundColor = '#fef3c7';
                        setTimeout(() => {
                            tagsContainer.style.backgroundColor = '';
                        }, 1000);
                    }
                }
            });
        }, 200);
    });
    
    // Reaplicar após qualquer atualização do componente
    Livewire.on('$updated', () => {
        setTimeout(aplicarCursorPointer, 100);
    });
    
    // Garantir que a funcionalidade de conferência funcione após qualquer atualização
    Livewire.on('conferido-alterado', (id, conferido) => {
        // Atualizar visual da linha se necessário
        const row = document.querySelector(`tr[wire\\:key="lancamento-${id}"]`);
        if (row) {
            if (conferido) {
                row.classList.add('bg-green-50');
            } else {
                row.classList.remove('bg-green-50');
            }
        }
    });
    
    // Garantir que o Alpine.js seja reinicializado após atualizações do Livewire
    Livewire.on('$refresh', () => {
        // Pequeno delay para garantir que o DOM foi atualizado
        setTimeout(() => {
            // Forçar reinicialização do Alpine.js se necessário
            if (window.Alpine) {
                window.Alpine.initTree(document.body);
            }
        }, 50);
    });
    
    // Garantir que o Alpine.js seja reinicializado após qualquer atualização
    Livewire.on('$updated', () => {
        setTimeout(() => {
            if (window.Alpine) {
                window.Alpine.initTree(document.body);
            }
        }, 50);
    });
    
    // Fechar menu de ações quando clicar fora
    document.addEventListener('click', function(event) {
        const menuButtons = document.querySelectorAll('[wire\\:click*="abrirMenuAcoes"]');
        const isMenuButton = Array.from(menuButtons).some(button => button.contains(event.target));
        
        if (!isMenuButton && !event.target.closest('.absolute')) {
            // Se clicou fora do menu e não é um botão de menu, fechar menu
            if (window.Livewire) {
                const wireId = document.querySelector('[wire\\:id]').getAttribute('wire:id');
                const component = window.Livewire.find(wireId);
                if (component) {
                    component.call('fecharMenuAcoes');
                }
            }
        }
    });
});
</script>

