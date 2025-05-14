<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Plataforma\Consultorio;
use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\AsignacionHorario;

class AsignacionConsultorio extends Model
{
    use HasFactory;
    protected $table = "plataforma.asignacion_consultorio";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'id_especialista',
        'id_consultorio',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la Tabla Asignacion Horario
    public function asignacionHorarioAsignacionConsultorio(){
        return $this->hasMany(AsignacionHorario::class, 'id_asignacion_consultorio','id');
    }

    //Relaciones con la Tabla Consultorio
    public function consultorioAsignacionConsultorio(){
        return $this->belongsTo(Consultorio::class,'id_consultorio', 'id');
    }

    //Relaciones con la tabla Especialista
    public function especialistaAsignacionConsultorio(){
        return $this->belongsTo(Especialista::class,'id_especialista', 'id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
