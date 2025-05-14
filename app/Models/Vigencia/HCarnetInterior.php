<?php

namespace App\Models\Vigencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\Convenio;

class HCarnetInterior extends Model
{
    use HasFactory;
    protected $table = "vvdd.h_carnet_interior";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'fecha_emision',
        'fecha_vencimiento',
        'motivo',
        'id_autorizacion_interior',
        'id_convenio',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la tabla Autorizacion Interior
    public function autorizacionInteriorHCarnetInterior(){
        return $this->belongsTo(AutorizacionInterior::class,'id_autorizacion_interior', 'id');
    }

    //Relaciones con la tabla Convenio
    public function convenioHCarnetInterior(){
        return $this->belongsTo(Convenio::class,'id_convenio', 'id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
