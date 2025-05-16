<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Response;

class RedisMiddleware
{
    // /**
    //  * Handle an incoming request.
    //  *
    //  * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
    //  */
    public function handle($request, Closure $next): Response
    {
        // dd('prueba de redis');
        try {
            Redis::ping(); // Intenta hacer ping
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Redis no está disponible',
                'details' => $e->getMessage(),
            ], 503);
        }
        return $next($request);
    }
}
