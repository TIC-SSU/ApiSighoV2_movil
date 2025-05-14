<?php

namespace App\Models\Aportes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// importacion de relaciones
use App\Models\Aportes\Institucion;
use App\Models\Aportes\Mes;
use App\Models\Aportes\AporteVoluntario;
use App\Models\User;
class ComprobanteTemp extends Model
{
    use HasFactory;
    protected $table='aportes.comprobante_temp';
    protected $connection = 'pgsql';

    protected $fillable= [
	'id_institucion',
	'gestion',
	'cantidad_eventuales',
	'cantidad_permanentes',
	'total_salario_cotizable',
	'reintegro',
	'beneficio_social',
	'id_mes',
	'id_user_created',
	'id_user_updated',
    ];

    //Relaciones con la tabla Comprobante
	public function institucionComprobanteTemp()
    {
        return $this->belongsTo(Institucion::class, 'id_institucion','id');
    }

    public function mesComprobanteTemp()
    {
        return $this->belongsTo(Mes::class, 'id_mes','id');
    }

	  //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
