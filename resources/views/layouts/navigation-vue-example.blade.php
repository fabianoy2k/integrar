<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-700 flex items-center gap-2">
                        <x-application-logo class="block h-9 w-auto fill-current text-blue-700" />
                        IntegraExpert
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                    <!-- Cadastros Dropdown - Vue Component -->
                    <vue-menu menu-title="📋 Cadastros" menu-width="w-48">
                        <a href="{{ route('empresas') }}" class="block px-4 py-2 hover:bg-blue-50">🏢 Empresas</a>
                        <a href="{{ route('usuarios') }}" class="block px-4 py-2 hover:bg-blue-50">👥 Usuários</a>
                        <a href="{{ route('terceiros') }}" class="block px-4 py-2 hover:bg-blue-50">🤝 Terceiros</a>
                    </vue-menu>

                    <!-- Importação Dropdown - Vue Component -->
                    <vue-menu menu-title="📥 Importação" menu-width="w-56">
                        <a href="{{ route('importador-avancado') }}" class="block px-4 py-2 hover:bg-blue-50">📄 Importador Avançado</a>
                        <a href="{{ route('importador-personalizado') }}" class="block px-4 py-2 hover:bg-blue-50 text-red-600 font-bold">🎯 Importador Personalizado (TESTE)</a>
                        <a href="{{ route('importacoes') }}" class="block px-4 py-2 hover:bg-blue-50">🕑 Importações anteriores</a>
                        <a href="{{ route('parametros-extratos') }}" class="block px-4 py-2 hover:bg-blue-50">📝 Parâmetros de Extrato</a>
                    </vue-menu>

                    <!-- Lançamentos Dropdown - Vue Component -->
                    <vue-menu menu-title="📊 Lançamentos" menu-width="w-56">
                        <a href="{{ route('tabela') }}" class="block px-4 py-2 hover:bg-blue-50">📋 Tabela de lançamentos</a>
                        <a href="{{ route('amarracoes') }}" class="block px-4 py-2 hover:bg-blue-50">🔗 Amarrações</a>
                        <a href="{{ route('regras-amarracao') }}" class="block px-4 py-2 hover:bg-blue-50">⚙️ Regras de Amarração</a>
                        <span class="block px-4 py-2 text-gray-400 cursor-not-allowed">🛠️ Reclassificações <span class="text-xs">(em breve)</span></span>
                    </vue-menu>

                    <!-- Exportação Dropdown - Vue Component -->
                    <vue-menu menu-title="📤 Exportação" menu-width="w-48">
                        <a href="{{ route('exportador') }}" class="block px-4 py-2 hover:bg-blue-50">📤 Exportador</a>
                    </vue-menu>

                    <!-- Administração Dropdown - Vue Component -->
                    <vue-menu menu-title="⚙️ Administração" menu-width="w-56">
                        <span class="block px-4 py-2 text-gray-400 cursor-not-allowed">🛠️ Configurações <span class="text-xs">(em breve)</span></span>
                        <span class="block px-4 py-2 text-gray-400 cursor-not-allowed">📜 Logs <span class="text-xs">(em breve)</span></span>
                        <span class="block px-4 py-2 text-gray-400 cursor-not-allowed">🔑 Acessos <span class="text-xs">(em breve)</span></span>
                    </vue-menu>
                </div>
            </div>

            <!-- Avatar do usuário logado -->
            <div x-data="{ openUser: false }" class="relative ml-4">
                <button @click="openUser = !openUser" class="flex items-center gap-2 font-semibold text-gray-700 hover:text-blue-700 focus:outline-none">
                    <span class="inline-block bg-blue-100 text-blue-700 rounded-full w-8 h-8 flex items-center justify-center">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="openUser" @click.away="openUser = false" class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-50">
                    <a href="{{ route('profile.edit') }}" @click="openUser = false" class="block px-4 py-2 hover:bg-blue-50">⚙️ Configurações</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-blue-50">🚪 Sair</button>
                    </form>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('usuarios')" :active="request()->routeIs('usuarios')">
                Usuários
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('importador-avancado')" :active="request()->routeIs('importador-avancado')">
                Importador Avançado
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('importador-personalizado')" :active="request()->routeIs('importador-personalizado')">
                Importador Personalizado
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('tabela')" :active="request()->routeIs('tabela')">
                Tabela Lançamentos
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('empresas')" :active="request()->routeIs('empresas')">
                Empresas
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('terceiros')" :active="request()->routeIs('terceiros')">
                Terceiros
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('amarracoes')" :active="request()->routeIs('amarracoes')">
                Amarrações
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('regras-amarracao')" :active="request()->routeIs('regras-amarracao')">
                Regras de Amarração
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('importacoes')" :active="request()->routeIs('importacoes')">
                Importações
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('exportador')" :active="request()->routeIs('exportador')">
                Exportador
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('parametros-extratos')" :active="request()->routeIs('parametros-extratos')">
                Parâmetros Extratos
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav> 