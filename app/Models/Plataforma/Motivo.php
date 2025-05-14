<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Plataforma\SuspensionHorario;
use App\Models\Plataforma\SuspensionVacacion;

class Motivo extends Model
{
    use HasFactory;
    protected $table = "plataforma.motivo";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'nombre_motivo',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la tabla Suspension Vacacion
    public function suspensionVacacionMotivo(){
        return $this->hasMany(SuspensionVacacion::class,'id_motivo', 'id');
    }

    //Relaciones con la Tabla Suspension Horario
    public function suspensionHorarioMotivo(){
        return $this->hasMany(SuspensionHorario::class,'id_motivo', 'id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
