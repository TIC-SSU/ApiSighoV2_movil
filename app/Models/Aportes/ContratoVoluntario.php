<?php

namespace App\Models\Aportes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Residencia;
use App\Models\Aportes\ClaseAportante;
use App\Models\Aportes\AporteVoluntario;
use App\Models\Administracion\Zona;
use App\Models\User;

class ContratoVoluntario extends Model
{
    use HasFactory;
    protected $table='aportes.contrato_voluntario';
    protected $connection = 'pgsql';

    protected $fillable=[
    'id',
	'id_persona',
	'direccion',
	'telefono_1',
    'telefono_2',
    'fecha_inicio_contrato',
    'fecha_fin_contrato',
    'descripcion_contrato',
    'id_clase_aportante',
    'id_zona',
    'numero',
    'estado',
    'correo',
	'id_user_created',
	'id_user_updated',
    'id_residencia',
    ];

    public function personaContratoVoluntario()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }
    
    public function claseAportanteContratoVoluntario()
    {
        return $this->belongsTo(ClaseAportante::class, 'id_clase_aportante', 'id');
    }

    /*public function zonaContratoVoluntario() DEPRECATED JAJAJAJAJAJAJAJAAJ
    {
        return $this->belongsTo(Zona::class, 'id_zona', 'id');
    }*/

    //Relaciones con la Tabla Residencia

    public function residenciaContratoVoluntario(){
        return $this->belongsTo(Residencia::class, 'id_residencia', 'id');
    }
    
    //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }

    //Relaciones Has Many
    public function aporteVoluntarioContratoVoluntario(){
        return $this->hasMany(AporteVoluntario::class, 'id_contrato_voluntario', 'id');
    }
}
