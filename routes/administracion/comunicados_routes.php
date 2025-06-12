<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administracion\ComunicadoController;

// comunicados
Route::get('/listar_comunicados', [ComunicadoController::class, 'listar_comunicados'])
    ->name('listar_comunicados');
Route::post('/registrar_comunicado', [ComunicadoController::class, 'registrar_comunicado'])
    ->name('registrar_comunicado');
