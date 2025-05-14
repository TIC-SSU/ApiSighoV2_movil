<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importancion de Modelos para las relaciones
use App\Models\User;

use App\Models\Vigencia\AutorizacionIntEspecialidad;
use App\Models\Vigencia\BajaMedica;
use App\Models\Vigencia\ConvenioEspecialidad;
use App\Models\Vigencia\OrdenServicio;
use App\Models\Vigencia\SolMedicaEspecialidad;

use App\Models\Plataforma\EspecialidadHabilitadoServicio;
use App\Models\Plataforma\Especialista;
use App\Models\Vigencia\Convenio;

class Especialidad extends Model
{
    use HasFactory;
    protected $table = "administracion.especialidad";
    protected $connection = "pgsql";
    protected $fillable = [
        'id',
        'especialidad',
        'sigla',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }

    //Relaciones con el Esquema VVDD
    //Relaciones con la tabla AutorizacionIntEspecialidad
    public function autorizacionIntEspecialidadEspecialidad(){
        return $this->hasMany(Especialidad::class,'id_especialidad','id');
    }

    //Relaciones con la Tabla Baja Medica
    public function bajaMedicaEspecialidad(){
        return $this->hasMany(BajaMedica::class, 'id_especialidad','id');
    }

    //Relaciones con la Tabla Convenio Especialidad
    public function convenioEspecialidadEspecialidad(){
        return $this->hasMany(Convenio::class, 'id_especialidad','id');
    }

    //Relaciones con la tabla Orden Servicio
    public function ordenServicioEspecialidad(){
        return $this->hasMany(OrdenServicio::class,'id_especialidad', 'id');
    }

    //Relaciones con la tabla Sol Medica Especialidad
    public function solMedicaEspecialidadEspecialidad(){
        return $this->hasMany(SolMedicaEspecialidad::class,'id_especialidad', 'id');
    }

    //Relaciones con el esquema Plataforma
    //Relaciones con la tabla Especialidad Habilitado Servicio

    public function especialidadHabilitadoServicioEspecialidad(){
        return $this->hasMany(EspecialidadHabilitadoServicio::class,'id_especialidad', 'id');
    }

    //Relaciones con la tabla Especialista
    public function especialistaEspecialidad(){
        return $this->hasMany(Especialista::class,'id_especialidad', 'id');
    }
}
