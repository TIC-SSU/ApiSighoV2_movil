<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EspecialistaController;

Route::get('imagen_especialista/{id_especialista}', [EspecialistaController::class, 'imagen_especialista'])
    ->name('imagen_especialista');
Route::get('top_especialistas', [EspecialistaController::class, 'top_especialistas'])->name('top_especialistas');
