<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>IntegraExpert</title>

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
        @php
            // Usar o controller para obter dados do menu
            $menuData = \App\Http\Controllers\MenuController::getMenuData();
            $menuItems = $menuData['menuItems'];
            $userData = $menuData['userData'];
        @endphp
        @include('layouts.menu-blade', ['menuItems' => $menuItems, 'userData' => $userData])

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
