<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Plataforma\Dia;
use App\Models\Plataforma\Especialista;
use App\Models\Administracion\Servicio;
use App\Models\Plataforma\ServicioPlataforma;

class EspecialistaHabilitadoServicio extends Model
{
    use HasFactory;
    protected $table = "plataforma.especialista_habilitado_servicio";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'fecha_inicio',
        'fecha_fin',
        'permanente',
        'estado',
        'id_especialista',
        'id_servicio',
        'id_dia',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la tabla Dia
    public function diaEspecialistaHabilitadoServicio(){
        return $this->belongsTo(Dia::class,'id_dia', 'id');
    }

    //Relaciones con la tabla Especialista
    public function especialistaEspecialistaHabilitadoServicio(){
        return $this->belongsTo(Especialista::class,'id_especialista', 'id');
    }

    //Relaciones con la tabla Servicio
    public function servicioEspecialistaHabilitadoServicio(){
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
