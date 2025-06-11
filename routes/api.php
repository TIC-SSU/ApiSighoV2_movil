<?php

use App\Http\Controllers\Administracion\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::prefix('auth')->group(function () {
    // Rutas sin autenticación
    Route::post('login', [AuthController::class, 'login']);
    // Route::post('register', [AuthController::class, 'register']);
});
// // Route::middleware(['api'])->group(function () {
// Route::prefix('auth')->group(function () {
//     // Rutas sin autenticación
//     Route::post('login', [AuthController::class, 'login']);
//     // Route::post('register', [AuthController::class, 'register']);
// });
// Rutas protegidas con JWT /* 'redis', */
// Route::get('api/administracion/obtener_imagen_usuario/{id_user}', [UserController::class, 'obtener_imagen_usuario'])
//     ->name('obtener_imagen_usuario');
Route::middleware(['auth.jwt'])->group(function () {

    Route::prefix('testing')->group(function () {
        require base_path('routes/test/test_routes.php');
    });

    // Route::get('test_token', [TestTokenController::class, 'testToken']);

    Route::prefix('administracion')->group(function () {
        require base_path('routes/administracion/persona_routes.php');
        require base_path('routes/administracion/user_routes.php');
        // require base_path('routes/administracion/especialidad_routes.php');
    });

    // Route::prefix('afiliacion')->group(function () {});

    // Route::prefix('aportes')->group(function () {});

    Route::prefix('plataforma')->group(function () {
        require base_path('routes/plataforma/agenda_routes.php');
    });
    Route::prefix('auth')->group(function () {
        // Rutas sin autenticación
        Route::post('logout', [AuthController::class, 'logout']);
        // Route::post('register', [AuthController::class, 'register']);
    });
});
