<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Plataforma\AgendaController;

// Route::get('test', [TestController::class, 'test']);
Route::get('obtenerFechas', [AgendaController::class, 'obtenerFechas']);
