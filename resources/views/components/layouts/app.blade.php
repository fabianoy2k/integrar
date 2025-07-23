<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN -->
    <!-- Remover o Tailwind CDN -->
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <!-- Forçar reload dos assets -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="text-xl font-bold text-blue-700">
                                IntegraExpert
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex items-center">
                            <!-- Cadastros Dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-1 font-semibold text-gray-700 hover:text-blue-700 focus:outline-none">
                                    📋 Cadastros
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white border rounded shadow-lg z-50">
                                    <a href="{{ route('empresas') }}" @click="open = false" class="block px-4 py-2 hover:bg-blue-50">🏢 Empresas</a>
                                    <a href="{{ route('usuarios') }}" @click="open = false" class="block px-4 py-2 hover:bg-blue-50">👥 Usuários</a>
                                    <a href="{{ route('terceiros') }}" @click="open = false" class="block px-4 py-2 hover:bg-blue-50">🤝 Terceiros</a>
                                </div>
                            </div>
                            <!-- Importação Dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-1 font-semibold text-gray-700 hover:text-blue-700 focus:outline-none">
                                    📥 Importação
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-56 bg-white border rounded shadow-lg z-50">
                                    <a href="{{ route('importador-avancado') }}" @click="open = false" class="block px-4 py-2 hover:bg-blue-50">📄 Importador</a>
                                    <a href="{{ route('importacoes') }}" @click="open = false" class="block px-4 py-2 hover:bg-blue-50">🕑 Importações anteriores</a>
                                    <a href="{{ route('parametros-extratos') }}" @click="open = false" class="block px-4 py-2 hover:bg-blue-50">📝 Parâmetros de Extrato</a>
                                </div>
                            </div>
                            <!-- Lançamentos Dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-1 font-semibold text-gray-700 hover:text-blue-700 focus:outline-none">
                                    📊 Lançamentos
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-56 bg-white border rounded shadow-lg z-50">
                                    <a href="{{ route('tabela') }}" @click="open = false" class="block px-4 py-2 hover:bg-blue-50">📋 Tabela de lançamentos</a>
                                    <a href="{{ route('amarracoes') }}" @click="open = false" class="block px-4 py-2 hover:bg-blue-50">🔗 Amarrações</a>
                                    <span class="block px-4 py-2 text-gray-400 cursor-not-allowed">🛠️ Reclassificações <span class="text-xs">(em breve)</span></span>
                                </div>
                            </div>
                            <!-- Exportação Dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-1 font-semibold text-gray-700 hover:text-blue-700 focus:outline-none">
                                    📤 Exportação
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white border rounded shadow-lg z-50">
                                    <a href="{{ route('exportador') }}" @click="open = false" class="block px-4 py-2 hover:bg-blue-50">📤 Exportador</a>
                                </div>
                            </div>
                            <!-- Administração Dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-1 font-semibold text-gray-700 hover:text-blue-700 focus:outline-none">
                                    ⚙️ Administração
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-56 bg-white border rounded shadow-lg z-50">
                                    <span class="block px-4 py-2 text-gray-400 cursor-not-allowed">🛠️ Configurações <span class="text-xs">(em breve)</span></span>
                                    <span class="block px-4 py-2 text-gray-400 cursor-not-allowed">📜 Logs <span class="text-xs">(em breve)</span></span>
                                    <span class="block px-4 py-2 text-gray-400 cursor-not-allowed">🔑 Acessos <span class="text-xs">(em breve)</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Avatar fixo no canto superior direito -->
        <div style="position: absolute; top: 12px; right: 32px; z-index: 100;">
            <div x-data="{ openUser: false }" class="relative">
                <button @click="openUser = !openUser" class="flex items-center gap-2 font-semibold text-gray-700 hover:text-blue-700 focus:outline-none">
                    <span class="inline-block bg-blue-100 text-blue-700 rounded-full w-8 h-8 flex items-center justify-center">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="openUser" @click.away="openUser = false" class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-50">
                    <a href="{{ route('profile.edit') }}" @click="openUser = false" class="block px-4 py-2 hover:bg-blue-50">⚙️ Configurações</a>
                    @if(Auth::user() && Auth::user()->role === 'admin')
                        <a href="{{ route('empresas-operadoras') }}" @click="openUser = false" class="block px-4 py-2 hover:bg-blue-50">🏢 Empresas Operadoras</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-blue-50">🚪 Sair</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
