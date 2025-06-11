<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTMiddleware
{
    protected $exceptRoutes = [
        'api/administracion/obtener_imagen_usuario/*',
        // 'api/publico/descargar_documento/*',
        // 'api/sin-token/*', // Puedes agregar todas las que quieras aquí
    ];
    public function handle($request, Closure $next)
    {

        foreach ($this->exceptRoutes as $route) {
            if ($request->is($route)) {
                return $next($request);
            }
        }

        try {
            JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return response()->json([
                'status' => 401,
                'success' => true,
                'message' => 'Token expirado'
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'status' => 401,
                'success' => true,
                'message' => 'Token invalido'
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 401,
                'success' => true,
                'message' => 'Token no encontrado'
            ], 401);
        }
        return $next($request);
    }
}
