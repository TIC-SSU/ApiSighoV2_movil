<?php

use App\Http\Controllers\Afiliacion\TitularController;
use Illuminate\Support\Facades\Route;

Route::get('/imagen_titular/{id_titular}', [TitularController::class, 'imgan_titular'])->name('imagen_titular');
