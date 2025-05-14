<?php

namespace App\Models\Afiliacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//Importando los modelos para las Relaciones
use App\Models\User;
use App\Models\Vigencia\BajaMedica;

use App\Models\Administracion\Persona;
use App\Models\Administracion\Residencia;
use App\Models\Vigencia\Convenio;

use App\Models\Afiliacion\TitularInstitucion;

class Titular extends Model
{
    use HasFactory;

    protected $table = 'afiliacion.titular';
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'estado_requisitos',
        'estado_vigencia',
        'telefono_referencia',
        'nombre_referencia',
        'foto_nombre',
        'observacion',
        'fecha_afiliacion',
        'id_persona',
        'telefono',
        'fecha_reafiliacion',
        'correo',
        'estado',
        'tipo_titular',
        'id_residencia',
        'tipo_rentista',
        'fecha_baja',
        'permanente',
        'id_user_created',
	    'id_user_updated', 
    ];

    // -- RELACIONES CON EL ESQUEMA AFILIACION ---
    public function titularConTitularInstitucion(){
        return $this->hasMany(TitularInstitucion::class, 'id_titular','id');
    }

  //Relaciones con el Esquema VVDD
    //Relaciones con la tabla Baja Medica
    public function bajaMedicaTitular(){
        return $this->hasMany(BajaMedica::class, 'id_titular', 'id');
    }
     //Relaciones con la tabla Convenio
    public function convenioAutorizaTitular(){
        return $this->hasMany(Convenio::class,'id_titular_autoriza', 'id');
    }
 //-- RELACIONES CON EL ESQUEMA ADMINISTRACION
    //Relacion de titular con Persona 
    public function titularPersona(){
        return $this->belongsTo(Persona::class, 'id_persona','id');
    }
    //Relacion de titular con Residencia 
    public function titularResidencia(){
        return $this->belongsTo(Residencia::class,'id_residencia','id');
    }
     //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }

    public function residencia()
    {
        return $this->belongsTo(Residencia::class,'id_residencia','id');
    }
}
