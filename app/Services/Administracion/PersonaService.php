<?php

namespace App\Services\Administracion;

use App\Services\ImageService;
use App\Models\Administracion\Persona;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Models\Vigencia\Convenio;
use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Vigencia\AutorizacionInterior;

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
    public function encontrarPersona($idPersona)
    {
        $persona = Persona::where('id', $idPersona)->with(['tipoAseguradoPersona'])->first();
        if (!$persona) {
            abort(404, 'No se encontro datos de la persona');
        }
        $datosAfiliado = [];
        //dd($persona);
        if ($persona->tipoAseguradoPersona->tipo_asegurado == 'TITULAR') {

            $datosAfiliado = $this->encontrarTitular($persona->id);
            return $datosAfiliado;
        } elseif (Str::contains($persona->tipoAseguradoPersona->tipo_asegurado, 'BENEFICIARIO')) {
            if ($persona->tipoAseguradoPersona->tipo_asegurado == 'BENEFICIARIO DEL INTERIOR') {
                $datosAfiliado = $this->encontrarConvenioInterior($persona->id);
            } else {
                $datosAfiliado = $this->encontrarBeneficiario($persona->id);
            }
            return $datosAfiliado;
        } elseif (
            $persona->tipoAseguradoPersona->tipo_asegurado == 'TITULAR DEL INTERIOR' ||
            $persona->tipoAseguradoPersona->tipo_asegurado == 'BENEFICIARIO DEL INTERIOR'
        ) {
            $datosAfiliado = $this->encontrarConvenioInterior($persona->id);
            return $datosAfiliado;
        } elseif ($persona->tipoAseguradoPersona->tipo_asegurado == 'ESTUDIANTE') {
            $datosAfiliado = $this->encontrarEstudiante($persona->id);
            return $datosAfiliado;
        } elseif ($persona->tipoAseguradoPersona->tipo_asegurado == 'TITULAR CONVENIO') {
            $datosAfiliado = $this->encontrarTitularConvenioEmergencia($persona->id);
            return $datosAfiliado;
        } else {
            return [];
        }
    }

    public function encontrarTitular($idPersona)
    {
        $titularGrupoFamiliar = [];
        $titular = Titular::where('id_persona', $idPersona)
            ->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])
            ->where('estado', true)
            ->first();

        if ($titular) {
            $personaTitular = $this->busquedaPoblacion($idPersona);
            //dd($personaTitular[0]->id);
            $titularGrupoFamiliar[0]['id'] = $personaTitular[0]->id;
            $titularGrupoFamiliar[0]['ci'] = $personaTitular[0]->ci;
            $titularGrupoFamiliar[0]['complemento'] = $personaTitular[0]->complemento;
            $titularGrupoFamiliar[0]['matricula_seguro'] = $personaTitular[0]->matricula_seguro;
            $titularGrupoFamiliar[0]['nombres'] = $personaTitular[0]->nombres;
            $titularGrupoFamiliar[0]['p_apellido'] = $personaTitular[0]->p_apellido;
            $titularGrupoFamiliar[0]['s_apellido'] = $personaTitular[0]->s_apellido;
            $titularGrupoFamiliar[0]['fecha_nacimiento'] = $personaTitular[0]->fecha_nacimiento;
            $titularGrupoFamiliar[0]['id_tipo_asegurado'] = $personaTitular[0]->id_tipo_asegurado;
            $titularGrupoFamiliar[0]['tipo_asegurado'] = $personaTitular[0]->tipo_asegurado;
            $titularGrupoFamiliar[0]['id_estado_civil'] = $personaTitular[0]->id_estado_civil;
            $titularGrupoFamiliar[0]['estado_civil'] = $personaTitular[0]->nombre;
            $titularGrupoFamiliar[0]['id_expedido'] = $personaTitular[0]->id_dept_exp;
            $titularGrupoFamiliar[0]['expedido'] = $personaTitular[0]->sigla;
            $titularGrupoFamiliar[0]['sexo'] = $personaTitular[0]->sexo;

            $grupoFam = Beneficiario::where('id_titular', $titular->id)
                ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
                ->where('estado', true) // 14/03/25 añadi esto porque me daba todos los registros de sus beneficarios.Atte:liz
                ->get();

            if (isset($grupoFam->toArray()[0])) {
                //dd('significa que tiene grupo');
                foreach ($grupoFam as $key => $fam) {
                    $personaBen = $this->busquedaPoblacion($fam->id_persona);
                    $titularGrupoFamiliar[$key + 1]['id'] = $personaBen[0]->id;
                    $titularGrupoFamiliar[$key + 1]['ci'] = $personaBen[0]->ci;
                    $titularGrupoFamiliar[$key + 1]['complemento'] = $personaBen[0]->complemento;
                    $titularGrupoFamiliar[$key + 1]['matricula_seguro'] = $personaBen[0]->matricula_seguro;
                    $titularGrupoFamiliar[$key + 1]['nombres'] = $personaBen[0]->nombres;
                    $titularGrupoFamiliar[$key + 1]['p_apellido'] = $personaBen[0]->p_apellido;
                    $titularGrupoFamiliar[$key + 1]['s_apellido'] = $personaBen[0]->s_apellido;
                    $titularGrupoFamiliar[$key + 1]['fecha_nacimiento'] = $personaBen[0]->fecha_nacimiento;
                    $titularGrupoFamiliar[$key + 1]['id_tipo_asegurado'] = $personaBen[0]->id_tipo_asegurado;
                    $titularGrupoFamiliar[$key + 1]['tipo_asegurado'] = $personaBen[0]->tipo_asegurado;
                    $titularGrupoFamiliar[$key + 1]['id_estado_civil'] = $personaBen[0]->id_estado_civil;
                    $titularGrupoFamiliar[$key + 1]['estado_civil'] = $personaBen[0]->nombre;
                    $titularGrupoFamiliar[$key + 1]['id_expedido'] = $personaBen[0]->id_dept_exp;
                    $titularGrupoFamiliar[$key + 1]['expedido'] = $personaBen[0]->sigla;
                    $titularGrupoFamiliar[$key + 1]['sexo'] = $personaBen[0]->sexo;
                }
                return $titularGrupoFamiliar;
            } else {
                return $titularGrupoFamiliar;
            }

            // /->titularGrupoFamiliar[]
        } else {
            return [];
        }
    }

    public function encontrarBeneficiario($idPersona)
    {
        $beneficiarioGrupoFamiliar = [];
        $ben = Beneficiario::where('id_persona', $idPersona)
            ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
            ->where('estado', true)
            ->first();
        if ($ben) {
            $titular = Titular::where('id', $ben->id_titular)
                ->whereIn('estado_vigencia', ['VIGENTE', 'CESANTE'])
                ->where('estado', true)
                ->first();
            if ($titular) {
                $personaTitular = $this->busquedaPoblacion($titular->id_persona);
                $beneficiarioGrupoFamiliar[0]['id'] = $personaTitular[0]->id;
                $beneficiarioGrupoFamiliar[0]['ci'] = $personaTitular[0]->ci;
                $beneficiarioGrupoFamiliar[0]['complemento'] = $personaTitular[0]->complemento;
                $beneficiarioGrupoFamiliar[0]['matricula_seguro'] = $personaTitular[0]->matricula_seguro;
                $beneficiarioGrupoFamiliar[0]['nombres'] = $personaTitular[0]->nombres;
                $beneficiarioGrupoFamiliar[0]['p_apellido'] = $personaTitular[0]->p_apellido;
                $beneficiarioGrupoFamiliar[0]['s_apellido'] = $personaTitular[0]->s_apellido;
                $beneficiarioGrupoFamiliar[0]['fecha_nacimiento'] = $personaTitular[0]->fecha_nacimiento;
                $beneficiarioGrupoFamiliar[0]['id_tipo_asegurado'] = $personaTitular[0]->id_tipo_asegurado;
                $beneficiarioGrupoFamiliar[0]['tipo_asegurado'] = $personaTitular[0]->tipo_asegurado;
                $beneficiarioGrupoFamiliar[0]['id_estado_civil'] = $personaTitular[0]->id_estado_civil;
                $beneficiarioGrupoFamiliar[0]['estado_civil'] = $personaTitular[0]->nombre;
                $beneficiarioGrupoFamiliar[0]['id_expedido'] = $personaTitular[0]->id_dept_exp;
                $beneficiarioGrupoFamiliar[0]['expedido'] = $personaTitular[0]->sigla;
                $beneficiarioGrupoFamiliar[0]['sexo'] = $personaTitular[0]->sexo;

                $grupoFam = Beneficiario::where('id_titular', $titular->id)
                    ->whereIn('vigencia', ['VIGENTE', 'CESANTE'])
                    ->where('estado', true)
                    ->get();
                if (isset($grupoFam->toArray()[0])) {
                    //dd('significa que tiene grupo');
                    foreach ($grupoFam as $key => $fam) {
                        $personaBen = $this->busquedaPoblacion($fam->id_persona);
                        $beneficiarioGrupoFamiliar[$key + 1]['id'] = $personaBen[0]->id;
                        $beneficiarioGrupoFamiliar[$key + 1]['ci'] = $personaBen[0]->ci;
                        $beneficiarioGrupoFamiliar[$key + 1]['complemento'] = $personaBen[0]->complemento;
                        $beneficiarioGrupoFamiliar[$key + 1]['matricula_seguro'] = $personaBen[0]->matricula_seguro;
                        $beneficiarioGrupoFamiliar[$key + 1]['nombres'] = $personaBen[0]->nombres;
                        $beneficiarioGrupoFamiliar[$key + 1]['p_apellido'] = $personaBen[0]->p_apellido;
                        $beneficiarioGrupoFamiliar[$key + 1]['s_apellido'] = $personaBen[0]->s_apellido;
                        $beneficiarioGrupoFamiliar[$key + 1]['fecha_nacimiento'] = $personaBen[0]->fecha_nacimiento;
                        $beneficiarioGrupoFamiliar[$key + 1]['id_tipo_asegurado'] = $personaBen[0]->id_tipo_asegurado;
                        $beneficiarioGrupoFamiliar[$key + 1]['tipo_asegurado'] = $personaBen[0]->tipo_asegurado;
                        $beneficiarioGrupoFamiliar[$key + 1]['id_estado_civil'] = $personaBen[0]->id_estado_civil;
                        $beneficiarioGrupoFamiliar[$key + 1]['estado_civil'] = $personaBen[0]->nombre;
                        $beneficiarioGrupoFamiliar[$key + 1]['id_expedido'] = $personaBen[0]->id_dept_exp;
                        $beneficiarioGrupoFamiliar[$key + 1]['expedido'] = $personaBen[0]->sigla;
                        $beneficiarioGrupoFamiliar[$key + 1]['sexo'] = $personaBen[0]->sexo;
                    }
                    return $beneficiarioGrupoFamiliar;
                } else {
                    return $beneficiarioGrupoFamiliar;
                }
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    public function encontrarConvenioInterior($idPersona)
    {
        $convenioGrupoFamiliar = [];

        $convenio = AutorizacionInterior::where('id_persona_interior', $idPersona)->where('estado', true)->first();

        if ($convenio) {
            if ($convenio->id_persona_interior == $convenio->id_persona_titular) {
                $personaTitularInterior = $this->busquedaPoblacion($convenio->id_persona_interior);

                $convenioGrupoFamiliar[0]['id'] = $personaTitularInterior[0]->id;
                $convenioGrupoFamiliar[0]['ci'] = $personaTitularInterior[0]->ci;
                $convenioGrupoFamiliar[0]['complemento'] = $personaTitularInterior[0]->complemento;
                $convenioGrupoFamiliar[0]['matricula_seguro'] = $personaTitularInterior[0]->matricula_seguro;
                $convenioGrupoFamiliar[0]['nombres'] = $personaTitularInterior[0]->nombres;
                $convenioGrupoFamiliar[0]['p_apellido'] = $personaTitularInterior[0]->p_apellido;
                $convenioGrupoFamiliar[0]['s_apellido'] = $personaTitularInterior[0]->s_apellido;
                $convenioGrupoFamiliar[0]['fecha_nacimiento'] = $personaTitularInterior[0]->fecha_nacimiento;
                $convenioGrupoFamiliar[0]['id_tipo_asegurado'] = $personaTitularInterior[0]->id_tipo_asegurado;
                $convenioGrupoFamiliar[0]['tipo_asegurado'] = $personaTitularInterior[0]->tipo_asegurado;
                $convenioGrupoFamiliar[0]['id_estado_civil'] = $personaTitularInterior[0]->id_estado_civil;
                $convenioGrupoFamiliar[0]['estado_civil'] = $personaTitularInterior[0]->nombre;
                $convenioGrupoFamiliar[0]['id_expedido'] = $personaTitularInterior[0]->id_dept_exp;
                $convenioGrupoFamiliar[0]['expedido'] = $personaTitularInterior[0]->sigla;
                $convenioGrupoFamiliar[0]['sexo'] = $personaTitularInterior[0]->sexo;
                //dd($personaTitularInterior);
                $grupoFam = AutorizacionInterior::where('id_persona_titular', $convenio->id_persona_titular)
                    ->whereNot('id_persona_interior', $convenio->id_persona_titular)
                    ->where('estado', true)
                    ->get();
                //dd($grupoFam);
                if (isset($grupoFam->toArray()[0])) {
                    foreach ($grupoFam as $key => $fam) {
                        $personaBenInterior = $this->busquedaPoblacion($fam->id_persona_interior);
                        $convenioGrupoFamiliar[$key + 1]['id'] = $personaBenInterior[0]->id;
                        $convenioGrupoFamiliar[$key + 1]['ci'] = $personaBenInterior[0]->ci;
                        $convenioGrupoFamiliar[$key + 1]['complemento'] = $personaBenInterior[0]->complemento;
                        $convenioGrupoFamiliar[$key + 1]['matricula_seguro'] = $personaBenInterior[0]->matricula_seguro;
                        $convenioGrupoFamiliar[$key + 1]['nombres'] = $personaBenInterior[0]->nombres;
                        $convenioGrupoFamiliar[$key + 1]['p_apellido'] = $personaBenInterior[0]->p_apellido;
                        $convenioGrupoFamiliar[$key + 1]['s_apellido'] = $personaBenInterior[0]->s_apellido;
                        $convenioGrupoFamiliar[$key + 1]['fecha_nacimiento'] = $personaBenInterior[0]->fecha_nacimiento;
                        $convenioGrupoFamiliar[$key + 1]['id_tipo_asegurado'] = $personaBenInterior[0]->id_tipo_asegurado;
                        $convenioGrupoFamiliar[$key + 1]['tipo_asegurado'] = $personaBenInterior[0]->tipo_asegurado;
                        $convenioGrupoFamiliar[$key + 1]['id_estado_civil'] = $personaBenInterior[0]->id_estado_civil;
                        $convenioGrupoFamiliar[$key + 1]['estado_civil'] = $personaBenInterior[0]->nombre;
                        $convenioGrupoFamiliar[$key + 1]['id_expedido'] = $personaBenInterior[0]->id_dept_exp;
                        $convenioGrupoFamiliar[$key + 1]['expedido'] = $personaBenInterior[0]->sigla;
                        $convenioGrupoFamiliar[$key + 1]['sexo'] = $personaBenInterior[0]->sexo;
                    }
                    return $convenioGrupoFamiliar;
                } else {
                    return $convenioGrupoFamiliar;
                }
            } else {
                $conv = AutorizacionInterior::where('id_persona_interior', $convenio->id_persona_titular)
                    ->where('estado', true)
                    ->first();

                if ($conv) {
                    $titularInterior = $this->busquedaPoblacion($conv->id_persona_interior);
                    $convenioGrupoFamiliar[0]['id'] = $titularInterior[0]->id;
                    $convenioGrupoFamiliar[0]['ci'] = $titularInterior[0]->ci;
                    $convenioGrupoFamiliar[0]['complemento'] = $titularInterior[0]->complemento;
                    $convenioGrupoFamiliar[0]['matricula_seguro'] = $titularInterior[0]->matricula_seguro;
                    $convenioGrupoFamiliar[0]['nombres'] = $titularInterior[0]->nombres;
                    $convenioGrupoFamiliar[0]['p_apellido'] = $titularInterior[0]->p_apellido;
                    $convenioGrupoFamiliar[0]['s_apellido'] = $titularInterior[0]->s_apellido;
                    $convenioGrupoFamiliar[0]['fecha_nacimiento'] = $titularInterior[0]->fecha_nacimiento;
                    $convenioGrupoFamiliar[0]['id_tipo_asegurado'] = $titularInterior[0]->id_tipo_asegurado;
                    $convenioGrupoFamiliar[0]['tipo_asegurado'] = $titularInterior[0]->tipo_asegurado;
                    $convenioGrupoFamiliar[0]['id_estado_civil'] = $titularInterior[0]->id_estado_civil;
                    $convenioGrupoFamiliar[0]['estado_civil'] = $titularInterior[0]->nombre;
                    $convenioGrupoFamiliar[0]['id_expedido'] = $titularInterior[0]->id_dept_exp;
                    $convenioGrupoFamiliar[0]['expedido'] = $titularInterior[0]->sigla;
                    $convenioGrupoFamiliar[0]['sexo'] = $titularInterior[0]->sexo;

                    $grupoFam = AutorizacionInterior::where('id_persona_titular', $conv->id_persona_titular)
                        ->whereNot('id_persona_interior', $conv->id_persona_titular)
                        ->where('estado', true)
                        ->get();
                    if (isset($grupoFam->toArray()[0])) {
                        foreach ($grupoFam as $key => $fam) {
                            $personaBenInterior = $this->busquedaPoblacion($fam->id_persona_interior);
                            $convenioGrupoFamiliar[$key + 1]['id'] = $personaBenInterior[0]->id;
                            $convenioGrupoFamiliar[$key + 1]['ci'] = $personaBenInterior[0]->ci;
                            $convenioGrupoFamiliar[$key + 1]['complemento'] = $personaBenInterior[0]->complemento;
                            $convenioGrupoFamiliar[$key + 1]['matricula_seguro'] = $personaBenInterior[0]->matricula_seguro;
                            $convenioGrupoFamiliar[$key + 1]['nombres'] = $personaBenInterior[0]->nombres;
                            $convenioGrupoFamiliar[$key + 1]['p_apellido'] = $personaBenInterior[0]->p_apellido;
                            $convenioGrupoFamiliar[$key + 1]['s_apellido'] = $personaBenInterior[0]->s_apellido;
                            $convenioGrupoFamiliar[$key + 1]['fecha_nacimiento'] = $personaBenInterior[0]->fecha_nacimiento;
                            $convenioGrupoFamiliar[$key + 1]['id_tipo_asegurado'] = $personaBenInterior[0]->id_tipo_asegurado;
                            $convenioGrupoFamiliar[$key + 1]['tipo_asegurado'] = $personaBenInterior[0]->tipo_asegurado;
                            $convenioGrupoFamiliar[$key + 1]['id_estado_civil'] = $personaBenInterior[0]->id_estado_civil;
                            $convenioGrupoFamiliar[$key + 1]['estado_civil'] = $personaBenInterior[0]->nombre;
                            $convenioGrupoFamiliar[$key + 1]['id_expedido'] = $personaBenInterior[0]->id_dept_exp;
                            $convenioGrupoFamiliar[$key + 1]['expedido'] = $personaBenInterior[0]->sigla;
                            $convenioGrupoFamiliar[$key + 1]['sexo'] = $personaBenInterior[0]->sexo;
                        }
                        return $convenioGrupoFamiliar;
                    } else {
                        return $convenioGrupoFamiliar;
                    }
                } else {
                    $grupoFam = AutorizacionInterior::where('id_persona_titular', $convenio->id_persona_titular)
                        ->where('estado', true)
                        ->get();

                    if (isset($grupoFam->toArray()[0])) {
                        foreach ($grupoFam as $key => $fam) {
                            $personaBenInterior = $this->busquedaPoblacion($fam->id_persona_interior);
                            $convenioGrupoFamiliar[$key + 1]['id'] = $personaBenInterior[0]->id;
                            $convenioGrupoFamiliar[$key + 1]['ci'] = $personaBenInterior[0]->ci;
                            $convenioGrupoFamiliar[$key + 1]['complemento'] = $personaBenInterior[0]->complemento;
                            $convenioGrupoFamiliar[$key + 1]['matricula_seguro'] = $personaBenInterior[0]->matricula_seguro;
                            $convenioGrupoFamiliar[$key + 1]['nombres'] = $personaBenInterior[0]->nombres;
                            $convenioGrupoFamiliar[$key + 1]['p_apellido'] = $personaBenInterior[0]->p_apellido;
                            $convenioGrupoFamiliar[$key + 1]['s_apellido'] = $personaBenInterior[0]->s_apellido;
                            $convenioGrupoFamiliar[$key + 1]['fecha_nacimiento'] = $personaBenInterior[0]->fecha_nacimiento;
                            $convenioGrupoFamiliar[$key + 1]['id_tipo_asegurado'] = $personaBenInterior[0]->id_tipo_asegurado;
                            $convenioGrupoFamiliar[$key + 1]['tipo_asegurado'] = $personaBenInterior[0]->tipo_asegurado;
                            $convenioGrupoFamiliar[$key + 1]['id_estado_civil'] = $personaBenInterior[0]->id_estado_civil;
                            $convenioGrupoFamiliar[$key + 1]['estado_civil'] = $personaBenInterior[0]->nombre;
                            $convenioGrupoFamiliar[$key + 1]['id_expedido'] = $personaBenInterior[0]->id_dept_exp;
                            $convenioGrupoFamiliar[$key + 1]['expedido'] = $personaBenInterior[0]->sigla;
                            $convenioGrupoFamiliar[$key + 1]['sexo'] = $personaBenInterior[0]->sexo;
                        }
                        return $convenioGrupoFamiliar;
                    } else {
                        return [];
                    }
                }
            }
        } else {
            return [];
        }
    }

    public function encontrarEstudiante($idPersona)
    {
        $datosPersonaEstudiante = [];
        $estudiante = Estudiante::where('id_persona', $idPersona)->where('estado_vigencia', true)->where('estado', true)->first();

        if ($estudiante) {
            $personaEstudiante = $this->busquedaPoblacion($idPersona);

            $datosPersonaEstudiante[0]['id'] = $personaEstudiante[0]->id;
            $datosPersonaEstudiante[0]['ci'] = $personaEstudiante[0]->ci;
            $datosPersonaEstudiante[0]['complemento'] = $personaEstudiante[0]->complemento;
            $datosPersonaEstudiante[0]['matricula_seguro'] = $personaEstudiante[0]->matricula_seguro;
            $datosPersonaEstudiante[0]['nombres'] = $personaEstudiante[0]->nombres;
            $datosPersonaEstudiante[0]['p_apellido'] = $personaEstudiante[0]->p_apellido;
            $datosPersonaEstudiante[0]['s_apellido'] = $personaEstudiante[0]->s_apellido;
            $datosPersonaEstudiante[0]['fecha_nacimiento'] = $personaEstudiante[0]->fecha_nacimiento;
            $datosPersonaEstudiante[0]['id_tipo_asegurado'] = $personaEstudiante[0]->id_tipo_asegurado;
            $datosPersonaEstudiante[0]['tipo_asegurado'] = $personaEstudiante[0]->tipo_asegurado;
            $datosPersonaEstudiante[0]['id_estado_civil'] = $personaEstudiante[0]->id_estado_civil;
            $datosPersonaEstudiante[0]['estado_civil'] = $personaEstudiante[0]->nombre;
            $datosPersonaEstudiante[0]['id_expedido'] = $personaEstudiante[0]->id_dept_exp;
            $datosPersonaEstudiante[0]['expedido'] = $personaEstudiante[0]->sigla;
            $datosPersonaEstudiante[0]['sexo'] = $personaEstudiante[0]->sexo;
            return $datosPersonaEstudiante;
        } else {
            return [];
        }
    }

    public function encontrarTitularConvenioEmergencia($idPersona)
    {
        $convenioEmergencia = Convenio::where('id_persona', $idPersona)->where('estado', true)->first();
        //dd($convenioEmergencia);
        if ($convenioEmergencia) {
            $personaEmergencia = $this->busquedaPoblacion($idPersona);
            //dd(count($personaEmergencia) > 0);
            if (count($personaEmergencia) > 0) {
                $datosConveniosEmergencia[0]['id'] = $personaEmergencia[0]->id;
                $datosConveniosEmergencia[0]['ci'] = $personaEmergencia[0]->ci;
                $datosConveniosEmergencia[0]['complemento'] = $personaEmergencia[0]->complemento;
                $datosConveniosEmergencia[0]['matricula_seguro'] = $personaEmergencia[0]->matricula_seguro;
                $datosConveniosEmergencia[0]['nombres'] = $personaEmergencia[0]->nombres;
                $datosConveniosEmergencia[0]['p_apellido'] = $personaEmergencia[0]->p_apellido;
                $datosConveniosEmergencia[0]['s_apellido'] = $personaEmergencia[0]->s_apellido;
                $datosConveniosEmergencia[0]['fecha_nacimiento'] = $personaEmergencia[0]->fecha_nacimiento;
                $datosConveniosEmergencia[0]['id_tipo_asegurado'] = $personaEmergencia[0]->id_tipo_asegurado;
                $datosConveniosEmergencia[0]['tipo_asegurado'] = $personaEmergencia[0]->tipo_asegurado;
                $datosConveniosEmergencia[0]['id_estado_civil'] = $personaEmergencia[0]->id_estado_civil;
                $datosConveniosEmergencia[0]['estado_civil'] = $personaEmergencia[0]->nombre;
                $datosConveniosEmergencia[0]['id_expedido'] = $personaEmergencia[0]->id_dept_exp;
                $datosConveniosEmergencia[0]['expedido'] = $personaEmergencia[0]->sigla;
                $datosConveniosEmergencia[0]['sexo'] = $personaEmergencia[0]->sexo;

                return $datosConveniosEmergencia;
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    public function busquedaPoblacion($busqueda)
    {
        $personas_datos = Persona::join('administracion.tipo_asegurado as ta', 'ta.id', '=', 'persona.id_tipo_asegurado')
            ->leftJoin('administracion.estado_civil as ec', 'ec.id', '=', 'persona.id_estado_civil')
            ->leftJoin('administracion.departamento as dep_exp', 'dep_exp.id', '=', 'persona.id_dept_exp')
            ->select(
                'persona.id',
                'persona.ci',
                'persona.complemento',
                'persona.nombres',
                'persona.p_apellido',
                'persona.s_apellido',
                'persona.sexo',
                'persona.fecha_nacimiento',
                'persona.matricula_seguro',
                'persona.id_tipo_asegurado',
                'ta.tipo_asegurado',
                'ec.nombre',
                'dep_exp.sigla'
            )
            ->where('persona.id', $busqueda)
            ->where('persona.afiliado', true)
            ->get();

        return $personas_datos;
    }
}
