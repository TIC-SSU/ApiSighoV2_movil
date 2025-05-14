<?php

namespace App\Models\Aportes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// importacion de relaciones
use App\Models\Aportes\Institucion;
use App\Models\Aportes\Mes;
use App\Models\Aportes\AporteVoluntario;
use App\Models\User;
use App\Models\Administracion\Estado;
use App\Models\Aportes\FormaPago;

class Comprobante extends Model
{
    use HasFactory;
    protected $table='aportes.comprobante';
    protected $connection = 'pgsql';

    protected $fillable= [
    'id',
	'nro_registro_comprobante',
	'id_institucion',
	'gestion',
	'fecha_liquidacion',
	'cantidad_eventuales',
	'cantidad_permanentes',
	'tasa_interes',
	'factor_actual',
	'total_salario_cotizable',
	'importe_intereses',
	'dias_mora',
	'subtotal_liquidacion',
	'importe_multa_interes',
	'importe_multa_no_planilla',
	'monto_demasia',
	'demasia_descripcion',
	'monto_deduccion',
	'deduccion_descripcion',
	'monto_total_comprobante',
	'monto_bajas_medicas',
	'fecha_liquidacion_baja_medica',
	'total_comprobante',
	'id_aporte_v',
	'reintegro',
	'beneficio_social',
	'id_mes',
	'id_estado',
	'importe_aporte_patronal',
	'actualizacion_aporte',
	'subtotal_recargos',
	'subtotal_deducciones',
	'ministerio',
	'observacion',
	'planilla',
	'importe_actualizacion_aporte',
	'id_user_created',
	'id_user_updated',
	'diferencia_pago_liquidacion',
	'planilla_adicional',
	'total_compensacion',
	'fecha_validado',
	'fecha_registrado',
	'fecha_liquidado',
	'retroactivo',
	'estado_umsa'
    ];
	  public function institucionComprobante()
    {
        return $this->belongsTo(Institucion::class, 'id_institucion', 'id');
    }

    public function mesComprobante()
    {
        return $this->belongsTo(Mes::class, 'id_mes', 'id');
    }

    public function aporteVComprobante()
    {
        return $this->belongsTo(AporteVoluntario::class, 'id_aporte_v', 'id');
    }
	  //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }

	public function estadoComprobante()
    {
        return $this->belongsTo(Estado::class, 'id_estado', 'id');
    }


		//Rleacion has many con forma pago
		public function formaPagoComprobante(){
			return $this->hasMany(FormaPago::class, 'id_comprobante', 'id');
		}

}
