@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md">
        <!-- Cabeçalho -->
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-3xl font-bold text-gray-800">Sistema Contábil</h1>
            <p class="text-gray-600 mt-2">Ferramenta para importação, edição e exportação de lançamentos contábeis</p>
        </div>

        <!-- Cards de Navegação -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Importador -->
                <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-blue-800">Importador CSV</h3>
                            <p class="text-blue-600 text-sm">Importe arquivos CSV de lançamentos contábeis</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('importador') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                            Acessar Importador
                        </a>
                    </div>
                </div>

                <!-- Lista de Importações -->
                <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-green-800">Importações</h3>
                            <p class="text-green-600 text-sm">Visualize e gerencie as importações realizadas</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('importacoes') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                            Ver Importações
                        </a>
                    </div>
                </div>

                <!-- Lançamentos -->
                <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-purple-800">Lançamentos</h3>
                            <p class="text-purple-600 text-sm">Edite e visualize os lançamentos contábeis</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('tabela') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700">
                            Gerenciar Lançamentos
                        </a>
                    </div>
                </div>

                <!-- Terceiros -->
                <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-yellow-800">Terceiros</h3>
                            <p class="text-yellow-600 text-sm">Gerencie empresas, clientes e fornecedores</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('terceiros') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-md hover:bg-yellow-700">
                            Gerenciar Terceiros
                        </a>
                    </div>
                </div>

                <!-- Exportador -->
                <div class="bg-red-50 rounded-lg p-6 border border-red-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-red-800">Exportador</h3>
                            <p class="text-red-600 text-sm">Exporte lançamentos em formatos contábeis</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('exportador') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700">
                            Exportar Dados
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
