<?php

namespace App\Listeners;

use App\Events\VerificarCachePoblacionAsegurada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use App\Models\Administracion\Especialidad;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class VerificarCacheEspecialidadesListener
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
        $key = $event->cacheKey2;
        Log::info('Confirmación, Cache Creado de : ' . $key . ' a las: ' . Carbon::now());
        $array_collect = Cache::remember($key, now()->addMinutes(60), function () {
            $fechaActual = Carbon::now()->format('Y-m-d');

            $especialidades = Especialidad::with(['especialidadHabilitadoServicioEspecialidad' => function ($query) use ($fechaActual) {
                $query->where('id_servicio', 2)
                    ->where('estado', true)
                    ->where(function ($unQuericito) use ($fechaActual) {
                        $unQuericito->whereNull('fecha_inicio')
                            ->orWhereDate('fecha_inicio', '<=', $fechaActual);
                    })
                    ->where(function ($otroQuericito) use ($fechaActual) {
                        $otroQuericito->whereNull('fecha_fin')
                            ->orWhereDate('fecha_fin', '>=', $fechaActual);
                    });
            }])
                ->where('estado', true)
                ->get();

            $listaEspecialidadesHabilitadas = $especialidades->flatMap(function ($especialidad) {
                return $especialidad->especialidadHabilitadoServicioEspecialidad->map(function ($servicio) use ($especialidad) {
                    return [
                        'id_especialidad' => $especialidad->id,
                        'especialidad' => $especialidad->especialidad,
                        'sigla' => $especialidad->sigla,
                        'estado' => $especialidad->estado,
                        'fecha_inicio' => $servicio->fecha_inicio,
                        'fecha_fin' => $servicio->fecha_fin,
                        'id_servicio' => $servicio->id_servicio,
                        'permanente' => $servicio->permanente,
                        'estudiantes' => $especialidad->estudiantes,
                        'estado_habilitado_servicio' => $servicio->estado,
                    ];
                });
            })->values()->all();

            return $listaEspecialidadesHabilitadas;
        });
    }
}
