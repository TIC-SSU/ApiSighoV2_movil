<?php

use App\Http\Controllers\EspecialistaController;
use Illuminate\Support\Facades\Route;

Route::get('imagen_especialista/{id_especialista}', [EspecialistaController::class, 'imagen_especialista'])
    ->name('imagen_especialista');
