<?php

use App\Http\Controllers\EspecialistaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Plataforma\AgendaController;
use App\Http\Controllers\Plataforma\EspecialidadController;

// Route::get('test', [TestController::class, 'test']);
// PASO 1
Route::get('obtenerFechas', [AgendaController::class, 'obtenerFechas']);
// PASO 2
Route::post('listar_especialidades', [EspecialidadController::class, 'listar_especialidades']);
Route::get('listar_especialidades_cache', [EspecialidadController::class, 'listar_especialidades_cache']);
// PASO 3
Route::post('especialistas_disponibles', [EspecialistaController::class, 'especialistas_disponibles']);
// PASO 4
Route::post('horario_especialista', [EspecialistaController::class, 'horario_especialista']);
// PASO 5
Route::post('agendamiento', [AgendaController::class, 'agendamiento']);

Route::post('anular_agenda', [AgendaController::class, 'anular_agenda']);
Route::post('obtener_agenda_persona', [AgendaController::class, 'obtener_agenda_persona']);

Route::get('especialistas_datos', [EspecialistaController::class, 'especialistas_datos']);
// --------------- PRUEBAS ------------------------
Route::get('contar_horario', [EspecialistaController::class, 'contar_horario'])->name('contar_horario');
