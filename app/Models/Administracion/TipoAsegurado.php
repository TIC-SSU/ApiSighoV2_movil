<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importancion de Modelos para las relaciones
use App\Models\User;
use App\Models\Administracion\Persona;
use App\Models\Vigencia\HistorialAsegurado;

class TipoAsegurado extends Model
{
    use HasFactory;
    protected $table = "administracion.tipo_asegurado";
    protected $connection = "pgsql";
    protected $fillable = [
        'id',
        'tipo_asegurado',
        'id_user_created',
        'id_user_updated',
    ];

    //Relaciones con la Tabla Persona
    public function personaTipoAsegurado(){
        return $this->hasMany(Persona::class,'id_tipo_asegurado','id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }

    public function historialAseguradoTipoAsegurado(){
        return $this->hasMany(HistorialAsegurado::class, 'id_tipo_asegurado', 'id');
    }
}
