<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
    Route::get('/tabela', App\Livewire\TabelaLancamentos::class)->name('tabela');
    Route::get('/empresas', App\Livewire\GerenciadorEmpresas::class)->name('empresas');
    Route::get('/terceiros', App\Livewire\GerenciadorTerceiros::class)->name('terceiros');
    Route::get('/amarracoes', App\Livewire\GerenciadorAmarracoes::class)->name('amarracoes');
    Route::get('/importacoes', App\Livewire\ListaImportacoes::class)->name('importacoes');
    Route::get('/exportador', App\Livewire\ExportadorContabil::class)->name('exportador');
    Route::get('/parametros-extratos', App\Livewire\GerenciadorParametrosExtratos::class)->name('parametros-extratos');
    Route::get('/extrator-bancario', App\Livewire\ExtratorBancario::class)->name('extrator-bancario');
    Route::get('/home', App\Livewire\Home::class)->name('home');

    // CRUD Empresas Operadoras (apenas admin)
    Route::middleware(['auth'])->get('/empresas-operadoras', function () {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403, 'Acesso não autorizado.');
        }
        return \Livewire\Livewire::mount('empresas-operadoras-form');
    })->name('empresas-operadoras');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
