<?php

namespace App\Listeners;

use App\Events\VerificarCachePoblacionAsegurada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use App\Models\Plataforma\AsignacionHorario;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class VerificarCacheHorariosEspecialistasListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VerificarCachePoblacionAsegurada $event): void
    {
        //
        $key = $event->cacheKey4;
        Log::info('Confirmación, Cache Creado de : ' . $key . ' a las: ' . Carbon::now());
        $array_collect = Cache::remember($key, now()->addMinutes(60), function () {
            $fechaActual = Carbon::now()->format('Y-m-d');
            $asignacionesHorarios = AsignacionHorario::with([
                'horarioAsignacionHorario',
                'consultorioAsignacionHorario.consultorioSedes.residenciaSedes.zonaResidencia',
            ])
                ->where(function ($quericito) use ($fechaActual) {
                    $quericito->whereNull('fecha_inicio')
                        ->orWhereDate('fecha_inicio', '<=', $fechaActual);
                })
                ->where(function ($otroQuericito) use ($fechaActual) {
                    $otroQuericito->whereNull('fecha_fin')
                        ->orWhereDate('fecha_fin', '>=', $fechaActual);
                })
                ->where('estado', true)
                ->get();

            $listaHorarios = $asignacionesHorarios->map(function ($asignacion) {
                $horario = $asignacion->horarioAsignacionHorario;
                $consultorio = $asignacion->consultorioAsignacionHorario;
                $sede = $consultorio?->consultorioSedes;
                $residencia = $sede?->residenciaSedes;
                $zona = $residencia?->zonaResidencia;

                return [
                    'id_asignacion_horario' => $asignacion->id,
                    'id_especialista' => $asignacion->id_especialista,
                    'fecha_inicio' => $asignacion->fecha_inicio,
                    'fecha_fin' => $asignacion->fecha_fin,
                    'id_dia' => $asignacion->id_dia,
                    'id_horario' => $asignacion->id_horario,
                    'hora_inicio' => $horario->hora_inicio,
                    'hora_fin' => $horario->hora_fin,
                    'intervalo' => $horario->intervalo,
                    'numero_paciente' => $horario->numero_paciente,
                    'turno' => $horario->turno,
                    'id_consultorio' => $asignacion->id_consultorio,
                    'numero_consultorio' => $consultorio->numero_consultorio,
                    'id_sedes' => $consultorio->id_sedes,
                    'ubicacion' => $sede->ubicacion,
                    'piso' => $sede->piso,
                    'id_residencia' => $sede->id_residencia,
                    'direccion' => $residencia->direccion,
                    'nro_vivienda' => $residencia->nro_vivienda,
                    'id_zona' => $residencia->id_zona,
                    'zona' => $zona->nombre,
                ];
            })->values()->all();

            return $listaHorarios;
        });
    }
}
