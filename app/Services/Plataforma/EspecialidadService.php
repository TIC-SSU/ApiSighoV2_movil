<?php

namespace App\Services\Plataforma;

use App\Models\Administracion\Especialidad;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EspecialidadService
{
    protected $agendaService;

    public function __construct(AgendaService $agendaService)
    {
        $this->agendaService = $agendaService;
    }
    public function top_especialidades()
    {
        $especialidades = Especialidad::all();

        $resultados = $especialidades->map(function ($esp) {
            return [
                'id' => $esp->id,
                'especialidad' => $esp->especialidad,
                'total_agendas' => $esp->contarAgendasPorEspecialidad(),
            ];
        });

        // Opcional: ordenar y limitar a las 5 más buscadas
        $top5 = $resultados->sortByDesc('total_agendas')->take(5)->values();
        return $top5;
        // $topEspecialidades = Especialidad::select(
        //     'administracion.especialidad.id',
        //     'administracion.especialidad.especialidad',
        //     DB::raw('COUNT(agenda.id) AS total_agendas')
        // )
        //     ->join('plataforma.especialista', 'especialista.id_especialidad', '=', 'administracion.especialidad.id')
        //     ->join('plataforma.asignacion_horario', 'asignacion_horario.id_especialista', '=', 'especialista.id')
        //     ->join('plataforma.agenda', 'agenda.id_asignacion_horario', '=', 'asignacion_horario.id')
        //     ->groupBy('administracion.especialidad.id', 'administracion.especialidad.especialidad')
        //     ->orderByDesc('total_agendas')
        //     ->limit(5)
        //     ->get();

        // return $topEspecialidades;
    }

    // Add your service logic here
    public function obtenerEspecialidadesCache()
    {
        $especialidades_cache = Cache::get('especialidades');
        // dd($especialidades_cache);
        if (!$especialidades_cache) {
            abort(404, 'no existen Especialidades en cache');
        }
        return $especialidades_cache;
    }
    public function listar_especialidades($fecha, $id_persona_titular, $sexo, $fecha_nacimiento)
    {
        $edad = Carbon::parse($fecha_nacimiento);
        // dd($fecha, $id_persona_titular);
        /*$response = $this->agendaService->existeGrupoFamiliarAgendado($fecha, $id_persona_titular);
        // dd($response);
        if ($this->agendaService->existeGrupoFamiliarAgendado($fecha, $id_persona_titular)) {
            abort(404, 'Ya existe un familar agendado en la fecha');
        }
        $especialidadesPorFecha = Cache::get('especialidades');
        // dd($especialidadesPorFecha);
        if (!$especialidadesPorFecha) {
            abort(404, 'No se encontraron especialidades en caché.');
        }

        $especialidadesDisponibles = [];
        $fechaElegida = Carbon::parse($fecha)->format('Y-m-d');

        foreach ($especialidadesPorFecha as $especialidad) {
            $inicio = $especialidad['fecha_inicio'];
            $fin = $especialidad['fecha_fin'];
            $permanente = $especialidad['permanente'];

            $disponible = (
                ($inicio <= $fechaElegida || $permanente) &&
                ($fin >= $fechaElegida || $permanente)
            );

            if ($disponible) {

                $nombreEspecialidad = mb_strtoupper($especialidad['especialidad']);

                if ($nombreEspecialidad === 'GERIATRÍA' && $edad <= 59) {
                    continue;
                }

                if ($nombreEspecialidad === 'PEDIATRÍA' && $edad >= 15) {
                    continue;
                }

                if ($nombreEspecialidad === 'GINECOLOGÍA' && $sexo === 'M') {
                    continue;
                }

                if ($nombreEspecialidad === 'UROLOGÍA' && $sexo === 'F') {
                    continue;
                }

                $especialidadesDisponibles[] = [
                    'id_especialidad' => $especialidad['id_especialidad'],
                    'especialidad' => $especialidad['especialidad'],
                ];
            }
        }

        if (empty($especialidadesDisponibles)) {
            abort(422, 'No hay especialidades disponibles para la fecha seleccionada.');
        }

        return $especialidadesDisponibles;*/
        return $edad;
    }
}
