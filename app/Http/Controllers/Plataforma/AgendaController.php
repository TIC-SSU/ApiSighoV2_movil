<?php

namespace App\Http\Controllers\Plataforma;

use App\Http\Controllers\Controller;
use App\Services\Plataforma\AgendaService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgendaController extends Controller
{
    //
    protected $agendaService;

    public function __construct(AgendaService $agendaService)
    {
        $this->agendaService = $agendaService;
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
