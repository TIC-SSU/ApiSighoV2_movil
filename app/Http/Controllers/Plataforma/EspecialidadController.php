<?php

namespace App\Http\Controllers\Plataforma;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Plataforma\EspecialidadService;
use Illuminate\Http\Request;
use App\Services\UserService;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EspecialidadController extends Controller
{
    //
    protected $especialidadService;

    public function __construct(EspecialidadService $especialidadService)
    {
        $this->especialidadService = $especialidadService;
    }
    public function listar_especialidades(Request $request)
    {
        try {
            $fecha = $request->input('fecha');
            $especialidades = $this->especialidadService->listar_especialidades($fecha);

            return response()->json([
                'success' => true,
                'data' => $especialidades,
            ]);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                'status' => 404,
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
                'message' => 'Error al listar especialidades',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ], 500);
        }
    }
    public function listar_especialidades_cache()
    {
        try {
            return $this->especialidadService->obtenerEspecialidadesCache();
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
                'message' => 'Error al listar especialidades',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ], 500);
        }
    }
}
