<?php

namespace App\Services\Plataforma;

use App\Models\Plataforma\DiasHabilitadosAgenda;
use Carbon\Carbon;

class DiasHabilitadosService
{
    // Add your service logic here
    public function obtenerFechasDisponibles(): array
    {
        $fechasElegibles = [];
        $fechasDisponibles = DiasHabilitadosAgenda::where('estado', true)
            ->where('id_servicio_plataforma', 2)
            ->first();

        for ($i = 0; $i < $fechasDisponibles->nro_dias; $i++) {
            if ($fechasDisponibles->hoy) {
                $fechasElegibles[] = Carbon::now()->addDays($i)->format('Y-m-d');
            } else {
                $fechasElegibles[] = Carbon::now()->addDays($i + 1)->format('Y-m-d');
            }
        }
        return $fechasElegibles;
        //dd($fechasElegibles);
    }
}
