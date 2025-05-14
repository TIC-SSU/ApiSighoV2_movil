<?php

namespace App\Models\Aportes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Aportes\Mes;
use App\Models\Administracion\Persona;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\PlanillaBajaMedica;
use App\Models\User;
class AporteInstitucional extends Model
{
    use HasFactory;
    protected $table='aportes.aporte_i';
    protected $connection = 'pgsql';
    protected $fillable=[
	'id_persona',
	'id_institucion',
	'item',
	'cargo',
	'area',
	'tipo_trabajador',
	'carga_horaria',
	'dias_trabajados',
	'total_ganado',
	'total_ganado_dia',
	'total_aporte',
	'gestion',
	'reintegro_normal',
	'id_mes',
	'reintegro_incr_salarial',
	'beneficio_social',
    'planilla_adicional',
    'retroactivo',
	'afiliado',
    'jubilado',    
    'fecha_ingreso',
    'fecha_novedad_retiro_suspension',
    'motivo_novedad_retiro_suspension',
    'parentesco_rentista',
    'tipo_renta',
    'aporte_planilla',
    'compensacion_salario_minimo',
    'estado_compensacion', 
	'id_user_created',
	'id_user_updated',
    ];
	// relacion con la tabla persona
	public function personaAporteInstitucional()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }
// relacion con la tabla institucion
    public function institucionAporteInstitucional()
    {
        return $this->belongsTo(Institucion::class, 'id_institucion' , 'id');
    }
    public function aporteInstitucionalPlanillaBajaMedica(){
        return $this->hasMany(PlanillaBajaMedica::class, 'id_aporte_i' , 'id');
    }
// realcion con la tabla mes
    public function mesAporteInstitucional()
    {
        return $this->belongsTo(Mes::class, 'id_mes' , 'id');
    }
	//Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
