<?php

namespace App\Http\Controllers\Administracion;

use App\Data\Administracion\ComunicadoDTOData;
use App\Http\Controllers\Controller;
use App\Services\Administracion\ComunicadoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ComunicadoController extends Controller
{
    //
    protected $comunicadoService;

    public function __construct(ComunicadoService $comunicadoService)
    {
        $this->comunicadoService = $comunicadoService;
    }

    public function listar_comunicados(Request $request): JsonResponse
    {
        try {
            $response = $this->comunicadoService->listar_comunicados();
            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => "Peticion Existosa",
                'data' => $response
            ], 200);
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
                'message' => 'Error en comunicadoService',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ], 500);
        }
    }
    public function registrar_comunicado(ComunicadoDTOData $data): JsonResponse
    {
        try {
            $response = $this->comunicadoService->registrar_comunicado($data);
            return response()->json([
                'status' => 201,
                'success' => true,
                'message' => "Registro exitoso",
                'data' => $response
            ], 200);
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
                'message' => 'Error en comunicadoService',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ], 500);
        }
    }
}
