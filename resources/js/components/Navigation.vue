<template>
  <nav class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <!-- Logo -->
          <div class="shrink-0 flex items-center">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-700 flex items-center gap-2">
              <svg class="block h-9 w-auto fill-current text-blue-700" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
              </svg>
              IntegraExpert
              <span class="text-xs bg-green-500 text-white px-2 py-1 rounded-full">Vue.js</span>
            </a>
          </div>

          <!-- Navigation Links -->
          <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
            <!-- Cadastros Dropdown -->
            <div class="relative">
                              <button 
                  @click="toggleDropdown('cadastros')" 
                  class="flex items-center gap-1 font-semibold text-gray-700 hover:text-blue-700 focus:outline-none"
                >
                  📋 Cadastros
                  <span class="text-xs bg-blue-500 text-white px-1 rounded">Vue</span>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
              <div 
                v-show="activeDropdown === 'cadastros' && isLoaded" 
                @click.away="closeDropdowns"
                class="absolute left-0 mt-2 w-48 bg-white border rounded shadow-lg z-50"
                :class="{ 'opacity-0': !isLoaded }"
              >
                <a href="{{ route('empresas') }}" @click="closeDropdowns" class="block px-4 py-2 hover:bg-blue-50">🏢 Empresas</a>
                <a href="{{ route('usuarios') }}" @click="closeDropdowns" class="block px-4 py-2 hover:bg-blue-50">👥 Usuários</a>
                <a href="{{ route('terceiros') }}" @click="closeDropdowns" class="block px-4 py-2 hover:bg-blue-50">🤝 Terceiros</a>
              </div>
            </div>

            <!-- Importação Dropdown -->
            <div class="relative">
              <button 
                @click="toggleDropdown('importacao')" 
                class="flex items-center gap-1 font-semibold text-gray-700 hover:text-blue-700 focus:outline-none"
              >
                📥 Importação
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div 
                v-show="activeDropdown === 'importacao' && isLoaded" 
                @click.away="closeDropdowns"
                class="absolute left-0 mt-2 w-56 bg-white border rounded shadow-lg z-50"
                :class="{ 'opacity-0': !isLoaded }"
              >
                <a href="{{ route('importador-avancado') }}" @click="closeDropdowns" class="block px-4 py-2 hover:bg-blue-50">📄 Importador Avançado</a>
                <a href="{{ route('importador-personalizado') }}" @click="closeDropdowns" class="block px-4 py-2 hover:bg-blue-50 text-red-600 font-bold">🎯 Importador Personalizado (TESTE)</a>
                <a href="{{ route('importacoes') }}" @click="closeDropdowns" class="block px-4 py-2 hover:bg-blue-50">🕑 Importações anteriores</a>
                <a href="{{ route('parametros-extratos') }}" @click="closeDropdowns" class="block px-4 py-2 hover:bg-blue-50">📝 Parâmetros de Extrato</a>
              </div>
            </div>

            <!-- Lançamentos Dropdown -->
            <div class="relative">
              <button 
                @click="toggleDropdown('lancamentos')" 
                class="flex items-center gap-1 font-semibold text-gray-700 hover:text-blue-700 focus:outline-none"
              >
                📊 Lançamentos
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div 
                v-show="activeDropdown === 'lancamentos' && isLoaded" 
                @click.away="closeDropdowns"
                class="absolute left-0 mt-2 w-56 bg-white border rounded shadow-lg z-50"
                :class="{ 'opacity-0': !isLoaded }"
              >
                <a href="{{ route('tabela') }}" @click="closeDropdowns" class="block px-4 py-2 hover:bg-blue-50">📋 Tabela de lançamentos</a>
                <a href="{{ route('amarracoes') }}" @click="closeDropdowns" class="block px-4 py-2 hover:bg-blue-50">🔗 Amarrações</a>
                <a href="{{ route('regras-amarracao') }}" @click="closeDropdowns" class="block px-4 py-2 hover:bg-blue-50">⚙️ Regras de Amarração</a>
                <span class="block px-4 py-2 text-gray-400 cursor-not-allowed">🛠️ Reclassificações <span class="text-xs">(em breve)</span></span>
              </div>
            </div>

            <!-- Exportação Dropdown -->
            <div class="relative">
              <button 
                @click="toggleDropdown('exportacao')" 
                class="flex items-center gap-1 font-semibold text-gray-700 hover:text-blue-700 focus:outline-none"
              >
                📤 Exportação
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div 
                v-show="activeDropdown === 'exportacao' && isLoaded" 
                @click.away="closeDropdowns"
                class="absolute left-0 mt-2 w-48 bg-white border rounded shadow-lg z-50"
                :class="{ 'opacity-0': !isLoaded }"
              >
                <a href="{{ route('exportador') }}" @click="closeDropdowns" class="block px-4 py-2 hover:bg-blue-50">📤 Exportador</a>
              </div>
            </div>

            <!-- Administração Dropdown -->
            <div class="relative">
              <button 
                @click="toggleDropdown('administracao')" 
                class="flex items-center gap-1 font-semibold text-gray-700 hover:text-blue-700 focus:outline-none"
              >
                ⚙️ Administração
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div 
                v-show="activeDropdown === 'administracao' && isLoaded" 
                @click.away="closeDropdowns"
                class="absolute left-0 mt-2 w-56 bg-white border rounded shadow-lg z-50"
                :class="{ 'opacity-0': !isLoaded }"
              >
                <span class="block px-4 py-2 text-gray-400 cursor-not-allowed">🛠️ Configurações <span class="text-xs">(em breve)</span></span>
                <span class="block px-4 py-2 text-gray-400 cursor-not-allowed">📜 Logs <span class="text-xs">(em breve)</span></span>
                <span class="block px-4 py-2 text-gray-400 cursor-not-allowed">🔑 Acessos <span class="text-xs">(em breve)</span></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Avatar do usuário logado -->
        <div class="relative ml-4">
          <button 
            @click="toggleDropdown('user')" 
            class="flex items-center gap-2 font-semibold text-gray-700 hover:text-blue-700 focus:outline-none"
          >
            <span class="inline-block bg-blue-100 text-blue-700 rounded-full w-8 h-8 flex items-center justify-center">
              {{ userInitial }}
            </span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          <div 
            v-show="activeDropdown === 'user' && isLoaded" 
            @click.away="closeDropdowns"
            class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-50"
            :class="{ 'opacity-0': !isLoaded }"
          >
            <a href="{{ route('profile.edit') }}" @click="closeDropdowns" class="block px-4 py-2 hover:bg-blue-50">⚙️ Configurações</a>
            <button @click="logout" class="block w-full text-left px-4 py-2 hover:bg-blue-50">🚪 Sair</button>
          </div>
        </div>

        <!-- Hamburger -->
        <div class="-me-2 flex items-center sm:hidden">
          <button 
            @click="toggleMobileMenu" 
            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
          >
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
              <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div v-show="mobileMenuOpen" class="sm:hidden">
      <div class="pt-2 pb-3 space-y-1">
        <a href="{{ route('usuarios') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Usuários</a>
        <a href="{{ route('importador-avancado') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Importador Avançado</a>
        <a href="{{ route('importador-personalizado') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Importador Personalizado</a>
        <a href="{{ route('tabela') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Tabela Lançamentos</a>
        <a href="{{ route('empresas') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Empresas</a>
        <a href="{{ route('terceiros') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Terceiros</a>
        <a href="{{ route('amarracoes') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Amarrações</a>
        <a href="{{ route('regras-amarracao') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Regras de Amarração</a>
        <a href="{{ route('importacoes') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Importações</a>
        <a href="{{ route('exportador') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Exportador</a>
        <a href="{{ route('parametros-extratos') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Parâmetros Extratos</a>
      </div>

      <!-- Responsive Settings Options -->
      <div class="pt-4 pb-1 border-t border-gray-200">
        <div class="px-4">
          <div class="font-medium text-base text-gray-800">{{ userName }}</div>
          <div class="font-medium text-sm text-gray-500">{{ userEmail }}</div>
        </div>

        <div class="mt-3 space-y-1">
          <a href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Profile</a>
          <button @click="logout" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-blue-50">Log Out</button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navigation',
  data() {
    return {
      activeDropdown: null,
      mobileMenuOpen: false,
      userName: '',
      userEmail: '',
      userInitial: '',
      isLoaded: false
    }
  },
  mounted() {
    // Buscar dados do usuário via API ou props
    this.loadUserData()
    
    // Indicador de que é Vue
    console.log('🎉 Navegação Vue.js carregada!', this.$options.name)
    console.log('Vue Version:', this.$root.version)
    
    // Marcar como carregado para evitar flash
    this.$nextTick(() => {
      this.isLoaded = true
    })
  },
  methods: {
    toggleDropdown(dropdown) {
      if (this.activeDropdown === dropdown) {
        this.activeDropdown = null
      } else {
        this.activeDropdown = dropdown
      }
    },
    closeDropdowns() {
      this.activeDropdown = null
    },
    toggleMobileMenu() {
      this.mobileMenuOpen = !this.mobileMenuOpen
    },
    async loadUserData() {
      try {
        // Buscar dados do usuário via API
        const response = await fetch('/api/user')
        if (response.ok) {
          const user = await response.json()
          this.userName = user.name
          this.userEmail = user.email
          this.userInitial = user.name.charAt(0).toUpperCase()
        }
      } catch (error) {
        console.error('Erro ao carregar dados do usuário:', error)
        // Fallback para dados básicos
        this.userName = 'Usuário'
        this.userEmail = 'usuario@exemplo.com'
        this.userInitial = 'U'
      }
    },
    async logout() {
      try {
        const response = await fetch('/logout', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
          }
        })
        
        if (response.ok) {
          window.location.href = '/login'
        }
      } catch (error) {
        console.error('Erro ao fazer logout:', error)
        // Fallback para logout tradicional
        const form = document.createElement('form')
        form.method = 'POST'
        form.action = '/logout'
        
        const csrfToken = document.createElement('input')
        csrfToken.type = 'hidden'
        csrfToken.name = '_token'
        csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        
        form.appendChild(csrfToken)
        document.body.appendChild(form)
        form.submit()
      }
    }
  }
}
</script>

<style scoped>
/* Estilos específicos do componente se necessário */
.opacity-0 {
  opacity: 0;
  transition: opacity 0.2s ease-in-out;
}

/* Transição suave para os dropdowns */
[v-show] {
  transition: opacity 0.2s ease-in-out;
}
</style> 