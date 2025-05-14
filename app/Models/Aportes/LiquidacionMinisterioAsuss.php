<?php

namespace App\Models\Aportes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Aportes\Mes;

class LiquidacionMinisterioAsuss extends Model
{
    use HasFactory;
    protected $table ='aportes.liquidacion_ministerio_asuss';
    protected $connection = 'pgsql';
    
    protected $fillable = [
	'nro_instituciones_activos',
	'nro_instituciones_pasivos',
    'nro_trabajadores_activos',
    'nro_trabajadores_pasivos',
    'nro_trabajadores_voluntarios',
    'total_general',
    'total_aporte',
    'tasa',
    'cotizacion',
    'id_mes',
    'gestion',
    'fecha_liquidacion',
    'estado',
    'id_user_created',
	'id_user_updated',
    ];

    public function mesLiquidacion()
    {
        return $this->belongsTo(Mes::class, 'id_mes', 'id');
    }

    //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}

