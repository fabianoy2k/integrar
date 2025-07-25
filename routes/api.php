<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExportadorContabilController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas do Exportador Contábil
Route::prefix('exportador-contabil')->group(function () {
    Route::get('/importacoes', [ExportadorContabilController::class, 'getImportacoes']);
    Route::get('/empresas', [ExportadorContabilController::class, 'getEmpresas']);
    Route::get('/importacoes/{id}', [ExportadorContabilController::class, 'getImportacao']);
    Route::get('/empresas/buscar-por-codigo/{codigo}', [ExportadorContabilController::class, 'buscarEmpresaPorCodigo']);
    Route::get('/lancamentos/datas/{importacaoId}', [ExportadorContabilController::class, 'getDatasLancamentos']);
    Route::get('/lancamentos/quantidade', [ExportadorContabilController::class, 'getQuantidadeLancamentos']);
    Route::post('/exportar', [ExportadorContabilController::class, 'exportar']);
});

// Rotas simplificadas para compatibilidade
Route::get('/importacoes', [ExportadorContabilController::class, 'getImportacoes']);
Route::get('/empresas', [ExportadorContabilController::class, 'getEmpresas']);
Route::get('/importacoes/{id}', [ExportadorContabilController::class, 'getImportacao']);
Route::get('/empresas/buscar-por-codigo/{codigo}', [ExportadorContabilController::class, 'buscarEmpresaPorCodigo']);
Route::get('/lancamentos/datas/{importacaoId}', [ExportadorContabilController::class, 'getDatasLancamentos']);
Route::get('/lancamentos/quantidade', [ExportadorContabilController::class, 'getQuantidadeLancamentos']);
Route::post('/exportar-contabil', [ExportadorContabilController::class, 'exportar']); 