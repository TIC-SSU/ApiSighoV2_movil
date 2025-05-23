<?php

namespace App\Services\Plataforma;

use App\Models\Plataforma\Agenda;
use App\Models\Plataforma\DiasHabilitadosAgenda;
use App\Models\Plataforma\Especialista;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AgendaService
{
    protected $especialistaService;

    public function __construct(EspecialistaService $especialistaService)
    {
        $this->especialistaService = $especialistaService;
    }
    public function obtenerFechasDisponibles()
    {
        try {
            Carbon::setLocale('es');
            $fechasDisponibles = DiasHabilitadosAgenda::where('estado', true)
                ->where('id_servicio_plataforma', 2)
                ->first();

            $fechasElegibles = [];
            $diasHabilitadosAgenda = $fechasDisponibles->nro_dias;
            $agregarHoy = $fechasDisponibles->hoy;
            $fecha = Carbon::now();
            if (!$agregarHoy) {
                $fecha->addDay();
            }

            while (count($fechasElegibles) < $diasHabilitadosAgenda) {
                if ($fecha->dayOfWeek !== 0) {
                    $fechasElegibles[] = [
                        'fecha' => $fecha->format('Y-m-d'),
                        'fecha_literal' => mb_strtoupper($fecha->isoFormat('dddd'))
                    ];
                }
                $fecha->addDay();
            }
            return $fechasElegibles;
        } catch (Exception $e) {
            Log::error('Error obteniendo fechas disponibles: ' . $e->getMessage());
            throw $e;  // o false si prefieres
        }
    }

    public function obtener_agenda_persona($id_persona)
    {
        $datos = Agenda::where('id_persona', $id_persona)->where('anulacion_ficha', false)
            ->with([
                'asignacionHorarioAgenda.especialistaAsignacionHorario.personaEspecialista',
                'asignacionHorarioAgenda.especialistaAsignacionHorario.especialidadEspecialista',
                'asignacionHorarioAgenda.consultorioAsignacionHorario',
                // 'asignacionHorarioAgenda.consultorioAsignacionHorario.zonaConsultorio',
                'asignacionHorarioAgenda.consultorioAsignacionHorario.consultorioSedes',
            ])
            ->orderBy('fecha_agenda', 'desc')
            ->get();
        if ($datos->isEmpty()) {
            abort(404, 'No se econtraron datos en Agenda');
        }
        return $datos;
    }


    public function anular_agenda(int $id_agenda)
    {
        // if (!$id_agenda) {
        //     abort(400, 'Faltan parámetros obligatorios. id_agenda');
        // }
        $agenda = Agenda::find($id_agenda);
        if (!$agenda) {
            abort(404, 'No se encontró el registro en Agenda');
        }
        $clave_unica = $agenda->clave_unica;
        $fecha_anulacion = Carbon::now()->format('Y-m-d');  // Obtener la fecha actual
        // $hora_agenda = $agenda->hora_agenda;
        $hora_actual = Carbon::now()->format('H:i:s');  // Obtener la hora actual
        $agenda->update([
            'clave_unica' => $clave_unica . "-A-" . $hora_actual,
            'fecha_anulacion' => $fecha_anulacion,
            'anulacion_ficha' => true
        ]);
        return $agenda;
    }

    // AGENDAMIENTO CONFIRMADO
    public function agendaWebConfirmada(
        $id_persona,
        $id_user,
        $fechaElegida,
        $horaElegida,
        $id_asignacion_horarioElegido,
        $ip,
        $id_persona_titular,
        $id_especialistaElegido
    ) {
        // dd(
        //     $id_persona,
        //     $id_user,
        //     $fechaElegida,
        //     $horaElegida,
        //     $id_asignacion_horarioElegido,
        //     $ip,
        //     $id_persona_titular,
        //     $id_especialistaElegido
        // );
        // id_asignacion_horarioElegido es el id_asginacion_horario
        $horaC = Carbon::parse($horaElegida)->format('H');
        $minutosC = Carbon::parse($horaElegida)->format('i');
        $diaC = Carbon::parse($fechaElegida)->format('d');
        $mesC = Carbon::parse($fechaElegida)->format('m');
        $gestionC = Carbon::parse($fechaElegida)->format('Y');
        $clave_unica = $id_asignacion_horarioElegido . $horaC . $minutosC . $diaC . $mesC . $gestionC;

        $verificarAgenda = Agenda::where('clave_unica', $clave_unica)->first();

        if ($this->existeGrupoFamiliarAgendado($fechaElegida, $id_persona_titular)) {
            abort(404, 'Ya existe un familar agendado en la fecha');
        }

        if ($verificarAgenda) {
            $this->especialistaService->especialistaSeleccionado($id_especialistaElegido, $id_asignacion_horarioElegido, $fechaElegida);
            abort(404, 'Ya existe una agena en este horario');
        } else {

            if ($this->existeGrupoFamiliarAgendado($fechaElegida, $id_persona_titular)) {
                abort(404, 'Ya existe un familar agendado en la fecha');
            }

            $nuevaAgendaWeb = Agenda::create([
                'fecha_agenda' => $fechaElegida,
                'hora_agenda' => $horaElegida,
                'confirmado' => true,
                'id_servicio_plataforma' => 2,
                'id_persona' => $id_persona,
                'id_asignacion_horario' => $id_asignacion_horarioElegido,
                'clave_unica' => $clave_unica,
                'ip' => $ip,
                'id_user_created' => $id_user,
            ]);

            if ($nuevaAgendaWeb) {
                // $agenda = $this->especialistaService->especialistaSeleccionado($id_especialistaElegido, $id_asignacion_horarioElegido, $fechaElegida);
                return $nuevaAgendaWeb;
            } else {
                abort(422, 'error al registrar la agenda');
            }
        }
    }
    public function existeGrupoFamiliarAgendado($fechaSeleccionada, $idPersonaTitular)
    {
        // $this->obtenerPoblacionAseguradaCache();
        $poblacion_asegurada_cache = Cache::get('poblacion_asegurada');
        $persona = collect($poblacion_asegurada_cache)->where('id_persona_titular', $idPersonaTitular);
        $grupoFamiliar = $persona->pluck('id_persona')->all();

        if (empty($grupoFamiliar)) {
            return false;
        }

        $existe = Agenda::where('fecha_agenda', $fechaSeleccionada)
            ->whereIn('id_persona', $grupoFamiliar)
            ->where('ficha_extra', false)
            ->where('anulacion_ficha', false)
            ->exists();

        return $existe;
    }
    private function obtenerImagenBase64($idFoto, $liga)
    {
        try {
            if ($idFoto) {
                $path = $liga . $idFoto;

                $imageData = Storage::disk('ftp')->get($path);
                $foto_nombre = base64_encode($imageData);
                if ($foto_nombre === '') {
                    return null;
                } else {
                    return $foto_nombre;
                }
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }
    }

    // pasoa para suspencion de Agenda

    // // ----------------------------------------
    // //PASOS
    // public $paso = 1;
    // // Add your service logic here
    // public function obtenerFechasDisponibles_old(): ?array
    // {
    //     try {
    //         $fechasDisponibles = DiasHabilitadosAgenda::where('estado', true)
    //             ->where('id_servicio_plataforma', 2)
    //             ->first();

    //         if (!$fechasDisponibles) {
    //             return null;
    //         }

    //         $fechasElegibles = [];

    //         for ($i = 0; $i < $fechasDisponibles->nro_dias; $i++) {
    //             $fecha = $fechasDisponibles->hoy
    //                 ? Carbon::now()->addDays($i)
    //                 : Carbon::now()->addDays($i + 1);

    //             $fechasElegibles[] = $fecha->format('Y-m-d');
    //         }

    //         return $fechasElegibles;
    //     } catch (Exception $e) {
    //         Log::error('Error obteniendo fechas disponibles: ' . $e->getMessage());
    //         throw $e;  // o false si prefieres
    //     }
    // }
    // protected function obtenerPoblacionAseguradaCache()
    // {
    //     $this->poblacion_asegurada_cache = Cache::get('poblacion_asegurada');
    // }

    // protected function obtenerEspecialidadesCache()
    // {
    //     $this->especialidades_cache = Cache::get('especialidades');
    // }

    // protected function obtenerEspecialistasCache()
    // {
    //     $this->especialistas_cache = Cache::get('especialistas');
    // }

    // protected function obtenerHorariosCache()
    // {
    //     $this->horarios_cache = Cache::get('horarios');
    // }

    // public function obtenerDatosPersona(int $id_persona)
    // {
    //     $this->obtenerPoblacionAseguradaCache();
    //     $persona = collect($this->poblacion_asegurada_cache)->firstWhere('id_persona', $id_persona);
    //     $this->fecha_nacimiento = $persona['fecha_nacimiento'];
    //     $this->nombre_completo = implode(' ', [$persona['nombres'], $persona['p_apellido'], $persona['s_apellido']]);
    //     $this->matricula = $persona['matricula_seguro'];
    //     $this->tipo_asegurado = $persona['tipo_asegurado'];
    //     $this->foto_nombre = $this->obtenerImagenBase64($persona['foto'], $persona['liga']);
    // }

}
