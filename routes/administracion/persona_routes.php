<?php

use App\Http\Controllers\Administracion\PersonaController;
use Illuminate\Support\Facades\Route;

// Route::get('/obtener_imagen_persona/{id_persona}', [ImageController::class, 'obtener_imagen_persona']);
Route::get('/obtener_imagen_persona/{id_persona}', [PersonaController::class, 'obtener_imagen_persona']);
Route::get('/cachePoblacionAsegurada', [PersonaController::class, 'obtenerPoblacionAseguradaCache']);
