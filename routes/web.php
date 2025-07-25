<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// Redirecionar '/' para home
Route::get('/', function () {
    return redirect()->route('home');
});

// Rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {
    Route::get('/usuarios', App\Livewire\GerenciadorUsuarios::class)->name('usuarios');
    Route::get('/importador', function () {
        return redirect()->route('importador-avancado');
    });
    Route::get('/importador-avancado', App\Livewire\ImportadorAvancado::class)->name('importador-avancado');
    Route::get('/importador-personalizado', App\Livewire\ImportadorPersonalizado::class)->name('importador-personalizado');
    Route::get('/tabela', App\Livewire\TabelaLancamentos::class)->name('tabela');
    Route::get('/empresas', App\Livewire\GerenciadorEmpresas::class)->name('empresas');
    Route::get('/terceiros', App\Livewire\GerenciadorTerceiros::class)->name('terceiros');
    Route::get('/amarracoes', App\Livewire\GerenciadorAmarracoes::class)->name('amarracoes');
    Route::get('/regras-amarracao', App\Livewire\GerenciadorRegrasAmarracao::class)->name('regras-amarracao');
    Route::get('/importacoes', App\Livewire\ListaImportacoes::class)->name('importacoes');
    Route::get('/exportador', App\Livewire\ExportadorContabil::class)->name('exportador');
    Route::get('/parametros-extratos', App\Livewire\GerenciadorParametrosExtratos::class)->name('parametros-extratos');
    Route::get('/extrator-bancario', App\Livewire\ExtratorBancario::class)->name('extrator-bancario');
    Route::get('/home', App\Livewire\Home::class)->name('home');

    // Rota de exemplo para navegação Vue
    Route::get('/vue-navigation-example', function () {
        return view('vue-navigation-example');
    })->name('vue-navigation-example');

    // Rota para testar Vue
    Route::get('/teste-vue', function () {
        return view('teste-vue');
    })->name('teste-vue');

    // Rota para teste Vue simples
    Route::get('/teste-vue-simples', function () {
        return view('teste-vue-simples');
    })->name('teste-vue-simples');

    // CRUD Empresas Operadoras (apenas admin)
    Route::get('/empresas-operadoras', App\Livewire\EmpresasOperadorasForm::class)->name('empresas-operadoras');
});

// Rota para download de arquivos (fora do middleware de autenticação)
Route::get('/download/{arquivo}', function ($arquivo) {
    $path = storage_path("app/exports/{$arquivo}");
    
    if (!file_exists($path)) {
        abort(404, 'Arquivo não encontrado');
    }
    
    return response()->download($path);
})->name('download.arquivo');

// Rota para download de arquivos da API
Route::get('/download-arquivo/{arquivo}', function ($arquivo) {
    $path = storage_path("app/exports/{$arquivo}");
    
    if (!file_exists($path)) {
        abort(404, 'Arquivo não encontrado');
    }
    
    return response()->download($path);
})->name('download.arquivo.api');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
