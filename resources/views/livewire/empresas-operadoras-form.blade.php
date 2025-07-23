<div class="max-w-5xl mx-auto py-10">
    <div class="bg-white rounded-2xl shadow-xl p-10 border border-gray-200">
        <div class="flex items-center gap-3 mb-8">
            <span class="inline-block bg-blue-100 p-2 rounded-full"><img src="/favicon.ico" class="h-8 w-8"></span>
            <h2 class="text-3xl font-extrabold text-blue-800">Empresas Operadoras</h2>
        </div>

        <form wire:submit.prevent="salvarEmpresa" class="mb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block font-semibold mb-2 text-gray-700">Razão Social <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.defer="razao_social" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-sm" required>
                    @error('razao_social') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block font-semibold mb-2 text-gray-700">Nome Fantasia</label>
                    <input type="text" wire:model.defer="nome_fantasia" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-sm">
                </div>
                <div x-data="{ cnpj: @entangle('cnpj') }">
                    <label class="block font-semibold mb-2 text-gray-700">CNPJ <span class="text-red-500">*</span></label>
                    <input type="text" x-mask="99.999.999/9999-99" x-model="cnpj" wire:model.defer="cnpj" maxlength="18" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-sm" required>
                    @error('cnpj') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block font-semibold mb-2 text-gray-700">Inscrição Estadual</label>
                    <input type="text" wire:model.defer="inscricao_estadual" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-sm">
                </div>
                <div x-data="{ tel: @entangle('telefone') }">
                    <label class="block font-semibold mb-2 text-gray-700">Telefone</label>
                    <input type="text" x-mask="(99) 99999-9999" x-model="tel" wire:model.defer="telefone" maxlength="15" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-sm">
                </div>
                <div>
                    <label class="block font-semibold mb-2 text-gray-700">E-mail</label>
                    <input type="email" wire:model.defer="email" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-sm">
                    @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block font-semibold mb-2 text-gray-700">Responsável</label>
                    <input type="text" wire:model.defer="responsavel" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-sm">
                </div>
                <div>
                    <label class="block font-semibold mb-2 text-gray-700">Logotipo</label>
                    <input type="file" wire:model="logo" class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-400 focus:border-blue-500 shadow-sm">
                    @if($logo)
                        <img src="{{ $logo->temporaryUrl() }}" class="h-12 mt-2 rounded shadow">
                    @elseif($logo_atual)
                        <img src="{{ Storage::url($logo_atual) }}" class="h-12 mt-2 rounded shadow">
                    @endif
                    @error('logo') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-2">
                <button type="submit" class="bg-blue-700 text-white px-8 py-2 rounded-lg font-semibold hover:bg-blue-800 shadow">{{ $modoEdicao ? 'Atualizar' : 'Cadastrar' }}</button>
                @if($modoEdicao)
                    <button type="button" wire:click="resetarCampos" class="bg-gray-400 text-white px-8 py-2 rounded-lg font-semibold hover:bg-gray-500">Cancelar</button>
                @endif
            </div>
        </form>

        <div class="border-t border-gray-200 pt-8 mt-8">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded shadow text-sm">
                    <thead class="bg-blue-100 text-blue-900">
                        <tr>
                            <th class="px-4 py-3 font-bold">Logo</th>
                            <th class="px-4 py-3 font-bold">Razão Social</th>
                            <th class="px-4 py-3 font-bold">CNPJ</th>
                            <th class="px-4 py-3 font-bold">Telefone</th>
                            <th class="px-4 py-3 font-bold">E-mail</th>
                            <th class="px-4 py-3 font-bold">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($empresas as $empresa)
                            <tr class="border-b even:bg-gray-50 hover:bg-blue-50 transition-colors">
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
                                    <button wire:click="editarEmpresa({{ $empresa->id }})" class="text-blue-700 hover:underline font-semibold">Editar</button>
                                    <button wire:click="excluirEmpresa({{ $empresa->id }})" class="text-red-600 hover:underline font-semibold" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-gray-400 py-6">Nenhuma empresa cadastrada.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
