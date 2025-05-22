<?php

use App\Http\Controllers\Administracion\PersonaController;
use Illuminate\Support\Facades\Route;

// Route::get('/obtener_imagen_persona/{id_persona}', [ImageController::class, 'obtener_imagen_persona']);
Route::get('/obtener_imagen_persona/{id_persona}', [PersonaController::class, 'obtener_imagen_persona']);
Route::get('/cachePoblacionAsegurada', [PersonaController::class, 'obtenerPoblacionAseguradaCache']);
Route::post('/obtener_id_titular_login', [PersonaController::class, 'obtener_id_titular']);
