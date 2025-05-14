<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Plataforma\AsignacionHorario;

class Horario extends Model
{
    use HasFactory;
    protected $table = "plataforma.horario";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'hora_inicio',
        'hora_fin',
        'intervalo',
        'turno',
        'numero_paciente',
        'id_user_created',
        'id_user_updated',
        'estado',
    ] ;

    //Relaciones con la tabla Asignacion Horario
    public function asignacionHorarioHorario(){
        return $this->hasMany(AsignacionHorario::class, 'id_horario','id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
