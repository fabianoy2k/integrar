<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\MenuTrait;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    use MenuTrait;

    public static function getMenuData()
    {
        $instance = new self();
        return [
            'menuItems' => $instance->getMenuOptions(),
            'userData' => $instance->getUserData()
        ];
    }
} 