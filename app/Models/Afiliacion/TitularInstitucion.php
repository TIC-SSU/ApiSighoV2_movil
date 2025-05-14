<?php

namespace App\Models\Afiliacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

use App\Models\Aportes\Institucion;

use App\Models\Afiliacion\Titular;

class TitularInstitucion extends Model
{
    use HasFactory;
    protected $table = 'afiliacion.titular_institucion';
    protected $connection = 'pgsql';
    protected $fillable = [
        'id_titular',
        'id_institucion',
        'estado',
        'fecha_ingreso',
        'cargo_actual',
        'salario',
        'tipo_institucion',
        'item',
        'carga_horaria',
        'titularidad',
        'id_user_created',
	    'id_user_updated', 
    ];
    // -- RELACIONES CON EL ESQUEMA AFILIACION ---
    public function titularInstitucionConTitular(){
        return $this->belongsTo(Titular::class,'id_titular','id');
    }

    //--- RELACION CON EL ESQUEMA APORTES ---
    public function titularInstitucionConInstitucion(){
        return $this->belongsTo(Institucion::class,'id_institucion','id');
    }
    // ---RELACIONES CON ESQUEMA ADMINSITRACION
    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
            return $this->belongsTo(User::class,'id_user_created','id');
        }    
        public function usuarioEditor(){
            return $this->belongsTo(User::class,'id_user_updated','id');
        }
}
