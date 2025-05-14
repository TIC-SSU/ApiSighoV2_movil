<?php

namespace App\Models\Vigencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Vigencia\MotivoBaja;
use App\Models\Administracion\Persona;
use App\Models\Administracion\TipoAsegurado;
use App\Models\Aportes\Institucion;

class BajaInstitucional extends Model
{
    use HasFactory;
    protected $table = "vvdd.baja_institucional";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'fecha_baja_inst',
        'fecha_baja_ssu',
        'id_persona',
        'id_motivo_baja',
        'descripcion_motivo',
        'nro_documento_referencia',
        'baja_definitiva',
        'baja_automatica',
        'observacion',
        'registro_tratado',
        'id_tipo_asegurado',
        'id_corresponde',
        'id_institucion_baja',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la tabla Motivo Baja
    public function motivoBajaBajaInstitucional(){
        return $this->belongsTo(MotivoBaja::class, 'id_motivo_baja','id');
    }

    //Relaciones con la tabla Tipo Asegurado
    public function tipoAseguradoBajaInstitucional(){
        return $this->belongsTo(TipoAsegurado::class, 'id_tipo_asegurado', 'id');
    }

    //Relaciones con la tabla Institucion
    public function institucionBajaInstitucional(){
        return $this->belongsTo(Institucion::class, 'id_institucion_baja', 'id');
    }

    //Relaciones con la tabla Persona
    public function personaBajaInstitucional(){
        return $this->belongsTo(Persona::class,'id_persona','id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
