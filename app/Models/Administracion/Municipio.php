<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importancion de Modelos para las relaciones
use App\Models\User;
use App\Models\Administracion\Provincia;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Zona;

class Municipio extends Model
{
    use HasFactory;
    protected $table = "administracion.municipio";
    protected $connection = "pgsql";
    protected $fillable = [
        'id',
        'nombre',
        'id_provincia',
        'sigla_asuss',
        'id_user_created',
        'id_user_updated',
    ];

    //Relaciones con la Tabla Zona
    public function zonaMunicipio(){
        return $this->hasMany(Zona::class,'id_municipio','id');
    }

    //Relaciones con la tabla Provincia
    public function provinciaMunicipio(){
        return $this->belongsTo(Provincia::class,'id_provincia', 'id');
    }

    //Relaciones con la tabla Municipio
    public function personaMunicipio(){
        return $this->hasMany(Persona::class,'id_nacimiento_municipio', 'id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
