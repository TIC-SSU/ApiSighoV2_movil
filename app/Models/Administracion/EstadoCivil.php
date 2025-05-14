<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importancion de Modelos para las relaciones
use App\Models\User;
use App\Models\Administracion\Persona;

class EstadoCivil extends Model
{
    use HasFactory;
    protected $table = "administracion.estado_civil";
    protected $connection = "pgsql";
    protected $fillable = [
        'nombre',
        'sigla_asuss',   
        'id_user_created',
        'id_user_updated',
    ];

    //Relaciones con la Tabla Persona
    public function personaEstadoCivil(){
        return $this->hasMany(Persona::class,'id_estado_civil','id');
    }

    //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
