<?php

namespace App\Models\Vigencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Administracion\Residencia;
use App\Models\Vigencia\OrdenServicio;

class InstitucionConvenio extends Model
{
    use HasFactory;
    protected $table = "vvdd.institucion_convenio";
    protected $connection = 'pgsql';
    protected $fillable = [
        'nombre_institucion',
        'sigla',
        'telefono',
        'correo',
        'estado',
        'servicio',
        'descripcion',
        'id_residencia',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la tabla Orden Servicio
    public function ordenServicioInstitucionConvenio(){
        return $this->hasMany(OrdenServicio::class,'id_institucion_convenio','id');
    }

    //Relaciones con la tabla Zona
    public function residenciaInstitucionConvenio(){
        return $this->belongsTo(Residencia::class,'id_residencia','id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
