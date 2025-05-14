<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Plataforma\Agenda;
use App\Models\Plataforma\EspecialidadHabilitadoServicio;

class ServicioPlataforma extends Model
{
    use HasFactory;
    protected $table = "plataforma.servicios_plataforma";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'nombre_servicio',
        'sigla',
        'estado',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la Tabla Agenda
    public function agendaServicioPlataforma(){
        return $this->hasOne(Agenda::class,'id_servicios_plataforma','id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }

      //Relaciones con el esquema Plataforma
    //Relaciones con la tabla Especialidad Habilitado Servicio

    public function especialidadHabilitadoServicioServicio(){
        return $this->hasMany(EspecialidadHabilitadoServicio::class,'id_servicio', 'id');
    }
}
