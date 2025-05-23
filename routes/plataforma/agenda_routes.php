<?php

use App\Http\Controllers\EspecialistaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Plataforma\AgendaController;
use App\Http\Controllers\Plataforma\EspecialidadController;

// Route::get('test', [TestController::class, 'test']);
Route::get('obtenerFechas', [AgendaController::class, 'obtenerFechas']);
Route::post('listar_especialidades', [EspecialidadController::class, 'listar_especialidades']);
Route::get('listar_especialidades_cache', [EspecialidadController::class, 'listar_especialidades_cache']);

Route::post('especialistas_disponibles', [EspecialistaController::class, 'especialistas_disponibles']);
Route::post('horario_especialista', [EspecialistaController::class, 'horario_especialista']);
Route::post('agendamiento', [AgendaController::class, 'agendamiento']);

Route::post('anular_agenda', [AgendaController::class, 'anular_agenda']);
