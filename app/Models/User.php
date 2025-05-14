<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

// Importando lo modelos necesarios
use App\Models\Administracion\Comunicado;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\Especialidad;
use App\Models\Administracion\EstadoCivil;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Notificacion;
use App\Models\Administracion\Persona;
use App\Models\Administracion\PersonalSsu;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Servicio;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Administracion\Unidad;
use App\Models\Administracion\Zona;
use App\Models\Administracion\AccesoAsussToken;

use App\Models\Vigencia\AseguradoSolicitante;
use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\AutorizacionIntEspecialidad;
use App\Models\Vigencia\AutorizacionIntServicio;
use App\Models\Vigencia\BajaInstitucional;
use App\Models\Vigencia\BajaMedica;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\ConvenioEspecialidad;
use App\Models\Vigencia\ConvenioServicio;
use App\Models\Vigencia\HCarnetInterior;
use App\Models\Vigencia\HistorialAsegurado;
use App\Models\Vigencia\InstitucionConvenio;
use App\Models\Vigencia\MotivoBaja;
use App\Models\Vigencia\OrdenServicio;
use App\Models\Vigencia\SeguroInterior;
use App\Models\Vigencia\SolicitudPMedica;
use App\Models\Vigencia\SolMedicaEspecialidad;
use App\Models\Vigencia\SolMedicaServicio;
use App\Models\Vigencia\TipoConvenio;

use App\Models\Plataforma\Agenda;
use App\Models\Plataforma\AsignacionConsultorio;
use App\Models\Plataforma\AsignacionHorario;
use App\Models\Plataforma\Consultorio;
use App\Models\Plataforma\Dia;
use App\Models\Plataforma\EspecialidadHabilitadoServicio;
use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\EspecialistaHabilitadoServicio;
use App\Models\Plataforma\Horario;
use App\Models\Plataforma\Motivo;
use App\Models\Plataforma\ServicioPlataforma;
use App\Models\Plataforma\SuspensionHorario;
use App\Models\Plataforma\SuspensionVacacion;
use App\Models\Plataforma\TipoAtencionAsegurado;
// aportes

use App\Models\Aportes\AporteInstitucional;
use App\Models\Aportes\AporteVoluntario;
use App\Models\Aportes\ClaseAportante;
use App\Models\Aportes\Comprobante;
use App\Models\Aportes\ComprobanteTemp;
use App\Models\Aportes\ContratoVoluntario;
use App\Models\Aportes\FormaPago;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\Mes;
use App\Models\Aportes\PlanillaBajaMedica;
use App\Models\Aportes\TasaInteres;
use App\Models\Aportes\Ufv;

//Afiliacion
use App\Models\Afiliacion\HistorialMatriculaEstudiante;


use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use  HasFactory, Notifiable;
    // use HasRoles;
    protected $table = 'administracion.users';
    protected $conection = 'pgsql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_persona',
        'id_user_created',
        'id_user_updated',
        'session_token',
        'imagen',
        'estado',
        'last_login_at',


    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    //obtener foto del usuario
    // public function fotoUsuario()
    // {
    //     try {
    //         if ($this->imagen) {
    //             $path = 'Administracion/Usuarios/Perfil/' . $this->imagen;
    //             $imageData = Storage::disk('ftp')->get($path);

    //             return 'data:image/jpeg;base64,' . base64_encode($imageData);
    //         } else {
    //             return asset('img/default-user.png'); // Imagen por defecto si no tiene foto
    //         }
    //     } catch (\Exception $e) {
    //         return asset('img/default-user.png'); // En caso de error, devuelve la imagen por defecto
    //     }
    // }

    // //enviar id a ruta editar
    // public function adminlte_profile_url()
    // {
    //     return route('administracion.usuarios.edit', ['id' => $this->id]);
    // }

    //Relaciones con la tabla Comunicado
    public function comunicadoEmisor()
    {
        return $this->hasMany(Comunicado::class, 'id_usuario_emisor', 'id');
    }

    public function comunicadoCreador()
    {
        return $this->hasMany(Comunicado::class, 'id_user_created', 'id');
    }

    public function comunicadoEditor()
    {
        return $this->hasMany(Comunicado::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Departamento
    public function departamentoCreador()
    {
        return $this->hasMany(Departamento::class, 'id_user_created', 'id');
    }

    public function departamentoEditor()
    {
        return $this->hasMany(Departamento::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Especialidad
    public function especialidadCreador()
    {
        return $this->hasMany(Especialidad::class, 'id_user_created', 'id');
    }

    public function especialidadEditor()
    {
        return $this->hasMany(Especialidad::class, 'id_user_updated', 'id');
    }

    //Relaciones con la Tabla Estado Civil
    public function estadoCivilCreador()
    {
        return $this->hasMany(EstadoCivil::class, 'id_user_created', 'id');
    }

    public function estadoCivilEditor()
    {
        return $this->hasMany(EstadoCivil::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Municipio
    public function municipioCreador()
    {
        return $this->hasMany(Municipio::class, 'id_user_created', 'id');
    }

    public function municipioEditor()
    {
        return $this->hasMany(Municipio::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Notificacion
    public function notificacionEmisor()
    {
        return $this->hasMany(Notificacion::class, 'id_usuario_emisor', 'id');
    }

    public function notificacionReceptor()
    {
        return $this->hasMany(Notificacion::class, 'id_usuario_receptor', 'id');
    }

    public function notificacionCreador()
    {
        return $this->hasMany(Notificacion::class, 'id_user_created', 'id');
    }

    public function notificacionEditor()
    {
        return $this->hasMany(Notificacion::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Persona
    public function personaCreador()
    {
        return $this->hasMany(Persona::class, 'id_user_created', 'id');
    }

    public function personaEditor()
    {
        return $this->hasMany(Persona::class, 'id_user_updated', 'id');
    }

    public function personaUser()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    //Relaciones con la tabla Personal Ssu
    public function personalSsuCreador()
    {
        return $this->hasMany(PersonalSsu::class, 'id_user_created', 'id');
    }

    public function personalSsuEditor()
    {
        return $this->hasMany(PersonalSsu::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Provincia
    public function provinciaCreador()
    {
        return $this->hasMany(Provincia::class, 'id_user_created', 'id');
    }

    public function provinciaEditor()
    {
        return $this->hasMany(Provincia::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Residencia
    public function residenciaCreador()
    {
        return $this->hasMany(Residencia::class, 'id_user_created', 'id');
    }

    public function residenciaEditor()
    {
        return $this->hasMany(Residencia::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Servicio
    public function servicioCreador()
    {
        return $this->hasMany(Servicio::class, 'id_user_created', 'id');
    }

    public function servicioEditor()
    {
        return $this->hasMany(Servicio::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla TipoAsegurado
    public function tipoAseguradoCreador()
    {
        return $this->hasMany(TipoAsegurado::class, 'id_user_created', 'id');
    }

    public function tipoAseguradoEditor()
    {
        return $this->hasMany(TipoAsegurado::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Unidad
    public function unidadCreador()
    {
        return $this->hasMany(Unidad::class, 'id_user_created', 'id');
    }

    public function unidadEditor()
    {
        return $this->hasMany(Unidad::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Zona
    public function zonaCreador()
    {
        return $this->hasMany(Zona::class, 'id_user_created', 'id');
    }

    public function zonaEditor()
    {
        return $this->hasMany(Zona::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Acceso Asuss Token
    public function accesoAsussTokenCreador()
    {
        return $this->hasMany(AccesoAsussToken::class, 'id_user_created', 'id');
    }


    //Relaciones Esquema VVDD 

    //Relaciones con la tabla Asegurado Solicitante

    public function aseguradoSolicitanteCreador()
    {
        return $this->hasMany(AseguradoSolicitante::class, 'id_user_created', 'id');
    }

    public function aseguradoSolicitanteEditor()
    {
        return $this->hasMany(AseguradoSolicitante::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Autoriizacion Interior
    public function autorizacionInteriorCreador()
    {
        return $this->hasMany(AutorizacionInterior::class, 'id_user_created', 'id');
    }

    public function autorizacionInteriorEditor()
    {
        return $this->hasMany(AutorizacionInterior::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Autorizacion Int Especialidad
    public function autorizacionIntEspecialidadCreador()
    {
        return $this->hasMany(AutorizacionIntEspecialidad::class, 'id_user_created', 'id');
    }

    public function autorizacionIntEspecialidadEditor()
    {
        return $this->hasMany(AutorizacionIntEspecialidad::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Autorizacion Int Servicio
    public function autorizacionIntServicioCreador()
    {
        return $this->hasMany(AutorizacionIntServicio::class, 'id_user_created', 'id');
    }

    public function autorizacionIntServicioEditor()
    {
        return $this->hasMany(AutorizacionIntServicio::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Baja Institucional
    public function bajaInstitucionalCreador()
    {
        return $this->hasMany(BajaInstitucional::class, 'id_user_created', 'id');
    }

    public function bajaInstitucionalEditor()
    {
        return $this->hasMany(BajaInstitucional::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Baja Medica

    public function bajaMedicaCreador()
    {
        return $this->hasMany(BajaMedica::class, 'id_user_created', 'id');
    }

    public function bajaMedicaEditor()
    {
        return $this->hasMany(BajaMedica::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Convenio
    public function convenioCreador()
    {
        return $this->hasMany(Convenio::class, 'id_user_created', 'id');
    }

    public function convenioEditor()
    {
        return $this->hasMany(Convenio::class, 'id_user_updated', 'id');
    }

    //Relaciones con la Convenio Especialidad
    public function convenioEspecialidadCreador()
    {
        return $this->hasMany(ConvenioEspecialidad::class, 'id_user_created', 'id');
    }

    public function convenioEspecialidadEditor()
    {
        return $this->hasMany(ConvenioEspecialidad::class, 'id_user_updated', 'id');
    }

    //Relaciones con la Tabla Convenio Servicio
    public function convenioServicioCreador()
    {
        return $this->hasMany(ConvenioServicio::class, 'id_user_created', 'id');
    }

    public function convenioServicioEditor()
    {
        return $this->hasMany(ConvenioServicio::class, 'id_user_updated', 'id');
    }

    //Relaciones con la Tabla HCarnet Interior

    public function hCarnetInteriorCreador()
    {
        return $this->hasMany(HCarnetInterior::class, 'id_user_created', 'id');
    }

    public function hCarnetInteriorEditor()
    {
        return $this->hasMany(HCarnetInterior::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Historial Asegurado
    public function historialAseguradoCreador()
    {
        return $this->hasMany(HistorialAsegurado::class, 'id_user_created', 'id');
    }

    public function historialAseguradoEditor()
    {
        return $this->hasMany(HistorialAsegurado::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Institucion Convenio
    public function institucionConvenioCreador()
    {
        return $this->hasMany(InstitucionConvenio::class, 'id_user_created', 'id');
    }

    public function institucionConvenioEditor()
    {
        return $this->hasMany(InstitucionConvenio::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Motivo Baja
    public function motivoBajaCreador()
    {
        return $this->hasMany(MotivoBaja::class, 'id_user_created', 'id');
    }

    public function motivoBajaEditor()
    {
        return $this->hasMany(MotivoBaja::class, 'id_user_updated', 'id');
    }

    //Relaciones con la Tabla Orden Servicio
    public function ordenServicioCreador()
    {
        return $this->hasMany(OrdenServicio::class, 'id_user_created', 'id');
    }

    public function ordenServicioEditor()
    {
        return $this->hasMany(OrdenServicio::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla SeguroInterior
    public function seguroInteriorCreador()
    {
        return $this->hasMany(SeguroInterior::class, 'id_user_created', 'id');
    }

    public function seguroInteriorEditor()
    {
        return $this->hasMany(SeguroInterior::class, 'id_user_updated', 'id');
    }

    //Relaciones con SolicitudPMedica
    public function solicitudPMedicaCreador()
    {
        return $this->hasMany(SolicitudPMedica::class, 'id_user_created', 'id');
    }

    public function solicitudPMedicaEditor()
    {
        return $this->hasMany(SolicitudPMedica::class, 'id_user_updated', 'id');
    }

    //Relaciones con SolMedica Especialidad
    public function solMedicaEspecialidadCreador()
    {
        return $this->hasMany(SolMedicaEspecialidad::class, 'id_user_created', 'id');
    }

    public function solMedicaEspecialidadEditor()
    {
        return $this->hasMany(SolMedicaEspecialidad::class, 'id_user_updated', 'id');
    }

    //Relaciones con SolMedica Servicio
    public function solMedicaServicioCreador()
    {
        return $this->hasMany(SolMedicaServicio::class, 'id_user_created', 'id');
    }

    public function solMedicaServicioEditor()
    {
        return $this->hasMany(SolMedicaServicio::class, 'id_user_updated', 'id');
    }

    //Relaciones con Tipo Convenio
    public function tipoConvenioCreador()
    {
        return $this->hasMany(TipoConvenio::class, 'id_user_created', 'id');
    }

    public function tipoConvenioEditor()
    {
        return $this->hasMany(TipoConvenio::class, 'id_user_updated', 'id');
    }

    //Relaciones Esquema Plataforma 

    //Relaciones con la tabla Agenda

    public function agendaCreador()
    {
        return $this->hasMany(Agenda::class, 'id_user_created', 'id');
    }

    public function agendaEditor()
    {
        return $this->hasMany(Agenda::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Asignacion Consultorio
    public function asignacionConsultorioCreador()
    {
        return $this->hasMany(AsignacionConsultorio::class, 'id_user_created', 'id');
    }

    public function asignacionConsultorioEditor()
    {
        return $this->hasMany(AsignacionConsultorio::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Asignacion Horario
    public function asignacionHorarioCreador()
    {
        return $this->hasMany(AsignacionHorario::class, 'id_user_created', 'id');
    }

    public function asignacionHorarioEditor()
    {
        return $this->hasMany(AsignacionHorario::class, 'id_user_updated', 'id');
    }

    //Relaciones con la Tabla Consultorio
    public function consultorioCreador()
    {
        return $this->hasMany(Consultorio::class, 'id_user_created', 'id');
    }

    public function consultorioEditor()
    {
        return $this->hasMany(Consultorio::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Dia
    public function diaCreador()
    {
        return $this->hasMany(Dia::class, 'id_user_created', 'id');
    }

    public function diaEditor()
    {
        return $this->hasMany(Dia::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Especialidad Habilitado Servicio
    public function especialidadHabilitadoServicioCreador()
    {
        return $this->hasMany(EspecialidadHabilitadoServicio::class, 'id_user_created', 'id');
    }

    public function especialidadHabilitadoServicioEditor()
    {
        return $this->hasMany(EspecialidadHabilitadoServicio::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Especialista
    public function especialistaCreador()
    {
        return $this->hasMany(Especialista::class, 'id_user_created', 'id');
    }

    public function especialistaEditor()
    {
        return $this->hasMany(Especialista::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Especialista Habilitado Servicio
    public function especialistaHabilitadoServicioCreador()
    {
        return $this->hasMany(EspecialistaHabilitadoServicio::class, 'id_user_created', 'id');
    }

    public function especialistaHabilitadoServicioEditor()
    {
        return $this->hasMany(EspecialistaHabilitadoServicio::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Horario
    public function HorarioCreador()
    {
        return $this->hasMany(Horario::class, 'id_user_created', 'id');
    }

    public function horarioEditor()
    {
        return $this->hasMany(Horario::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Motivo
    public function motivoCreador()
    {
        return $this->hasMany(Motivo::class, 'id_user_created', 'id');
    }

    public function motivoEditor()
    {
        return $this->hasMany(Motivo::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Servicio Plataforma
    public function servicioPlataformaCreador()
    {
        return $this->hasMany(ServicioPlataforma::class, 'id_user_created', 'id');
    }

    public function servicioPlataformaEditor()
    {
        return $this->hasMany(ServicioPlataforma::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Suspension Horario
    public function suspensionHorarioCreador()
    {
        return $this->hasMany(SuspensionHorario::class, 'id_user_created', 'id');
    }

    public function suspensionHorarioEditor()
    {
        return $this->hasMany(SuspensionHorario::class, 'id_user_updated', 'id');
    }

    //Relaciones con la tabla Suspension Vacacion
    public function suspensionVacacionCreador()
    {
        return $this->hasMany(SuspensionVacacion::class, 'id_user_created', 'id');
    }

    public function suspensionVacacionEditor()
    {
        return $this->hasMany(SuspensionVacacion::class, 'id_user_updated', 'id');
    }
    public function suspensionVacacionAnula()
    {
        return $this->hasMany(SuspensionVacacion::class, 'id_user_anulacion', 'id');
    }

    //Relaciones con lla tabla Tipo Atencion Asegurado
    public function tipoAtencionAseguradoCreador()
    {
        return $this->hasMany(TipoAtencionAsegurado::class, 'id_user_created', 'id');
    }

    public function tipoAtencionAseguradoEditor()
    {
        return $this->hasMany(TipoAtencionAsegurado::class, 'id_user_updated', 'id');
    }

    // Relacion con la tabla AporteInstitucional
    public function aporteInstitucionalCreador()
    {
        return $this->hasMany(AporteInstitucional::class, 'id_user_created', 'id');
    }

    public function aporteInstitucionalEditor()
    {
        return $this->hasMany(AporteInstitucional::class, 'id_user_updated', 'id');
    }
    // Relacion con la tabla AporteVoluntario
    public function aporteVoluntarioCreador()
    {
        return $this->hasMany(AporteVoluntario::class, 'id_user_created', 'id');
    }

    public function aporteVoluntarioEditor()
    {
        return $this->hasMany(AporteVoluntario::class, 'id_user_updated', 'id');
    }
    // Relacion con la tabla ClaseAportante
    public function claseAportanteCreador()
    {
        return $this->hasMany(ClaseAportante::class, 'id_user_created', 'id');
    }

    public function claseAportanteEditor()
    {
        return $this->hasMany(ClaseAportante::class, 'id_user_updated', 'id');
    }
    // Relacion con la tabla Comprobante
    public function comprobateCreador()
    {
        return $this->hasMany(Comprobante::class, 'id_user_created', 'id');
    }

    public function comprobateEditor()
    {
        return $this->hasMany(Comprobante::class, 'id_user_updated', 'id');
    }

    // Relacion con la tabla Comprobante Temp
    public function comprobateTempCreador()
    {
        return $this->hasMany(ComprobanteTemp::class, 'id_user_created', 'id');
    }

    public function comprobateTempEditor()
    {
        return $this->hasMany(ComprobanteTemp::class, 'id_user_updated', 'id');
    }


    // Relacion con la tabla ContratoVoluntario
    public function contratoVoluntarioCreador()
    {
        return $this->hasMany(ContratoVoluntario::class, 'id_user_created', 'id');
    }

    public function contratoVoluntarioEditor()
    {
        return $this->hasMany(ContratoVoluntario::class, 'id_user_updated', 'id');
    }
    // Relacion con la tabla FormaPago
    public function formaPagoCreador()
    {
        return $this->hasMany(FormaPago::class, 'id_user_created', 'id');
    }

    public function formaPagoEditor()
    {
        return $this->hasMany(FormaPago::class, 'id_user_updated', 'id');
    }
    // Relacion con la tabla Institucion
    public function institucionCreador()
    {
        return $this->hasMany(Institucion::class, 'id_user_created', 'id');
    }

    public function institucionEditor()
    {
        return $this->hasMany(Institucion::class, 'id_user_updated', 'id');
    }

    public function ResponsableInstitucion()
    {
        return $this->hasMany(Institucion::class, 'id_usuario_responsable', 'id');
    }
    // Relacion con la tabla Mes
    public function mesCreador()
    {
        return $this->hasMany(Mes::class, 'id_user_created', 'id');
    }

    public function mesEditor()
    {
        return $this->hasMany(Mes::class, 'id_user_updated', 'id');
    }
    // Relacion con la tabla PlanillaBajaMedica
    public function planillaBajaMedicaCreador()
    {
        return $this->hasMany(PlanillaBajaMedica::class, 'id_user_created', 'id');
    }

    public function planillaBajaMedicaEditor()
    {
        return $this->hasMany(PlanillaBajaMedica::class, 'id_user_updated', 'id');
    }
    // Relacion con la tabla TasaInteres
    public function tasaInteresCreador()
    {
        return $this->hasMany(TasaInteres::class, 'id_user_created', 'id');
    }

    public function tasaInteresEditor()
    {
        return $this->hasMany(TasaInteres::class, 'id_user_updated', 'id');
    }
    // Relacion con la tabla Ufv
    public function ufvCreador()
    {
        return $this->hasMany(Ufv::class, 'id_user_created', 'id');
    }

    public function ufvEditor()
    {
        return $this->hasMany(Ufv::class, 'id_user_updated', 'id');
    }

    //Relacion con el esquema de AFILIACION

    //Relacion con la tabla Historial Mtriucla Estudiante

    public function historialMatriculaEstudianteCreador()
    {
        return $this->hasMany(HistorialMatriculaEstudiante::class, 'id_user_created', 'id');
    }

    public function historialMatriculaEstudianteEditor()
    {
        return $this->hasMany(HistorialMatriculaEstudiante::class, 'id_user_updated', 'id');
    }
}
