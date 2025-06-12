<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Services\Administracion\PersonaService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
    public function grupo_familiar($id_persona): JsonResponse
    {
        // $id_persona = $request->input('id_persona');
        try {
            $validator = Validator::make(
                ['id_persona' => $id_persona],
                ['id_persona' => 'required|integer|min:1']
            );

            // Lanzar excepción si la validación falla
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $response = $this->personaService->encontrarPersona($id_persona);
            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => "Peticion Grupo familiar Existosa",
                'data' => $response
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 422,
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors(),
            ], 422);
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
                'message' => 'Error en personaService',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ], 500);
        }
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
    public function obtener_id_titular(Request $request)
    {
        try {
            // dd($id_persona);
            $id_persona = $request->input('id_persona');
            return $this->personaService->obtener_id_titular_login($id_persona);
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
