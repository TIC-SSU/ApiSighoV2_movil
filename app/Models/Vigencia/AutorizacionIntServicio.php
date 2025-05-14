<?php

namespace App\Models\Vigencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Administracion\Servicio;

class AutorizacionIntServicio extends Model
{
    use HasFactory;
    protected $table = "vvdd.autorizacionint_servicio";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'id_autorizacion_interior',
        'id_servicio',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relacion con la Tabla Autorizacion Interior
    public function autorizacionInteriorAutorizacionIntServicio(){
        return $this->belongsTo(AutorizacionInterior::class,'id_autorizacion_interior','id');
    }

    //Relacion con la tabla Servicio
    public function servicioAutorizacionIntServicio(){
        return $this->belongsTo(Servicio::class,'id_servicio','id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
