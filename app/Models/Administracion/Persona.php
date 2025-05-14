<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importancion de Modelos para las relaciones
use App\Models\User;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\EstadoCivil;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Administracion\PersonalSsu;
use App\Models\Administracion\Residencia;

use App\Models\Aportes\AporteInstitucional;
use App\Models\Aportes\AporteInstitucionalTemp;
use App\Models\Aportes\ContratoVoluntario;

use App\Models\Vigencia\AseguradoSolicitante;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\BajaInstitucional;
use App\Models\Vigencia\BajaMedica;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\HistorialAsegurado;
use App\Models\Vigencia\OrdenServicio;
use App\Models\Vigencia\SeguroInterior;

use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\HCarnet;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Afiliacion\HistorialMatriculaEstudiante;

use App\Models\Afiliacion\TitularInstitucion;
use App\Models\Afiliacion\CodigoInstitucionSsu;


use App\Models\Plataforma\SuspensionHorario;

class Persona extends Model
{
    use HasFactory;
    protected $table = "administracion.persona";
    protected $connection = "pgsql";
    protected $fillable = [
        'id',
        'ci',
        'complemento',
        'nombres',
        'p_apellido',
        's_apellido',
        'sexo',
        'id_estado_civil',
        'id_nacimiento_municipio',
        'id_dept_exp',
        'fecha_nacimiento',
        'matricula_seguro',
        'id_tipo_asegurado',
        'es_extranjero',
        'estado_asuss',
        'afiliado',
        'grupo_sanguineo',
        'alergias',
        'clave_unica',
        'id_user_created',
        'id_user_updated',
    ];

    //Relaciones con la Tabla Residencia
    public function residenciaPersona()
    {
        return $this->hasMany(Residencia::class, 'id_persona', 'id');
    }

    //Relaciones con la Tabla PersonalSsu
    public function personalSsuPersona()
    {
        return $this->hasMany(PersonalSsu::class, 'id_persona', 'id');
    }
    public function personaHCarnet()
    {
        return $this->hasMany(HCarnet::class, 'id_persona', 'id');
    }
    //Relaciones con la tabla Departamento
    public function departamentoPersona()
    {
        return $this->belongsTo(Departamento::class, 'id_dept_exp', 'id');
    }

    //Relaciones con la tabla Estado Civil
    public function estadoCivilPersona()
    {
        return $this->belongsTo(EstadoCivil::class, 'id_estado_civil', 'id');
    }

    //Relaciones con la tabla Municipio
    public function municipioPersona()
    {
        return $this->belongsTo(Municipio::class, 'id_nacimiento_municipio', 'id');
    }

    //Relaciones con la tabla Tipo Asegurado
    public function tipoAseguradoPersona()
    {
        return $this->belongsTo(TipoAsegurado::class, 'id_tipo_asegurado', 'id');
    }

    //Relaciones con la Tabla Usuario

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }


    //Relaciones con el esquema VVDD -----

    //Relacion con la tabla Asegurado Solicitante
    public function aseguradoSolicitantePersona()
    {
        return $this->hasMany(AseguradoSolicitante::class, 'id_asegurado_solicitante', 'id');
    }

    public function aseguradoTitularPersona()
    {
        return $this->hasMany(AseguradoSolicitante::class, 'id_asegurado_titular', 'id');
    }

    //Relaciones con la tabla Autorizacion Interior
    public function autorizacionInteriorPersonaInterior()
    {
        return $this->hasMany(AutorizacionInterior::class, 'id_persona_interior', 'id');
    }

    public function autorizacionInteriorPersonaInteriorTitular()
    {
        return $this->hasMany(AutorizacionInterior::class, 'id_persona_titular', 'id');
    }

    //Relaciones con la tabla Baja Institucional
    public function bajaInstitucionalPersona()
    {
        return $this->hasMany(BajaInstitucional::class, 'id_persona', 'id');
    }

    //Relaciones con la tabla Baja Medica
    public function bajaMedicaPersona()
    {
        return $this->hasMany(BajaMedica::class, 'id_persona', 'id');
    }

    public function bajaMedicaPersonaEntregado()
    {
        return $this->hasMany(BajaMedica::class, 'id_persona_entregado', 'id');
    }

    //Relaciones con la tabla Convenio
    public function convenioAutorizaPersona()
    {
        return $this->hasMany(Convenio::class, 'id_persona_autoriza', 'id');
    }

    public function convenioPersona()
    {
        return $this->hasMany(Convenio::class, 'id_persona', 'id');
    }

    //Relaciones con la tabla Historial Asegurado
    public function historialAseguradoPersona()
    {
        return $this->hasMany(HistorialAsegurado::class, 'id_persona', 'id');
    }

    //Relaciones con la tabla Orden Servicio
    public function ordenServicioPersonaEntrega()
    {
        return $this->hasMany(OrdenServicio::class, 'id_persona_entregado', 'id');
    }

    public function ordenServicioPersona()
    {
        return $this->hasMany(OrdenServicio::class, 'id_persona', 'id');
    }

    //Relaciones con la tabla Seguro Interior
    public function seguroInteriorPersona()
    {
        return $this->hasMany(SeguroInterior::class, 'id_persona_responsable', 'id');
    }


    //Relaciones con el esquema Plataforma -----

    //Relaciones con la tabla Suspension Horario
    public function suspensionHorarioPersona()
    {
        return $this->hasMany(SuspensionHorario::class, 'id_persona', 'id');
    }

    //Relaciones con el esquema Aportes------
    public function aporteInstitucionalPersona()
    {
        return $this->hasMany(AporteInstitucional::class, 'id_persona', 'id');
    }

    public function aporteInstitucionalTempPersona()
    {
        return $this->hasMany(AporteInstitucionalTemp::class, 'id_persona', 'id');
    }

    //Relacion con la tabla Contrato Voluntario

    public function contratoVoluntarioPersona()
    {
        return $this->hasMany(ContratoVoluntario::class, 'id_persona', 'id');
    }
    //-- RELACIONES CON EL ESQUEMA AFILIACION ----
    public function PersonaTitular()
    {
        return $this->hasMany(Titular::class, 'id_persona', 'id');
    }
    public function PersonaBeneficiario()
    {
        return $this->hasMany(Beneficiario::class, 'id_persona', 'id');
    }
    public function PersonaEstudiante()
    {
        return $this->hasMany(Estudiante::class, 'id_persona', 'id');
    }

    public function historialMatriculaEstudiantePersona()
    {
        return $this->hasMany(HistorialMatriculaEstudiante::class, 'id_persona', 'id');
    }

    //FUNCION PARA OBTENER MATRICULA CALCULADA DE UNA PERSONA
    public function getFormattedMatriculaSeguroAttribute()
    {
        if ($this->tipoAsegurado == 'TITULAR') {
            $titular = Titular::where('id_persona', $this->id)
                ->where('estado', true)->first();
            if (!$titular) return $this->matricula_seguro;

            $institucionTit = TitularInstitucion::where('id_titular', $titular->id)
                ->with('titularInstitucionConInstitucion.codigosInstitucion')
                ->orderBy('id', 'asc')
                ->first();

            if ($institucionTit && $institucionTit->titularInstitucionConInstitucion) {
                $codigoInst = CodigoInstitucionSsu::where('id_institucion', $institucionTit->id_institucion)->first();
                if ($codigoInst) {
                    $codigoTitular = $codigoInst->codigo . '' . $codigoInst->tipo_titular;
                    return $this->matricula_seguro . ' - ' . $codigoTitular;
                }
            }
            return $this->matricula_seguro;
        } elseif (strpos($this->tipoAsegurado, 'BENEFICIARIO') !== false) {
            $beneficiario = Beneficiario::where('id_persona', $this->id)
                ->where('estado', true)->first();
            if (!$beneficiario)
                return $this->matricula_seguro;

            $institucionTit = TitularInstitucion::where('id_titular', $beneficiario->id_titular)
                ->with('titularInstitucionConInstitucion.codigosInstitucion')
                ->orderBy('id', 'asc')
                ->first();

            if ($institucionTit && $institucionTit->titularInstitucionConInstitucion) {
                $codigoInst = CodigoInstitucionSsu::where('id_institucion', $institucionTit->id_institucion)->first();
                if ($codigoInst) {
                    $codBeneficiario = $codigoInst->codigo . '' . $codigoInst->tipo_beneficiario;
                    return $this->matricula_seguro . ' - ' . $codBeneficiario;
                }
            }
            return $this->matricula_seguro;
        } elseif ($this->tipoAsegurado == 'ESTUDIANTE') {
            return $this->matricula_seguro;
        }

        return $this->matricula_seguro;
    }
}
