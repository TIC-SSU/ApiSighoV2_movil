<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Administracion\PersonaController;

// Route::get('/obtener_imagen_persona/{id_persona}', [ImageController::class, 'obtener_imagen_persona']);
Route::get('/obtener_imagen_persona/{id_persona}', [PersonaController::class, 'obtener_imagen_persona'])
    ->name('obtener_imagen_persona');

Route::get('/cachePoblacionAsegurada', [PersonaController::class, 'obtenerPoblacionAseguradaCache'])
    ->name('obtenerPoblacionAseguradaCache');

Route::post('/obtener_id_titular_login', [PersonaController::class, 'obtener_id_titular'])
    ->name('obtener_id_titular');

Route::get('grupo_familiar/{id_persona}', [PersonaController::class, 'grupo_familiar'])
    ->name('grupo_familiar');
