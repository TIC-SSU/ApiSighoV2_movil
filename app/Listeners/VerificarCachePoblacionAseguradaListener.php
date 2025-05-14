<?php

namespace App\Listeners;

use App\Events\VerificarCachePoblacionAsegurada;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Models\Administracion\Persona;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class VerificarCachePoblacionAseguradaListener
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


        // Log::info('¡Prueba de reinicio de logs!');
        $key = $event->cacheKey1;

        Log::info('Confirmación, Cache Creado de : ' . $key . ' a las: ' . Carbon::now());
        $array_collect = Cache::remember($key, now()->addMinutes(60), function () {
            $arrayDatos = [];

            $consultaPersona = Persona::whereNot('id_tipo_asegurado', 0)
                ->where('afiliado', true)
                ->with([
                    'tipoAseguradoPersona',
                    'PersonaTitular',
                    'PersonaBeneficiario.titularBeneficiario',
                    'autorizacionInteriorPersonaInterior',
                    'convenioPersona',
                    'PersonaEstudiante',
                ])
                ->orderBy('id', 'asc')
                ->get();

            foreach ($consultaPersona as $persona) {
                $idPersonaTitular = 0;
                $tipoAsegurado = $persona->tipoAseguradoPersona->tipo_asegurado ?? null;

                switch ($tipoAsegurado) {
                    case 'TITULAR':
                        $titular = $persona->PersonaTitular->where('estado', true)->first();
                        $idPersonaTitular = $titular->id_persona ?? 0;
                        break;

                    case 'BENEFICIARIO DEL INTERIOR':
                        $autorizacionInterior = $persona->autorizacionInteriorPersonaInterior->where('estado', true)->first();
                        $idPersonaTitular = $autorizacionInterior->id_persona_titular ?? 0;
                        break;

                    case (Str::contains($tipoAsegurado, 'BENEFICIARIO')):
                        $beneficiario = $persona->PersonaBeneficiario->where('estado', true)->first();
                        $idPersonaTitular = $beneficiario->titularBeneficiario->id_persona ?? 0;
                        break;

                    case 'TITULAR DEL INTERIOR':
                        $autorizacionInteriorTitular = $persona->autorizacionInteriorPersonaInterior->where('estado', true)->first();
                        $idPersonaTitular = $autorizacionInteriorTitular->id_persona_titular ?? 0;
                        break;

                    case 'TITULAR CONVENIO':
                        $convenio = $persona->convenioPersona->where('estado', true)->first();
                        $idPersonaTitular = $convenio->id_persona ?? 0;
                        break;

                    case 'ESTUDIANTE':
                        $estudiante = $persona->PersonaEstudiante
                            ->where('estado_vigencia', true)
                            ->where('estado', true)
                            ->where('estado_validar', 'AFILIADO')
                            ->first();
                        $idPersonaTitular = $estudiante->id_persona ?? 0;
                        break;

                    default:
                        $idPersonaTitular = 0;
                        break;
                }

                $arrayDatos[] = [
                    'id_persona' => $persona->id,
                    'nombres' => $persona->nombres,
                    'p_apellido' => $persona->p_apellido,
                    's_apellido' => $persona->s_apellido,
                    'matricula_seguro' => $persona->matricula_seguro,
                    'sexo' => $persona->sexo,
                    'fecha_nacimiento' => $persona->fecha_nacimiento,
                    'ci' => $persona->ci,
                    'complemento' => $persona->complemento,
                    'id_tipo_asegurado' => $persona->id_tipo_asegurado,
                    'tipo_asegurado' => $tipoAsegurado,
                    'excepcion' => $persona->excepcion,
                    'id_persona_titular' => $idPersonaTitular,
                ];
            }

            return $arrayDatos;
        });
    }
}
