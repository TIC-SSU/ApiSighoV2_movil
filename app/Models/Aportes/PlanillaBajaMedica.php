<?php

namespace App\Models\Aportes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Aportes\AporteInstitucional;
use App\Models\User;
class PlanillaBajaMedica extends Model
{
    use HasFactory;
    protected $table='aportes.planilla_baja_medica';
    protected $connection = 'pgsql';

    protected $fillable=[
	'id_aporte_i',
	'fecha_inicio',
	'fecha_fin',
	'tipo',
	'dias_baja',
	'monto_baja_medica',
	'id_user_created',
	'id_user_updated',
    ];
	public function aporteInstitucionalPlanillaBajaMedica()
    {
        return $this->belongsTo(AporteInstitucional::class, 'id_aporte_i','id');
    }
      //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
