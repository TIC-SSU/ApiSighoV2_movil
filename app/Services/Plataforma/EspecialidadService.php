<?php

namespace App\Services\Plataforma;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;


class EspecialidadService
{
    protected $agendaService;

    public function __construct(AgendaService $agendaService)
    {
        $this->agendaService = $agendaService;
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
    public function listar_especialidades($fecha, $id_persona_titular)
    {
        // dd($fecha, $id_persona_titular);
        $response = $this->agendaService->existeGrupoFamiliarAgendado($fecha, $id_persona_titular);
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
                $especialidadesDisponibles[] = [
                    'id_especialidad' => $especialidad['id_especialidad'],
                    'especialidad' => $especialidad['especialidad'],
                ];
            }
        }

        if (empty($especialidadesDisponibles)) {
            abort(422, 'No hay especialidades disponibles para la fecha seleccionada.');
        }

        return $especialidadesDisponibles;
    }
}
