<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Services\Administracion\PersonaService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PersonaController extends Controller
{
    //
    protected $personaService;
    protected $imagenService;

    public function __construct(
        PersonaService $personaService,
        ImageService $imagenService
    ) {
        $this->personaService = $personaService;
        $this->imagenService = $imagenService;
    }

    public function obtenerPoblacionAseguradaCache()
    {
        try {
            $cache = $this->personaService->obtenerPoblacionAseguradaCache();
            // dd($cache);
            if (!$cache) {
                $data = [
                    'status' => 404,
                    'message' => 'No se econtraron datos de cache',
                ];
                return response()->json($data, 404);
            }
            $data = [
                'status' => 200,
                'data' => $cache,
            ];
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            $dataError = [
                'status' => 500,
                'success' => false,
                'message' => 'Error al listar en Poblacion Asegurada',
                'error' => $th->getMessage(), // Mensaje del error
                'line' => $th->getLine(), // L nea donde ocurri el error
                'file' => $th->getFile(), // Archivo donde ocurri el error
            ];
            return response()->json($dataError, 500);
        }
    }
    public function obtener_imagen_persona($id_persona)
    {
        try {
            // dd($id_persona);
            return $this->personaService->obtener_imagen_persona($id_persona);
        } catch (NotFoundHttpException $e) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        } catch (HttpException $e) {
            return response()->json([
                'status' => $e->getStatusCode(),
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getStatusCode());
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Error al obtener imagen',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ], 500);
        }
    }
}
