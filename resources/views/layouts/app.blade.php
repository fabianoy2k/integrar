<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>IntegraExpert</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                <div id="app">
                    @hasSection('content')
                        @yield('content')
                    @else
                        {{ $slot }}
                    @endif
                </div>
            </main>
        </div>
        @livewireScripts
        
        <script>
        // Teste de debug para Vue
        console.log('🔍 Debug Vue - Página carregada');
        console.log('Vue disponível:', !!window.Vue);
        console.log('Elemento app:', !!document.getElementById('app'));
        console.log('Elemento vue-navigation:', !!document.getElementById('vue-navigation'));
        console.log('Componente navigation:', !!document.querySelector('navigation-component'));
        
        // Aguardar um pouco e verificar novamente
        setTimeout(() => {
            console.log('🔍 Debug Vue - Verificação tardia');
            console.log('Vue disponível:', !!window.Vue);
            console.log('Componente navigation:', !!document.querySelector('navigation-component'));
            console.log('Indicadores Vue:', document.querySelectorAll('.bg-green-500, .bg-blue-500').length);
        }, 3000);
        </script>
    </body>
</html>
