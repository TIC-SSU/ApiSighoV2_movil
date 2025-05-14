<?php

namespace App\Models\Aportes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Aportes\Comprobante;
use App\Models\User;
class FormaPago extends Model
{
    use HasFactory;
    protected $table='aportes.forma_pago';
    protected $connection = 'pgsql';

    protected $fillable=[
	'id_comprobante',
	'fecha_pago',
	'tipo_pago',
	'nro_pago',
	'total_forma_pago',
	'nro_compro_anterior',
	'total_compro_anterior',
	'id_persona_caja',
	'id_user_created',
	'id_user_updated',
];
public function comprobanteFormaPago()
    {
        return $this->belongsTo(Comprobante::class, 'id_comprobante');
    }
      //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
