<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

trait MenuTrait
{
    public function getMenuOptions()
    {
        $user = Auth::user();
        
        $menuItems = [
            [
                'id' => 'cadastros',
                'name' => '📋 Cadastros',
                'icon' => 'fa-database',
                'items' => [
                    [
                        'name' => '🏢 Empresas',
                        'url' => route('empresas'),
                        'active' => request()->routeIs('empresas*'),
                    ],
                    [
                        'name' => '👥 Usuários',
                        'url' => route('usuarios'),
                        'active' => request()->routeIs('usuarios*'),
                    ],
                    [
                        'name' => '🤝 Terceiros',
                        'url' => route('terceiros'),
                        'active' => request()->routeIs('terceiros*'),
                    ],
                ],
            ],
            [
                'id' => 'importacao',
                'name' => '📥 Importação',
                'icon' => 'fa-upload',
                'items' => [
                    [
                        'name' => '📄 Importador Avançado',
                        'url' => route('importador-avancado'),
                        'active' => request()->routeIs('importador-avancado*'),
                    ],
                    [
                        'name' => '🎯 Importador Personalizado',
                        'url' => route('importador-personalizado'),
                        'active' => request()->routeIs('importador-personalizado*'),
                    ],
                    [
                        'name' => '🕑 Importações anteriores',
                        'url' => route('importacoes'),
                        'active' => request()->routeIs('importacoes*'),
                    ],
                    [
                        'name' => '📝 Parâmetros de Extrato',
                        'url' => route('parametros-extratos'),
                        'active' => request()->routeIs('parametros-extratos*'),
                    ],
                ],
            ],
            [
                'id' => 'lancamentos',
                'name' => '📊 Lançamentos',
                'icon' => 'fa-chart-bar',
                'items' => [
                    [
                        'name' => '📋 Tabela de lançamentos',
                        'url' => route('tabela'),
                        'active' => request()->routeIs('tabela*'),
                    ],
                    [
                        'name' => '🔗 Amarrações',
                        'url' => route('amarracoes'),
                        'active' => request()->routeIs('amarracoes*'),
                    ],
                    [
                        'name' => '⚙️ Regras de Amarração',
                        'url' => route('regras-amarracao'),
                        'active' => request()->routeIs('regras-amarracao*'),
                    ],
                    [
                        'name' => '🛠️ Reclassificações',
                        'url' => '#',
                        'active' => false,
                        'class' => 'text-gray-400 cursor-not-allowed',
                        'disabled' => true,
                        'note' => '(em breve)',
                    ],
                ],
            ],
            [
                'id' => 'exportacao',
                'name' => '📤 Exportação',
                'icon' => 'fa-download',
                'items' => [
                    [
                        'name' => '📤 Exportador',
                        'url' => route('exportador'),
                        'active' => request()->routeIs('exportador*'),
                    ],
                ],
            ],
            [
                'id' => 'administracao',
                'name' => '⚙️ Administração',
                'icon' => 'fa-cog',
                'items' => [
                    [
                        'name' => '🛠️ Configurações',
                        'url' => '#',
                        'active' => false,
                        'class' => 'text-gray-400 cursor-not-allowed',
                        'disabled' => true,
                        'note' => '(em breve)',
                    ],
                    [
                        'name' => '📜 Logs',
                        'url' => '#',
                        'active' => false,
                        'class' => 'text-gray-400 cursor-not-allowed',
                        'disabled' => true,
                        'note' => '(em breve)',
                    ],
                    [
                        'name' => '🔑 Acessos',
                        'url' => '#',
                        'active' => false,
                        'class' => 'text-gray-400 cursor-not-allowed',
                        'disabled' => true,
                        'note' => '(em breve)',
                    ],
                ],
            ],
        ];

        // Filtrar itens baseado no papel do usuário
        if ($user->role === 'operador') {
            // Remover itens administrativos para operadores
            $menuItems = array_filter($menuItems, function($menu) {
                return !in_array($menu['id'], ['administracao']);
            });
        }

        return $menuItems;
    }

    public function getUserData()
    {
        $user = Auth::user();
        
        return [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'initial' => strtoupper(substr($user->name, 0, 1)),
        ];
    }
} 