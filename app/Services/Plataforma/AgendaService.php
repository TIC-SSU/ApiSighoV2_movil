<?php

namespace App\Services\Plataforma;

use App\Models\Plataforma\DiasHabilitadosAgenda;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AgendaService
{
    public $especialidadesDisponibles = [];
    public $especialistasDisponibles = [];
    protected $poblacion_asegurada_cache;
    protected $especialidades_cache;
    protected $especialistas_cache;
    protected $horarios_cache;

    //DATOS PERSONA
    public $fecha_nacimiento;
    public $nombre_completo;
    public $matricula;
    public $tipo_asegurado;
    public $foto_nombre;


    //DATOS AFIRMADO
    public $fecha_text;
    public $especialidad_text;
    public $doctor_select;

    //AGENDA COMO TAL
    public $fechaElegida;
    public $diaElegido;
    // public $especialidadesDisponibles = [];
    // public $especialistasDisponibles = [];

    //PASOS
    public $paso = 1;
    // Add your service logic here
    public function obtenerFechasDisponibles(): ?array
    {
        try {
            $fechasDisponibles = DiasHabilitadosAgenda::where('estado', true)
                ->where('id_servicio_plataforma', 2)
                ->first();

            if (!$fechasDisponibles) {
                return null;
            }

            $fechasElegibles = [];

            for ($i = 0; $i < $fechasDisponibles->nro_dias; $i++) {
                $fecha = $fechasDisponibles->hoy
                    ? Carbon::now()->addDays($i)
                    : Carbon::now()->addDays($i + 1);

                $fechasElegibles[] = $fecha->format('Y-m-d');
            }

            return $fechasElegibles;
        } catch (Exception $e) {
            Log::error('Error obteniendo fechas disponibles: ' . $e->getMessage());
            throw $e;  // o false si prefieres
        }
    }

    protected function obtenerPoblacionAseguradaCache()
    {
        $this->poblacion_asegurada_cache = Cache::get('poblacion_asegurada');
    }

    protected function obtenerEspecialidadesCache()
    {
        $this->especialidades_cache = Cache::get('especialidades');
    }

    protected function obtenerEspecialistasCache()
    {
        $this->especialistas_cache = Cache::get('especialistas');
    }

    protected function obtenerHorariosCache()
    {
        $this->horarios_cache = Cache::get('horarios');
    }

    public function obtenerDatosPersona(int $id_persona)
    {
        $this->obtenerPoblacionAseguradaCache();
        $persona = collect($this->poblacion_asegurada_cache)->firstWhere('id_persona', $id_persona);
        $this->fecha_nacimiento = $persona['fecha_nacimiento'];
        $this->nombre_completo = implode(' ', [$persona['nombres'], $persona['p_apellido'], $persona['s_apellido']]);
        $this->matricula = $persona['matricula_seguro'];
        $this->tipo_asegurado = $persona['tipo_asegurado'];
        $this->foto_nombre = $this->obtenerImagenBase64($persona['foto'], $persona['liga']);
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
}
