<?php

use App\Http\Controllers\Administracion\ComunicadoController;
use App\Http\Controllers\Administracion\PersonaController;
use Illuminate\Support\Facades\Route;

// Route::get('/obtener_imagen_persona/{id_persona}', [ImageController::class, 'obtener_imagen_persona']);
Route::get('/obtener_imagen_persona/{id_persona}', [PersonaController::class, 'obtener_imagen_persona']);
Route::get('/cachePoblacionAsegurada', [PersonaController::class, 'obtenerPoblacionAseguradaCache']);
Route::post('/obtener_id_titular_login', [PersonaController::class, 'obtener_id_titular']);

// comunicados
Route::get('/listar_comunicados', [ComunicadoController::class, 'listar_comunicados'])->name('listar_comunicados');
Route::post('/registrar_comunicado', [ComunicadoController::class, 'registrar_comunicado'])->name('registrar_comunicado');
