<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Administracion\Especialidad;
use App\Models\Plataforma\ServicioPlataforma;

class EspecialidadHabilitadoServicio extends Model
{
    use HasFactory;
    protected $table = "plataforma.especialidad_habilitado_servicio";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'fecha_inicio',
        'fecha_fin',
        'permanente',
        'estado',
        'id_especialidad',
        'id_servicio',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la tabla especialidad
    public function especialidadEspecialidadHabilitadoServicio(){
        return $this->belongsTo(Especialidad::class,'id_especialidad', 'id');
    }

    //Relaciones con la tabla Servicio
    public function servicioEspecialidadHabilitadoServicio(){
        return $this->belongsTo(ServicioPlataforma::class,'id_servicio', 'id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
