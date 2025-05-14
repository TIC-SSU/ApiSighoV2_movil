<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importancion de Modelos para las relaciones
use App\Models\User;

use App\Models\Administracion\Zona;

use App\Models\Afiliacion\Titular;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Afiliacion\Estudiante;
use App\Models\Aportes\Institucion;

class Residencia extends Model
{
    use HasFactory;
    protected $table = "administracion.residencia";
    protected $connection = "pgsql";
    protected $fillable = [
        'direccion',
        'nro_vivienda',
        'id_zona',
        'latitud',
        'longitud',
        'id_user_created',
        'id_user_updated',
    ];

    

    //Relaciones con la tabla Zona
    public function zonaResidencia(){
        return $this->belongsTo(Zona::class,'id_zona','id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }



    // RELACION CON EL ESQUEMA AFILIACION ---
    //Con la tabla titular
    public function residenciaTitular(){
        return $this->hasMany(Titular::class, 'id_residencia','id');
    }
      //Con la tabla Beneficiario 
    public function residenciaBeneficiario(){
        return $this->hasMany(Beneficiario::class, 'id_residencia','id');
    }
      //Con la tabla Estudiante
    public function residenciaEstudiante(){
        return $this->hasMany(Estudiante::class, 'id_residencia','id');
    }

    //RELACION CON ESQUENAS APORTES
    //tabla INSTITUCION
    public function residenciaInstitucion(){
        return $this->hasMany(Institucion::class, 'id_residencia','id');
    }
}
