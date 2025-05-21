<?php

namespace App\Http\Controllers;

use App\Models\Plataforma\Especialista;
use App\Services\Plataforma\EspecialistaService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EspecialistaController extends Controller
{
    //
    protected $especialistaService;

    public function __construct(EspecialistaService $especialistaService)
    {
        $this->especialistaService = $especialistaService;
    }
    public function especialistas_disponibles(Request $request)
    {
        try {
            $fecha = $request->input('fecha');
            $id_especialidad = $request->input('id_especialidad');
            $tipo_asegurado = $request->input('tipo_asegurado');
            $response = $this->especialistaService->especialistas_disponibles($id_especialidad, $fecha, $tipo_asegurado);
            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => "Espepecialistas habilitados",
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
                'message' => 'Error al listar especialidades',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ], 500);
        }
    }

    public function horario_especialista(Request $request)
    {
        try {
            $fecha_elegida = $request->input('fecha_elegida');
            $id_asignacion_horario = $request->input('id_asignacionHorario');
            $id_especialista = $request->input('id_especialista');
            $response = $this->especialistaService->especialistaSeleccionado($id_especialista, $id_asignacion_horario, $fecha_elegida);
            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => "Espepecialista horarios habilitados",
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
                'message' => 'Error al listar especialidades',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ], 500);
        }
    }
}
