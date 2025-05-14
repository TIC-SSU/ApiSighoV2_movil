<?php

namespace App\Models\Aportes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administracion\Persona;
use App\Models\Aportes\Mes;
use App\Models\Aportes\Comprobante;
use App\Models\Aportes\ContratoVoluntario;
use App\Models\User;
class AporteVoluntario extends Model
{
    use HasFactory;
    protected $table='aportes.aporte_v';
    protected $connection = 'pgsql';
    protected $fillable=[
    'id',
	'total_ganado',
	'total_aporte',
	'gestion',
	'id_contrato_voluntario',
	'id_mes',
	'afiliado',
	'id_user_created',
	'id_user_updated',
    ];
    // relacion con la tabla contrato_voluntario
	public function contratoVoluntarioAporteVoluntario()
    {
        return $this->belongsTo(ContratoVoluntario::class, 'id_contrato_voluntario', 'id');
    }
// relacion con la tabla mes
    public function mesAporteVoluntario()
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

    //Relaciones Has Many
    public function comprobanteAporteVoluntario(){
        return $this->hasMany(Comprobante::class,'id_aporte_v','id');
    }
    public function planillaBajaMedicaAporteVoluntaio(){
        return $this->hasMany(planillaBajaMedica::class, 'id_aporte_v','id');
    }
}
