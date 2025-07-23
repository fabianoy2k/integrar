<div class="max-w-5xl mx-auto py-10">
    <div class="bg-white rounded-lg shadow p-8">
        <h2 class="text-3xl font-bold mb-8 flex items-center gap-2">🏢 Empresas Operadoras</h2>

        <form wire:submit.prevent="salvarEmpresa" class="mb-10" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-semibold mb-1">Razão Social <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.defer="razao_social" class="w-full rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                    @error('razao_social') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block font-semibold mb-1">Nome Fantasia</label>
                    <input type="text" wire:model.defer="nome_fantasia" class="w-full rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div x-data="{ cnpj: @entangle('cnpj') }">
                    <label class="block font-semibold mb-1">CNPJ <span class="text-red-500">*</span></label>
                    <input type="text" x-mask="99.999.999/9999-99" x-model="cnpj" wire:model.defer="cnpj" maxlength="18" class="w-full rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                    @error('cnpj') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block font-semibold mb-1">Inscrição Estadual</label>
                    <input type="text" wire:model.defer="inscricao_estadual" class="w-full rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div x-data="{ tel: @entangle('telefone') }">
                    <label class="block font-semibold mb-1">Telefone</label>
                    <input type="text" x-mask="(99) 99999-9999" x-model="tel" wire:model.defer="telefone" maxlength="15" class="w-full rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block font-semibold mb-1">E-mail</label>
                    <input type="email" wire:model.defer="email" class="w-full rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block font-semibold mb-1">Responsável</label>
                    <input type="text" wire:model.defer="responsavel" class="w-full rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Logotipo</label>
                    <input type="file" wire:model="logo" class="w-full">
                    @if($logo)
                        <img src="{{ $logo->temporaryUrl() }}" class="h-12 mt-2 rounded shadow">
                    @elseif($logo_atual)
                        <img src="{{ Storage::url($logo_atual) }}" class="h-12 mt-2 rounded shadow">
                    @endif
                    @error('logo') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mt-6 flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 shadow">{{ $modoEdicao ? 'Atualizar' : 'Cadastrar' }}</button>
                @if($modoEdicao)
                    <button type="button" wire:click="resetarCampos" class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500">Cancelar</button>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded shadow">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">Logo</th>
                        <th class="px-4 py-2">Razão Social</th>
                        <th class="px-4 py-2">CNPJ</th>
                        <th class="px-4 py-2">Telefone</th>
                        <th class="px-4 py-2">E-mail</th>
                        <th class="px-4 py-2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empresas as $empresa)
                        <tr class="border-b hover:bg-blue-50">
                            <td class="px-4 py-2">
                                @if($empresa->logo)
                                    <img src="{{ Storage::url($empresa->logo) }}" class="h-8 rounded">
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $empresa->razao_social }}</td>
                            <td class="px-4 py-2">{{ $empresa->cnpj }}</td>
                            <td class="px-4 py-2">{{ $empresa->telefone }}</td>
                            <td class="px-4 py-2">{{ $empresa->email }}</td>
                            <td class="px-4 py-2 flex gap-2">
                                <button wire:click="editarEmpresa({{ $empresa->id }})" class="text-blue-600 hover:underline">Editar</button>
                                <button wire:click="excluirEmpresa({{ $empresa->id }})" class="text-red-600 hover:underline" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Máscaras com Alpine.js -->
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.plugin((Alpine) => {
            Alpine.directive('mask', (el, {expression}, {evaluate}) => {
                let mask = evaluate(expression);
                el.addEventListener('input', () => {
                    let v = el.value.replace(/\D/g, '');
                    let m = mask;
                    let i = 0;
                    el.value = m.replace(/9/g, _ => v[i++] || '');
                });
            });
        });
    });
    </script>
</div>
