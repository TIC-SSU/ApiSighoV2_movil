<?php

namespace App\Http\Controllers\Plataforma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;

use App\Services\Plataforma\EspecialidadService;

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
            $request->validate([
                'fecha' => 'required',
                'id_persona_titular' => 'required',
            ]);
            $fecha = $request->input('fecha');
            $id_persona_titular = $request->input('id_persona_titular');
            $especialidades = $this->especialidadService->listar_especialidades($fecha, $id_persona_titular);

            return response()->json([
                'status' => 200,
                'success' => true,
                'data' => $especialidades,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 422,
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
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
