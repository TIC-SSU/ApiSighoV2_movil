<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    //
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function obtener_imagen_usuario(Request $request, $id_user)
    {
        try {
            if (! URL::hasValidSignature($request)) {
                return response()->json([
                    'status' => 403,
                    'success' => false,
                    'message' => 'Enlace inválido o expirado',
                ], 403);
            }

            return $this->userService->obtener_imagen_usuario($id_user);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                'status' => $e->getStatusCode(),
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        } catch (HttpException $e) {
            return response()->json([
                'status' => $e->getStatusCode(),
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Error en userService',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ], 500);
        }
    }
}
