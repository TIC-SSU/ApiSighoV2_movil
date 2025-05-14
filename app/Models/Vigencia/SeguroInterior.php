<?php

namespace App\Models\Vigencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Administracion\Departamento;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Residencia;
use App\Models\Vigencia\SolicitudPMedica;

class SeguroInterior extends Model
{
    use HasFactory;
    protected $table = "vvdd.seguro_interior";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'nombre_seguro',
        'sigla_seguro',
        'nit',
        'nro_empleador',
        'id_residencia',
        'correo',
        'direccion_seguro',
        'telefono',
        'fax',
        'id_usuario_responsable',
        'id_rep_legal',
        'estado',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la tabla Solicitud P Medica
    public function solicitudPMedicaSeguroInterior(){
        return $this->hasMany(SolicitudPMedica::class, 'id_seguro_interior','id');
    }

    //Relaciones con la tabla Usuario
    public function usuarioSeguroInterior(){
        return $this->belongsTo(User::class,'id_usuario_responsable','id');
    }

    //Relaciones con la tabla Persona
    public function personaSeguroInterior(){
        return $this->belongsTo(Persona::class,'id_rep_legal','id');
    }

    //Relaciones con la tabla Residencia
    public function residenciaSeguroInterior(){
        return $this->belongsTo(Residencia::class,'id_residencia','id');
    }

    //Relaciones con la tabla Autorizacion Interior
    public function autorizacionInteriorSeguroInterior(){
        return $this->hasMany(AutorizacionInterior::class, 'id_seguro_interior', 'id');    
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
