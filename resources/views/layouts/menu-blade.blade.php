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

                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                    @foreach($menuItems as $menu)
                        <div class="relative group">
                            <button class="flex items-center gap-1 font-semibold text-gray-700 hover:text-blue-700 focus:outline-none">
                                {{ $menu['name'] }}

                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <div class="absolute left-0 mt-2 w-56 bg-white border rounded shadow-lg z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                @foreach($menu['items'] as $item)
                                    @if(isset($item['disabled']) && $item['disabled'])
                                        <span class="block px-4 py-2 text-gray-400 cursor-not-allowed">
                                            {{ $item['name'] }}
                                            @if(isset($item['note']))
                                                <span class="text-xs">{{ $item['note'] }}</span>
                                            @endif
                                        </span>
                                    @else
                                        <a href="{{ $item['url'] }}" 
                                           class="block px-4 py-2 hover:bg-blue-50 {{ $item['active'] ? 'bg-blue-50 text-blue-700' : '' }} {{ $item['class'] ?? '' }}">
                                            {{ $item['name'] }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Avatar do usuário logado -->
            <div class="relative ml-4 group">
                <button class="flex items-center gap-2 font-semibold text-gray-700 hover:text-blue-700 focus:outline-none">
                    <span class="inline-block bg-blue-100 text-blue-700 rounded-full w-8 h-8 flex items-center justify-center">
                        {{ $userData['initial'] }}
                    </span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-blue-50">⚙️ Configurações</a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-blue-50">🚪 Sair</button>
                    </form>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button id="mobile-menu-toggle" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path id="hamburger-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div id="mobile-menu" class="sm:hidden hidden">
        <div class="pt-2 pb-3 space-y-1">
            @foreach($menuItems as $menu)
                <div class="px-4 py-2">
                    <div class="font-medium text-gray-700 mb-2">{{ $menu['name'] }}</div>
                    @foreach($menu['items'] as $item)
                        @if(isset($item['disabled']) && $item['disabled'])
                            <div class="ml-4 text-gray-400 cursor-not-allowed">
                                {{ $item['name'] }}
                                @if(isset($item['note']))
                                    <span class="text-xs">{{ $item['note'] }}</span>
                                @endif
                            </div>
                        @else
                            <a href="{{ $item['url'] }}" 
                               class="ml-4 block text-gray-700 hover:bg-blue-50 px-2 py-1 rounded {{ $item['active'] ? 'bg-blue-50 text-blue-700' : '' }} {{ $item['class'] ?? '' }}">
                                {{ $item['name'] }}
                            </a>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ $userData['name'] }}</div>
                <div class="font-medium text-sm text-gray-500">{{ $userData['email'] }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-blue-50">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const closeIcon = document.getElementById('close-icon');
    
    mobileMenuToggle.addEventListener('click', function() {
        const isHidden = mobileMenu.classList.contains('hidden');
        
        if (isHidden) {
            mobileMenu.classList.remove('hidden');
            hamburgerIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
        } else {
            mobileMenu.classList.add('hidden');
            hamburgerIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        }
    });
    
    // Fechar menu mobile ao clicar fora
    document.addEventListener('click', function(event) {
        if (!mobileMenuToggle.contains(event.target) && !mobileMenu.contains(event.target)) {
            mobileMenu.classList.add('hidden');
            hamburgerIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
        }
    });
});
</script> 