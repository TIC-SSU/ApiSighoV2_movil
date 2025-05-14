<?php

namespace App\Models\Vigencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;
use App\Models\Administracion\Persona;
use App\Models\Vigencia\SolicitudPMedica;

class AseguradoSolicitante extends Model
{
    use HasFactory;
    protected $table = "vvdd.asegurado_solicitante";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'id_solicitud_p_medica',
        'id_asegurado_solicitante',
        'id_asegurado_titular',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la tabla Persona
    public function personaAseguradoSolicitante(){
        return $this->belongsTo(Persona::class,'id_asegurado_solicitante','id');
    }

    public function personaAseguradoTitular(){
        return $this->belongsTo(Persona::class,'id_asegurado_titular','id');
    }

    //Relaciones con la Tabla Solicitud P Medica
    public function solicitudPMedicaAseguradoSolicitante(){
        return $this->belongsTo(SolicitudPMedica::class,'id_solicitud_p_medica','id');
    }
    

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }


}
