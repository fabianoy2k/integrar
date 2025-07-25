@extends('layouts.vue-navigation')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-4">Exemplo de Navegação Vue</h1>
                <p class="mb-4">Esta página demonstra o uso do componente Vue de navegação.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-blue-800 mb-2">Cadastros</h3>
                        <p class="text-blue-600">Gerencie empresas, usuários e terceiros</p>
                    </div>
                    
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-green-800 mb-2">Importação</h3>
                        <p class="text-green-600">Importe dados de diferentes formatos</p>
                    </div>
                    
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-purple-800 mb-2">Lançamentos</h3>
                        <p class="text-purple-600">Visualize e gerencie lançamentos</p>
                    </div>
                    
                    <div class="bg-orange-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-orange-800 mb-2">Exportação</h3>
                        <p class="text-orange-600">Exporte dados em diferentes formatos</p>
                    </div>
                    
                    <div class="bg-red-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-red-800 mb-2">Administração</h3>
                        <p class="text-red-600">Configure e administre o sistema</p>
                    </div>
                    
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Vue.js</h3>
                        <p class="text-gray-600">Navegação moderna com Vue.js</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 