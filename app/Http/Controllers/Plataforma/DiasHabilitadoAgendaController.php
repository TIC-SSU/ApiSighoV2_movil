<?php

namespace App\Http\Controllers\Plataforma;

use App\Http\Controllers\Controller;
use App\Models\Plataforma\DiasHabilitadosAgenda;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DiasHabilitadoAgendaController extends Controller
{
    //
    public function fechas_habilitadas(Request $request)
    {
        try {
            Carbon::setLocale('es');
            $fechasElegibles = [];
            $fechasDisponibles = DiasHabilitadosAgenda::where('id_servicio_plataforma', 1)->first();
            // return response()->json($fechasDisponibles);
            if (!$fechasDisponibles) {
                return response()->json([
                    'success' => false,
                    'status' => 410,
                    'message' => 'No se econtro habilitado para el servicio APK movil',
                ]);
            }
            if ($fechasDisponibles->estado) {
                $fechaInicio = Carbon::now();
                for ($i = 0; $i < $fechasDisponibles->nro_dias; $i++) {
                    $fechasElegibles[$i]['fecha_sin_formato'] = Carbon::parse($fechaInicio)->format('Y-m-d');
                    $fechasElegibles[$i]['fecha_con_formato'] = mb_strtoupper($fechaInicio->translatedFormat('l, d \\d\\e F \\d\\e Y'));
                    $fechaInicio = $fechaInicio->addDays(1);
                }
            } else {
                $fechaInicio = Carbon::now();
                for ($i = 0; $i < $fechasDisponibles->nro_dias; $i++) {
                    $fechaInicio = $fechaInicio->addDays(1);
                    $fechasElegibles[$i]['fecha_sin_formato'] = Carbon::parse($fechaInicio)->format('Y-m-d');
                    $fechasElegibles[$i]['fecha_con_formato'] = mb_strtoupper($fechaInicio->translatedFormat('l, d \\d\\e F \\d\\e Y'));
                }
            }
            // dd($fechasElegibles);
            // $this->var = 4;
            $fechas  = $fechasElegibles;
            if (!$fechas) {
                $data = [
                    'success' => false,
                    'status' => 404,
                    'message' => 'No se econtraron fechas para elegir',
                ];
                return response()->json($data, 404);
            }
            $data = [
                'success' => true,
                'status' => 200,
                'fechas_habilitadas' => $fechas,

            ];
            return response()->json($data, 200);
        } catch (\Throwable $th) {
            $dataError = [
                'success' => false,
                'message' => 'Error al listar Especialidades',
                'error' => $th->getMessage(), // Mensaje del error
                'line' => $th->getLine(), // L nea donde ocurri el error
                'file' => $th->getFile(), // Archivo donde ocurri el error
            ];
            return response()->json($dataError, 500);
        }
    }
}
