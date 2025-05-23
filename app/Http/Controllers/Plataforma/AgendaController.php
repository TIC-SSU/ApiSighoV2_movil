<?php

namespace App\Http\Controllers\Plataforma;

use App\Http\Controllers\Controller;
use App\Services\Plataforma\AgendaService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;

class AgendaController extends Controller
{
    //
    protected $agendaService;

    public function __construct(AgendaService $agendaService)
    {
        $this->agendaService = $agendaService;
    }
    public function agendamiento(Request $request): JsonResponse
    {
        try {
            $id_persona = $request->input('id_persona');
            $id_user = $request->input('id_user');
            $fechaElegida = $request->input('fechaElegida');
            $horaElegida = $request->input('horaElegida');
            $id_asignacion_horarioElegido = $request->input('id_asignacion_horarioElegido');
            $ip = $request->input('ip');
            $id_persona_titular = $request->input('id_persona_titular');
            $id_especialistaElegido = $request->input('id_especialistaElegido');
            $response = $this->agendaService->agendaWebConfirmada(
                $id_persona,
                $id_user,
                $fechaElegida,
                $horaElegida,
                $id_asignacion_horarioElegido,
                $ip,
                $id_persona_titular,
                $id_especialistaElegido,
            );
            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => "Agenda agregada exitosamente",
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
                'message' => 'Error al registrar agenda',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ], 500);
        }
    }

    public function anular_agenda(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id_agenda' => 'required|integer',
            ]);

            $response = $this->agendaService->anular_agenda($validated['id_agenda']);

            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => "Petición exitosa",
                'data' => $response,
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
                'message' => 'Error en agendaService',
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ], 500);
        }
    }
    public function obtenerFechas(): JsonResponse
    {
        try {
            $fechas = $this->agendaService->obtenerFechasDisponibles();

            if (is_null($fechas)) {
                return response()->json([
                    'success' => false,
                    'status' => 404,
                    'message' => 'No hay fechas disponibles para este servicio.',
                    'code' => 'FECHAS_NO_DISPONIBLES',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Fechas disponibles para este servicio.',
                'data' => $fechas
            ], 200);
        } catch (Exception $e) {
            // Loguear el error
            Log::error('Error al obtener las fechas: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado. Por favor, intente más tarde.',
                'error' => $e->getMessage()
            ], 500); // HTTP 500 - Internal Server Error
        }
    }

    // public static function fecha_agenda_ocupada_familiar($fecha_agenda, $id_persona)
    // {
    //     $verificacionAgenda = new GrupoFamiliarPersona();
    //     $countAgendas = 0;
    //     $grupoFam = $verificacionAgenda->encontrarPersona($id_persona);

    //     foreach ($grupoFam as $key => $fam) {
    //         if (
    //             Agenda::where('fecha_agenda', $fecha_agenda)
    //             ->where('anulacion_ficha', false)
    //             ->where('id_persona', $fam['id'])
    //             ->exists()
    //         ) {
    //             $countAgendas++;
    //         }
    //     }
    //     if ($countAgendas == 0) {
    //         return false;
    //     }
    //     return true;
    // }
}
