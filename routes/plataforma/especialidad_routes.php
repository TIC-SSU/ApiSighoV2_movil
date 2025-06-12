<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Plataforma\EspecialidadController;

Route::get('top_especialidades', [EspecialidadController::class, 'top_especialidades'])->name('top_especialidades');
