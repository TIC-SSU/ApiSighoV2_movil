<?php

namespace App\Models\Vigencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Administracion\Persona;

class HistorialAsegurado extends Model
{
    use HasFactory;
    protected $table = "vvdd.historial_asegurado";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'estado',
        'estado_vigencia',
        'fecha_inicio',
        'fecha_cesantia',
        'fecha_baja',
        'id_persona',
        'id_tipo_asegurado',
        'id_corresponde',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la tabla Persona
    public function personaHistorialAsegurado(){
        return $this->belongsTo(Persona::class,'id_persona','id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }

    public function tipoAseguradoHistorialAsegurado(){
        return $this->belongsTo(TipoAsegurado::class, 'id_tipo_asegurado', 'id');
    }
}
