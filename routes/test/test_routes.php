<?php

use App\Http\Controllers\Testing\TestController;
use Illuminate\Support\Facades\Route;

Route::get('test', [TestController::class, 'test']);
Route::get('carga_cache', [TestController::class, 'carga_cache']);
Route::get('ver_cache', [TestController::class, 'ver_cache']);
Route::get('obtenerTitularesCache', [TestController::class, 'obtenerTitularesCache']);
Route::get('obtener_personas_cache/{id_persona}', [TestController::class, 'obtener_personas_cache']);
Route::get('obtener_personas/{id_persona}', [TestController::class, 'obtener_personas']);
Route::get('almacenar_personas_en_cache', [TestController::class, 'almacenar_personas_en_cache']);
