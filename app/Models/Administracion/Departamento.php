<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importancion de Modelos para las relaciones
use App\Models\User;

use App\Models\Administracion\Persona;
use App\Models\Administracion\Provincia;
use App\Models\Vigencia\SeguroInterior;

class Departamento extends Model
{
    use HasFactory;
    protected $table = "administracion.departamento";
    protected $connection = "pgsql";
    protected $fillable = [
        'id',
        'nombre',
        'sigla',
        'id_user_created',
        'id_user_updated',
    ];

    //Relaciones con la Tabla Provincia
    public function provinciaDepartamento(){
        return $this->hasMany(Provincia::class,'id_departamento','id');
    }

    //Relaciones con la tabla Persona
    public function personaDepartamento(){
        return $this->hasMany(Persona::class,'id_dept_exp','id');
    }

    //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }

    //Relaciones con el Esquema VVDD
    //Relaciones con la tabla Seguro Interior
    public function seguroInteriorDepartamento(){
        return $this->hasMany(SeguroInterior::class,'id_departamento', 'id');
    }
}
