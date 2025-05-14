<?php

namespace App\Models\Afiliacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Afiliacion\Carrera;
use App\Models\User;

use App\Models\Administracion\Persona;
use App\Models\Administracion\Residencia;

class Estudiante extends Model
{
    use HasFactory;
    protected $table = 'afiliacion.estudiante';
    protected $connection = 'pgsql';

    protected $fillable = [
        'id',
        'id_persona',
        'id_carrera',
        'semestre',
        'registro_universitario',
        'gestion',
        'foto_nombre',
        'observacion',
        'fecha_afiliacion',
        'fecha_solicitud_afiliacion',
        'telefono_referencia',
        'nombre_referencia',
        'estado_validar',
        'telefono',
        'correo_institucional',
        'fecha_reafiliacion',
        'estado',
        'estado_vigencia',
        'afiliacion',
        'fecha_baja',
        'migrado',
        'id_residencia',
        'fecha_fin_vigencia',
        'id_user_created',
        'id_user_updated',
    ];

    protected $casts = [
        'fecha_afiliacion' => 'date',
        'fecha_solicitud_afiliacion' => 'date',
        'fecha_reafiliacion' => 'date',
        'estado' => 'boolean',
        'afiliacion' => 'boolean',
    ];

    public function carreraEstudiante()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera');
    }
    public function estudianteDocumentos(){
        return $this->belongsTo(Estudiante::class,'id_estudiante');
    }
//--RELACIONES CON ESQUEMA ADMINISTRACION
    public function personaEstudiante() {
        return $this->belongsTo(Persona::class, 'id_persona','id');
    }
    public function estudianteResidencia() {
        return $this->belongsTo(Residencia::class, 'id_residencia','id');
    }

    public function userCreated(){
        return $this->belongsTo(User::class, 'id_user_created');
    }

    public function userUpdated(){
        return $this->belongsTo(User::class, 'id_user_updated');
    }

    
}
