<?php

namespace App\Listeners;

use App\Events\VerificarCachePoblacionAsegurada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Models\Administracion\Persona;
use  App\Models\Plataforma\Especialista;

class VerificarCacheEspecialistasListener
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
        $key = $event->cacheKey3;
        Log::info('Confirmación, Cache Creado de : ' . $key . ' a las: ' . Carbon::now());
        $array_collect = Cache::remember($key, now()->addMinutes(60), function () {
            $fechaActual = Carbon::now()->format('Y-m-d');

            $especialistas = Especialista::with([
                'personaEspecialista',
                'especialidadEspecialista',
                'suspensionVacacionEspecialista',
                'especialistaHabilitadoEspecialista'
            ])
                ->where('estado', true)
                ->where(function ($quericito) {
                    $quericito->whereDoesntHave('suspensionVacacionEspecialista')
                        ->orWhereHas('suspensionVacacionEspecialista', function ($otroQuery) {
                            $otroQuery->whereNot('estado', 'VACACION ACTIVA');
                        });
                })
                ->get();

            $listaEspecialistas = $especialistas->filter(function ($especialista) use ($fechaActual) {
                return $especialista->especialistaHabilitadoEspecialista->filter(function ($servicio) use ($fechaActual) {
                    return $servicio->id_servicio === 2 && $servicio->estado &&
                        ($servicio->permanente || ($servicio->fecha_inicio <= $fechaActual && $servicio->fecha_fin >= $fechaActual));
                })->isNotEmpty();
            })->map(function ($especialista) {
                $foto = $this->obtenerFotoEspecialista($especialista->foto);
                $persona = $especialista->personaEspecialista;
                $especialidad = $especialista->especialidadEspecialista;

                return [
                    'id_especialista' => $especialista->id,
                    'nombres' => $persona->nombres,
                    'p_apellido' => $persona->p_apellido,
                    's_apellido' => $persona->s_apellido,
                    'carnet' => $persona->complemento !== null ? $persona->ci . ' - ' . $persona->complemento : $persona->ci,
                    'id_especialidad' => $especialidad->id,
                    'especialidad' => $especialidad->especialidad,
                    'afiliados' => $especialista->afiliados,
                    'estudiantes' => $especialista->estudiantes,
                    'convenios' => $especialista->convenios,
                    'foto' => $foto,
                ];
            })->values()->all();

            return $listaEspecialistas;
        });
    }
    private function obtenerFotoEspecialista($idFoto)
    {
        try {
            if ($idFoto) {
                $path = 'Plataforma/Especialista/Fotos/' . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);
                $foto = base64_encode($imageData);
                if ($foto === '') {
                    return null;
                } else {
                    return $foto;
                }
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }
}
