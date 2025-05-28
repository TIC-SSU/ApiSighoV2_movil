<?php

namespace App\Services\Plataforma;

use App\Models\Plataforma\Agenda;
use App\Models\Plataforma\Especialista;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EspecialistaService
{
    // public $identificadorTipoAsegurado;

    // //AGENDA COMO TAL
    // public $fechaElegida;
    // public $diaElegido;
    // public $especialidadesDisponibles = [];
    // public $especialistasDisponibles = [];
    // public $horariosDisponibles = [];
    // public $listaHorarios = [];
    // public $horaActualParaVerificacion;
    // public $switchMismoDia;
    // public $especialistaElegido;
    // public $horarioElegido;
    // public $horaElegida;
    // public $edadGeriatria;
    // public $edadPediatria;
    // Add your service logic here

    public function horarios_mas_citas(): int
    {
        $repeticiones = Agenda::select('id_asignacion_horario', DB::raw('COUNT(id_asignacion_horario) as rep'))
            ->groupBy('id_asignacion_horario')
            ->orderByDesc('rep')
            ->pluck('id_asignacion_horario')
            ->toArray();
        return $repeticiones;
    }
    public function top_especialistas()
    {
        $topEspecialistas = Especialista::with([
            'persona',
            'especialidadEspecialista',
            'asignacionHorarioEspecialista.consultorioAsignacionHorario.consultorioSedes',
            'asignacionHorarioEspecialista.horarioAsignacionHorario',
        ]) // si quieres traer datos de persona
            ->withCount('agendas')                         // cuenta total de agendas relacionadas
            ->orderByDesc('agendas_count')                 // ordenar por cantidad de agendas
            ->limit(5)
            ->get();
        return $topEspecialistas;
    }
    public function obtenerEspecialistasCache()
    {
        $especialistas_cache = Cache::get('especialistas');
        return $especialistas_cache;
    }
    public function obtenerHorariosCache()
    {
        $horarios_cache = Cache::get('horarios');
        return $horarios_cache;
    }
    public function especialistas_datos()
    {
        $datos = Especialista::with(['personaEspecialista'])->get();
        if ($datos->isEmpty()) {
            abort(404, 'no se encontro especialistas');
        }
        return $datos;
    }
    public function especialistas_disponibles($id_especialidad, $fechaElegida, $tipo_asegurado)
    {
        if (!$id_especialidad || !$fechaElegida || !$tipo_asegurado) {
            abort(400, 'Faltan parámetros obligatorios.');
        }

        try {
            $fechaElegida = Carbon::parse($fechaElegida)->format('Y-m-d');
        } catch (\Exception $e) {
            abort(400, 'Fecha inválida.');
        }

        $diaElegido = Carbon::parse($fechaElegida)->dayOfWeek;

        $especialistas_cache = Cache::get('especialistas');
        $horariosPorFecha = Cache::get('horarios');
        if (!$especialistas_cache) {
            abort(404, 'No se encontraron especialistas en caché.');
        }
        // dd($especialistas_cache);
        $especialistas = collect($especialistas_cache);

        $especialistasFiltrados = $especialistas->filter(function ($esp) use ($id_especialidad, $fechaElegida, $tipo_asegurado) {
            if ($esp['id_especialidad'] !== $id_especialidad) return false;

            $contrato_valido = ($esp['fecha_contrato_inicio'] <= $fechaElegida || $esp['permanente']) &&
                ($esp['fecha_contrato_fin'] >= $fechaElegida || $esp['permanente']);

            if (!$contrato_valido) return false;

            if (empty($esp['especialistaHabilitado'])) return false;

            if ($tipo_asegurado === 1 && !$esp['afiliados']) return false;
            if ($tipo_asegurado === 2 && !$esp['convenios']) return false;
            if ($tipo_asegurado === 3 && !$esp['estudiantes']) return false;

            return true;
        });
        // dd($especialistasFiltrados);
        $especialistasDisponibles = collect();

        foreach ($especialistasFiltrados as $especialista) {
            $habilitacionesActivas = collect($especialista['especialistaHabilitado'])->filter(function ($hab) use ($diaElegido, $fechaElegida) {
                return $hab['id_dia'] === $diaElegido &&
                    (($hab['fecha_inicio'] <= $fechaElegida || $hab['permanente']) &&
                        ($hab['fecha_fin'] >= $fechaElegida || $hab['permanente']));
            });

            if ($habilitacionesActivas->isNotEmpty()) {
                $especialistasDisponibles->push([
                    'id_especialista' => $especialista['id_especialista'],
                    'foto' => $especialista['foto'],
                    'nombres' => implode(' ', [
                        $especialista['p_apellido'],
                        $especialista['s_apellido'],
                        $especialista['nombres'],
                    ]),
                    'grado_academico' => $especialista['grado_academico'] === 'DOCTORA' ? 'DRA.' : ($especialista['grado_academico'] === 'DOCTOR' ? 'DR.' : 'LIC.'),
                ]);
            }
        }
        // dd($especialistasDisponibles);
        // === PASO EXTRA: Verificación de horarios y fichas ===
        $horariosDisponibles = [];

        foreach ($especialistasDisponibles as $especialistaDisponible) {
            foreach ($horariosPorFecha as $horario) {
                if (
                    $horario['id_dia'] === $diaElegido &&
                    (($horario['fecha_inicio'] <= $fechaElegida) &&
                        ($horario['fecha_fin'] >= $fechaElegida || $horario['fecha_fin'] === null)) &&
                    $horario['id_especialista'] === $especialistaDisponible['id_especialista']
                ) {
                    $verificacionAgendas = Agenda::where('id_asignacion_horario', $horario['id_asignacion_horario'])
                        ->where('fecha_agenda', $fechaElegida)
                        ->where('anulacion_ficha', false)
                        ->where('ficha_extra', false)
                        ->get()
                        ->groupBy('id_asignacion_horario');

                    $agendasVerificadas = $verificacionAgendas->get($horario['id_asignacion_horario'], collect());
                    $ocupadas = $agendasVerificadas->where('id_suspension_horario', null)->count();
                    $suspendidas = $agendasVerificadas->where('id_suspension_horario', '!=', null)->count();

                    $horariosDisponibles[] = [
                        "id_asignacion_horario" => $horario['id_asignacion_horario'],
                        'id_especialista_asignacion' => $horario['id_especialista'],
                        'fecha_inicio' => $horario['fecha_inicio'],
                        'fecha_fin' => $horario['fecha_fin'],
                        'id_dia' => $horario['id_dia'],
                        'id_horario' => $horario['id_horario'],
                        'hora_inicio' => $horario['hora_inicio'],
                        'hora_fin' => $horario['hora_fin'],
                        'intervalo' => $horario['intervalo'],
                        'numero_paciente' => $horario['numero_paciente'],
                        'turno' => $horario['turno'],
                        'nro_fichas_disponibles' => $horario['numero_paciente'] - ($ocupadas + $suspendidas),
                        'nro_fichas_ocupadas' => $ocupadas,
                        'nro_fichas_suspendidas' => $suspendidas,
                        'id_consultorio' => $horario['id_consultorio'],
                        'numero_consultorio' => $horario['numero_consultorio'],
                        'id_sedes' => $horario['id_sedes'],
                        'ubicacion' => $horario['ubicacion'],
                        'piso' => $horario['piso'],
                        'id_residencia' => $horario['id_residencia'],
                        'direccion' => $horario['direccion'],
                        'nro_vivienda' => $horario['nro_vivienda'],
                        'id_zona' => $horario['id_zona'],
                        'zona' => $horario['zona'],
                        'id_especialista_especialista' => $especialistaDisponible['id_especialista'],
                        'nombres' => $especialistaDisponible['nombres'],
                        'foto' => $especialistaDisponible['foto'],
                        'grado_academico' => $especialistaDisponible['grado_academico'],
                    ];
                }
            }
        }
        // dd($horariosDisponibles);
        if (empty($horariosDisponibles)) {
            abort(404, 'No hay especialistas disponibles.');
        }

        return $horariosDisponibles;
    }

    public function especialistas_disponibles_old($id_especialidad, $fechaElegida, $tipo_asegurado)
    {
        // dd()
        if (!$id_especialidad || !$fechaElegida || !$tipo_asegurado) {
            abort(400, 'Faltan parámetros obligatorios.');
        }

        try {
            $fechaElegida = Carbon::parse($fechaElegida)->format('Y-m-d');
        } catch (\Exception $e) {
            abort(400, 'Fecha inválida.');
        }

        $diaElegido = Carbon::parse($fechaElegida)->dayOfWeek;

        $especialistas_cache = Cache::get('especialistas');

        if (!$especialistas_cache) {
            abort(404, 'No se encontraron especialistas en caché.');
        }

        $especialistas = collect($especialistas_cache);
        // dd($especialistas);
        // 1. Filtrar especialistas por especialidad, fechas y tipo asegurado
        $especialistasFiltrados = $especialistas->filter(function ($esp) use ($id_especialidad, $fechaElegida, $tipo_asegurado) {
            // dd($esp);
            if ($esp['id_especialidad'] !== $id_especialidad) {
                return false;
            }
            // dd($esp);

            $contrato_valido = ($esp['fecha_contrato_inicio'] <= $fechaElegida || $esp['permanente']) &&
                ($esp['fecha_contrato_fin'] >= $fechaElegida || $esp['permanente']);
            if (!$contrato_valido) {
                return false;
            }
            // dd($contrato_valido);
            // dd($esp['espacialistaHabilitado']);

            if (empty($esp['especialistaHabilitado'])) { // corregí el typo aquí
                return false;
            }
            // dd($esp['afiliados']);
            // Validar tipo asegurado
            if ($tipo_asegurado === 1 && !$esp['afiliados']) return false;
            if ($tipo_asegurado === 2 && !$esp['convenios']) return false;
            if ($tipo_asegurado === 3 && !$esp['estudiantes']) return false;

            return true;
        });

        // dd($especialistasFiltrados);

        // 2. Para cada especialista, filtrar sus habilitaciones activas para el día y fecha
        $especialistasDisponibles = collect();

        foreach ($especialistasFiltrados as $especialista) {
            $habilitacionesActivas = collect($especialista['especialistaHabilitado'])->filter(function ($hab) use ($diaElegido, $fechaElegida) {
                return $hab['id_dia'] === $diaElegido &&
                    (($hab['fecha_inicio'] <= $fechaElegida || $hab['permanente']) &&
                        ($hab['fecha_fin'] >= $fechaElegida || $hab['permanente']));
            });

            // Si tiene habilitaciones activas, agregamos el especialista formateado
            if ($habilitacionesActivas->isNotEmpty()) {
                $especialistasDisponibles->push([
                    'id_especialista' => $especialista['id_especialista'],
                    'foto' => $especialista['foto'],
                    'nombres' => implode(' ', [
                        $especialista['p_apellido'],
                        $especialista['s_apellido'],
                        $especialista['nombres'],
                    ]),
                    'grado_academico' => $especialista['grado_academico'] === 'DOCTORA' ? 'DRA.' : ($especialista['grado_academico'] === 'DOCTOR' ? 'DR.' : 'LIC.'),
                ]);
            }
        }

        return $especialistasDisponibles->values()->all();
    }

    public function listar_especialistas($id_especialidad, $fechaElegida)
    {

        $fechaElegida = Carbon::parse($fechaElegida)->format('Y-m-d');
        $diaElegido = Carbon::parse($fechaElegida)->dayOfWeek;
        $identificadorTipoAsegurado = null;


        $especialistas_cache = Cache::get('especialistas');
        $horarios_cache = Cache::get('horarios');
        $horariosPorFecha = collect($horarios_cache);
        $especialistasPorFecha = collect($especialistas_cache);
        $especialistasDisponibles = [];

        $especialistasDisponibles = $especialistasPorFecha;
        $especialistasDisponibles = $especialistasPorFecha
            ->filter(function ($especialista) use ($id_especialidad, $identificadorTipoAsegurado, $fechaElegida) {
                if ($especialista['id_especialidad'] !== $id_especialidad) {
                    return false;
                }

                $fechaValida = (($especialista['fecha_contrato_inicio'] <= $fechaElegida || $especialista['permanente'])
                    && ($especialista['fecha_contrato_fin'] >= $fechaElegida || $especialista['permanente']));

                if (!$fechaValida) {
                    return false;
                }

                if (empty($especialista['espacialistaHabilitado'])) {
                    return false;
                }

                if ($identificadorTipoAsegurado === 1 && !$especialista['afiliados']) {
                    return false;
                }

                if ($identificadorTipoAsegurado === 2 && !$especialista['convenios']) {
                    return false;
                }

                if ($identificadorTipoAsegurado === 3 && !$especialista['estudiantes']) {
                    return false;
                }

                return true;
            })
            ->flatMap(function ($especialista)  use ($diaElegido, $fechaElegida) {
                return collect($especialista['espacialistaHabilitado'])
                    ->filter(function ($espHabServ) use ($especialista, $diaElegido, $fechaElegida) {
                        return $espHabServ['id_dia'] === $diaElegido
                            && (
                                ($espHabServ['fecha_inicio'] <= $fechaElegida || $espHabServ['permanente']) &&
                                ($espHabServ['fecha_fin'] >= $fechaElegida || $espHabServ['permanente'])
                            );
                    })
                    ->map(function () use ($especialista) {
                        return [
                            'id_especialista' => $especialista['id_especialista'],
                            'foto' => $especialista['foto'],
                            'nombres' => implode(' ', [
                                $especialista['p_apellido'],
                                $especialista['s_apellido'],
                                $especialista['nombres']
                            ]),
                            'grado_academico' => $especialista['grado_academico'] === 'DOCTORA'
                                ? 'DRA.'
                                : ($especialista['grado_academico'] === 'DOCTOR' ? 'DR.' : 'LIC.')

                        ];
                    });
            })
            ->values()
            ->all();

        foreach ($especialistasDisponibles as $especialistaDisponible) {
            foreach ($horariosPorFecha as $horario) {
                if (
                    $horario['id_dia'] === $diaElegido
                    && (($horario['fecha_inicio'] <= $fechaElegida) &&
                        ($horario['fecha_fin'] >= $fechaElegida || $horario['fecha_fin'] === null))
                    && $horario['id_especialista'] === $especialistaDisponible['id_especialista']
                ) {

                    $verificacionAgendas = Agenda::where('id_asignacion_horario', $horario['id_asignacion_horario'])
                        ->where('fecha_agenda', $fechaElegida)
                        ->where('anulacion_ficha', false)
                        ->where('ficha_extra', false)
                        ->get()
                        ->groupBy('id_asignacion_horario');

                    $agendasVerificadas = $verificacionAgendas->get($horario['id_asignacion_horario'], collect());
                    $ocupadas = $agendasVerificadas->where('id_suspension_horario', null)->count();
                    $suspendidas = $agendasVerificadas->where('id_suspension_horario', '!=', null)->count();

                    $horariosDisponibles[] = [
                        "id_asignacion_horario" => $horario['id_asignacion_horario'],
                        'id_especialista_asignacion' => $horario['id_especialista'],
                        'fecha_inicio' => $horario['fecha_inicio'],
                        'fecha_fin' => $horario['fecha_fin'],
                        'id_dia' => $horario['id_dia'],
                        'id_horario' => $horario['id_horario'],
                        'hora_inicio' => $horario['hora_inicio'],
                        'hora_fin' => $horario['hora_fin'],
                        'intervalo' => $horario['intervalo'],
                        'numero_paciente' => $horario['numero_paciente'],
                        'turno' => $horario['turno'],
                        'nro_fichas_disponibles' => $horario['numero_paciente'] - ($ocupadas + $suspendidas),
                        'nro_fichas_ocupadas' => $ocupadas,
                        'nro_fichas_suspendidas' => $suspendidas,
                        'id_consultorio' => $horario['id_consultorio'],
                        'numero_consultorio' => $horario['numero_consultorio'],
                        'id_sedes' => $horario['id_sedes'],
                        'ubicacion' => $horario['ubicacion'],
                        'piso' => $horario['piso'],
                        'id_residencia' => $horario['id_residencia'],
                        'direccion' => $horario['direccion'],
                        'nro_vivienda' => $horario['nro_vivienda'],
                        'id_zona' => $horario['id_zona'],
                        'zona' => $horario['zona'],
                        'id_especialista_especialista' => $especialistaDisponible['id_especialista'],
                        'nombres' => $especialistaDisponible['nombres'],
                        'foto' => $especialistaDisponible['foto'],
                        'grado_academico' => $especialistaDisponible['grado_academico'],
                    ];
                }
            }
        }
        return $especialistasDisponibles;
        // $paso = 3;
    }
    public function especialistaSeleccionado($idEspecialista, $idAsignacionHorario, $fechaElegida)
    {
        $listaHorarios = [];
        // $especialistaElegido = $idEspecialista;
        // $horarioElegido = $idAsignacionHorario;
        $horarios_cache = Cache::get('horarios');
        $horariosElegibles = collect($horarios_cache)
            ->first(function ($horitas) use ($idAsignacionHorario, $idEspecialista) {
                return $horitas['id_asignacion_horario'] == $idAsignacionHorario
                    && $horitas['id_especialista'] == $idEspecialista;
            });

        //dd($horariosElegibles);

        $listaHorarios = $this->listaHorasAgendaWeb(
            $horariosElegibles['hora_inicio'],
            $horariosElegibles['hora_fin'],
            $horariosElegibles['intervalo'],
            $fechaElegida,
            $horariosElegibles['id_asignacion_horario']
        );

        if ($listaHorarios === []) {
            abort(404, ' no hay horarios disponibles');
        }
        return $listaHorarios;
    }
    public function  listaHorasAgendaWeb($horaInicio, $horaFin, $inter, $fechaAgenda, $idAsignacionHorario, /* $fechaElegida */)
    {
        // $horaActualParaVerificacion = Carbon::now()->format('H:i');
        // $switchMismoDia = Carbon::now()->format('Y-m-d') === $fechaElegida;

        $horas = [];
        $inicio = Carbon::createFromFormat('H:i:s', $horaInicio);
        $fin = Carbon::createFromFormat('H:i:s', $horaFin);

        // horitas disponiblesssss
        while ($inicio->lt($fin)) {
            $horaActual = $inicio->format('H:i');
            $estadoHora = Agenda::with(['suspensionHorarioAgenda', 'servicioPlataformaAgenda'])
                ->where('fecha_agenda', $fechaAgenda)
                ->where('hora_agenda', $inicio->format('H:i:s'))
                ->where('id_asignacion_horario', $idAsignacionHorario)
                ->first();

            $horas[] = $this->procesarHoraAgendaWeb($estadoHora, $horaActual);
            $inicio->addMinutes($inter);
        }

        return $horas;
    }
    public function procesarHoraAgendaWeb($estadoHora, $horaActual)
    {
        $hora = [
            'hora' => $horaActual,
            'matricula' => null,
            'asegurado' => null,
            'institucion' => null,
            'tipo_asegurado' => null,
            'estado' => 'LIBRE',
            'observacion' => null,
            'motivo_suspension' => null,
            'servicio' => null,
            'ficha_extra' => false,
        ];

        if ($estadoHora) {
            $hora['id_agenda'] = $estadoHora->id;

            if ($estadoHora->id_persona) {
                if ($estadoHora->anulacion_ficha) {
                    return $hora;
                }

                if ($estadoHora->id_suspension_horario) {
                    $hora['estado'] = 'SUSPENDIDO';
                    $hora['observacion'] = $estadoHora->suspensionHorarioAgenda->observacion;
                } else {
                    $hora['estado'] = 'OCUPADO';
                }
            } else {
                $hora['estado'] = $estadoHora->id_suspension_horario ? 'SUSPENDIDO' : 'OCUPADO';
                $hora['observacion'] = $estadoHora->suspensionHorarioAgenda->observacion ?? null;
            }
        }
        return $hora;
    }
    // public function especialistas_disponibles($id_especialidad, $fechaElegida, $tipo_asegurado)
    // {
    //     if (!$id_especialidad || !$fechaElegida || !$tipo_asegurado) {
    //         abort(400, 'Faltan parámetros obligatorios.');
    //     }

    //     try {
    //         $fechaElegida = Carbon::parse($fechaElegida)->format('Y-m-d');
    //     } catch (\Exception $e) {
    //         abort(400, 'Fecha inválida.');
    //     }

    //     $diaElegido = Carbon::parse($fechaElegida)->dayOfWeek;

    //     $especialistas_cache = Cache::get('especialistas');
    //     if (!$especialistas_cache) {
    //         abort(404, 'No se encontraron especialistas en caché.');
    //     }

    //     $especialistasPorFecha = collect($especialistas_cache);
    //     $identificadorTipoAsegurado = $tipo_asegurado;

    //     $especialistasDisponibles = $especialistasPorFecha
    //         ->filter(function ($especialista) use ($id_especialidad, $fechaElegida, $identificadorTipoAsegurado) {
    //             if ($especialista['id_especialidad'] !== $id_especialidad) {
    //                 return false;
    //             }

    //             $inicio = $especialista['fecha_contrato_inicio'] ?? null;
    //             $fin = $especialista['fecha_contrato_fin'] ?? null;
    //             $permanente = $especialista['permanente'] ?? false;

    //             $fechaValida = (
    //                 $permanente ||
    //                 (
    //                     (!is_null($inicio) && $inicio <= $fechaElegida) &&
    //                     (!is_null($fin) && $fin >= $fechaElegida)
    //                 )
    //             );

    //             if (!$fechaValida) {
    //                 return false;
    //             }

    //             if (empty($especialista['espacialistaHabilitado'])) {
    //                 return false;
    //             }

    //             if ($identificadorTipoAsegurado === 1 && !$especialista['afiliados']) {
    //                 return false;
    //             }

    //             if ($identificadorTipoAsegurado === 2 && !$especialista['convenios']) {
    //                 return false;
    //             }

    //             if ($identificadorTipoAsegurado === 3 && !$especialista['estudiantes']) {
    //                 return false;
    //             }

    //             return true;
    //         })
    //         ->flatMap(function ($especialista) use ($diaElegido, $fechaElegida) {
    //             return collect($especialista['espacialistaHabilitado'])
    //                 ->filter(function ($espHabServ) use ($especialista, $diaElegido, $fechaElegida) {
    //                     $inicio = $espHabServ['fecha_inicio'] ?? null;
    //                     $fin = $espHabServ['fecha_fin'] ?? null;
    //                     $permanente = $espHabServ['permanente'] ?? false;

    //                     return $espHabServ['id_dia'] === $diaElegido &&
    //                         (
    //                             $permanente ||
    //                             (
    //                                 (!is_null($inicio) && $inicio <= $fechaElegida) &&
    //                                 (!is_null($fin) && $fin >= $fechaElegida)
    //                             )
    //                         );
    //                 })
    //                 ->map(function () use ($especialista) {
    //                     return [
    //                         'id_especialista' => $especialista['id_especialista'],
    //                         'foto' => $especialista['foto'],
    //                         'nombres' => implode(' ', [
    //                             $especialista['p_apellido'],
    //                             $especialista['s_apellido'],
    //                             $especialista['nombres']
    //                         ]),
    //                         'grado_academico' => $especialista['grado_academico'] === 'DOCTORA'
    //                             ? 'DRA.'
    //                             : ($especialista['grado_academico'] === 'DOCTOR' ? 'DR.' : 'LIC.')
    //                     ];
    //                 });
    //         })
    //         ->values()
    //         ->all();

    //     return $especialistasDisponibles;
    // }
}
