<?php

namespace App\Services\Administracion;

use App\Services\ImageService;
use App\Models\Administracion\Persona;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PersonaService
{
    protected $imagenService;

    public function __construct(ImageService $imagenService)
    {
        $this->imagenService = $imagenService;
    }
    // Add your service logic here
    public function obtenerPoblacionAseguradaCache()
    {
        $cache = Cache::get('poblacion_asegurada');
        // dd('servicio');
        return $cache;
    }

    public function obtener_id_titular_login($id_persona)
    {
        $persona = Persona::whereNot('id_tipo_asegurado', 0)
            ->where('afiliado', true)
            ->where('id', $id_persona)
            ->with([
                'tipoAseguradoPersona',
                'PersonaTitular',
                'PersonaBeneficiario.titularBeneficiario',
                'autorizacionInteriorPersonaInterior',
                'convenioPersona',
                'PersonaEstudiante'
            ])
            ->orderBy('id', 'asc')
            ->first();

        $idPersonaTitular = 0;

        $tipoAsegurado = $persona->tipoAseguradoPersona->tipo_asegurado ?? null;
        // dd($tipoAsegurado);
        switch ($tipoAsegurado) {
            case 'TITULAR':
                $titular = $persona->PersonaTitular->where('estado', true)->first();
                // dd($titular->id_persona);
                $idPersonaTitular = $titular->id_persona ?? 0;
                // $liga = 'Afiliacion/FOTOS/';
                // $foto = $titular->foto_nombre ?? null;
                return $idPersonaTitular;
                // break;

            case 'BENEFICIARIO DEL INTERIOR':
                $autorizacionInterior = $persona->autorizacionInteriorPersonaInterior->where('estado', true)->first();
                $idPersonaTitular = $autorizacionInterior->id_persona_titular ?? 0;
                // $liga = 'Vigencia/FOTOS/CONVENIO/';
                // $foto = $autorizacionInterior->foto_nombre ?? null;
                return $idPersonaTitular;

            case (Str::contains($tipoAsegurado, 'BENEFICIARIO')):
                $beneficiario = $persona->PersonaBeneficiario->where('estado', true)->first();
                // dd($beneficiario);
                $idPersonaTitular = $beneficiario->titularBeneficiario->id_persona ?? 0;
                // $liga = 'Afiliacion/FOTOS/';
                // $foto = $beneficiario->foto_nombre ?? null;
                return $idPersonaTitular;

            case 'TITULAR DEL INTERIOR':
                $autorizacionInteriorTitular = $persona->autorizacionInteriorPersonaInterior->where('estado', true)->first();
                $idPersonaTitular = $autorizacionInteriorTitular->id_persona_titular ?? 0;
                // $liga = 'Vigencia/FOTOS/CONVENIO/';
                // $foto = $autorizacionInteriorTitular->foto_nombre ?? null;
                return $idPersonaTitular;

            case 'TITULAR CONVENIO':
                $convenio = $persona->convenioPersona->where('estado', true)->first();
                $idPersonaTitular = $convenio->id_persona ?? 0;
                // $liga = 'Vigencia/FOTOS/CONVENIO/';
                // $foto = $convenio->foto_nombre ?? null;
                return $idPersonaTitular;

            case 'ESTUDIANTE':
                $estudiante = $persona->PersonaEstudiante
                    ->where('estado_vigencia', true)
                    ->where('estado', true)
                    ->where('estado_validar', 'AFILIADO')
                    ->first();
                $idPersonaTitular = $estudiante->id_persona ?? 0;
                // $liga = 'Afiliacion/SSUE/FOTOS/';
                // $foto = $estudiante->foto_nombre ?? null;
                return $idPersonaTitular;

            default:
                return $idPersonaTitular = 0;
                // break;
        }
    }
    public function obtener_imagen_persona($id_persona)
    {
        $poblacion_asegurada_cache = $this->obtenerPoblacionAseguradaCache();
        // dd($poblacion_asegurada_cache);

        $persona = collect($poblacion_asegurada_cache)->firstWhere('id_persona', $id_persona);
        // dd($persona);
        if ($persona == null) {
            abort(404, 'Persona no encontrada'); // Deja que esto lance la excepción
        }

        $foto_nombre = $persona['foto'];
        $liga = $persona['liga'];

        return $this->imagenService->obtener_imagen($foto_nombre, $liga);
    }
    // public function obtener_imagen_persona($id_persona)
    // {
    //     try {
    //         $poblacion_asegurada_cache = $this->obtenerPoblacionAseguradaCache();
    //         $persona = collect($poblacion_asegurada_cache)->firstWhere('id_persona', $id_persona);

    //         if ($persona == null) {
    //             // Lanzar una excepción personalizada o estándar
    //             throw new \Exception('Persona no encontrada');
    //         }

    //         $foto_nombre = $persona['foto'];
    //         $liga = $persona['liga'];

    //         return $this->imagenService->obtener_imagen($foto_nombre, $liga);
    //     } catch (\Exception $e) {
    //         // Aquí puedes loguear, hacer algo extra si quieres...
    //         // Luego relanzar la excepción para que el controlador la capture
    //         throw $e;
    //     }
    // }
    // public function listarEspecialidades(Request $request)
    // {
    //     try {
    //         $fecha_agenda = Carbon::parse($request->fecha_agenda)->format('Y-m-d');
    //         $id_persona = $request->id_persona;

    //         $verifica_agenda = AgendaController::fecha_agenda_ocupada_familiar($fecha_agenda, $id_persona);
    //         if ($verifica_agenda) {
    //             $data = [
    //                 'status' => 409,
    //                 'message' => 'Esta fecha ya a sido ocupada por Usted o algun miembro del grupo familiar',
    //             ];
    //             return response()->json($data, 409);
    //         }
    //         // dd($fecha_agenda);
    //         // $especialidades_habilitadas = EspecialidadHabilitadoServicio::where('estado', '=', true)
    //         //     ->where('fecha_inicio', '<=', $fecha_agenda)
    //         //     ->orWhereNull('fecha_inicio')
    //         //     ->where('fecha_fin', '>=', $fecha_agenda)
    //         //     ->orWhereNull('fecha_fin')
    //         //     ->where('id_servicio', '=', 4)
    //         //     ->with([
    //         //         'especialidadEspecialidadHabilitadoServicio',
    //         //     ])
    //         //     ->get();
    //         $especialidades_habilitadas = EspecialidadHabilitadoServicio::where('estado', '=', true)
    //             ->where(function ($query) use ($fecha_agenda) {
    //                 // Grupo de condiciones para fecha_inicio
    //                 $query->where('fecha_inicio', '<=', $fecha_agenda)
    //                     ->orWhereNull('fecha_inicio');
    //             })
    //             ->where(function ($query) use ($fecha_agenda) {
    //                 // Grupo de condiciones para fecha_fin
    //                 $query->where('fecha_fin', '>=', $fecha_agenda)
    //                     ->orWhereNull('fecha_fin');
    //             })
    //             ->where('id_servicio', '=', 2)
    //             ->with(['especialidadEspecialidadHabilitadoServicio'])
    //             ->get();

    //         if ($especialidades_habilitadas->isEmpty()) {
    //             $data = [
    //                 'status' => 404,
    //                 'message' => 'No se econtraron datos de Especialidades habilitadas en la fecha',
    //             ];
    //             return response()->json($data, 404);
    //         }
    //         // $especialidades = Especialidad::where('estado', 'true')->get();
    //         // if ($especialidades->isEmpty()) {
    //         //     $data = [
    //         //         'status' => 404,
    //         //         'message' => 'No se econtraron datos de Especialidades',
    //         //     ];
    //         //     return response()->json($this->utf8ize($data), 404);
    //         // }
    //         $data = [
    //             'status' => 200,
    //             'especialidades' => $especialidades_habilitadas,

    //         ];
    //         return response()->json($data, 200);
    //     } catch (\Throwable $th) {
    //         $dataError = [
    //             'success' => false,
    //             'message' => 'Error al listar Especialidades',
    //             'error' => $th->getMessage(), // Mensaje del error
    //             'line' => $th->getLine(), // L nea donde ocurri el error
    //             'file' => $th->getFile(), // Archivo donde ocurri el error
    //         ];
    //         return response()->json($dataError, 500);
    //     }
    // }
}
