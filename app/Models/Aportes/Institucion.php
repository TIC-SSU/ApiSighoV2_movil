<?php

namespace App\Models\Aportes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Aportes\ClaseAportante;
use App\Models\Administracion\Zona;
use App\Models\Administracion\Persona;
use App\Models\Administracion\Residencia;
use App\Models\User;

use App\Models\Aportes\Comprobante;
use App\Models\Aportes\ComprobanteTemp;
use App\Models\Aportes\AporteInstitucional;
use App\Models\Aportes\AporteInstitucionalTemp;
use App\Models\Aportes\Mes;

use App\Models\Afiliacion\TitularInstitucion;

use App\Models\Afiliacion\CodigoInstitucionSsu;

class Institucion extends Model
{
    use HasFactory;
    protected $table ='aportes.institucion';
    protected $connection = 'pgsql';
    
    protected $fillable = [
	'nit',
	'nro_empleador',
	'actividad_empleador',
	'id_clase_aportante',
	'acronimo',
	'fecha_afiliacion',
	'correo',
	'telefono',
	'id_persona',
	'nombre',
	'estado',
	'tgn',
	'id_user_created',
	'id_user_updated',
    'id_usuario_responsable',
    'estado_validado',
    'id_residencia' ,
    'publica',
    'codigo_comercio',
    'tipo_empresa_institucion',
    'referencia_direccion',
    'telefono_dos'    
    ];                                                                                                                             

    
	public function claseAportanteInstitucion()
    {
        return $this->belongsTo(ClaseAportante::class, 'id_clase_aportante');
    }

    public function personaRepresentanteInstitucion()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }
	  //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }

    //Relaciones Has Many
    public function comprobanteInstitucion(){
        return $this->hasMany(Comprobante::class, 'id_institucion', 'id');
    }

    public function comprobanteTempInstitucion(){
        return $this->hasMany(ComprobanteTemp::class, 'id_institucion', 'id');
    }


    public function aporteInstitucionalInstitucion(){
        return $this->hasMany(AporteInstitucional::class, 'id_institucion', 'id');
    }

    public function aporteInstitucionalTempInstitucion(){
        return $this->hasMany(AporteInstitucionalTemp::class, 'id_institucion', 'id');
    }

    public function InstitucionResponsable(){
        return $this->belongsto(User::class,'id_usuario_responsable','id');
    }

    public function codigosInstitucion()
    {
        return $this->hasOne(CodigoInstitucionSsu::class, 'id_institucion', 'id');
    }

    //-- RELACION CON ESQUEMA AFILIACION
    public function institucionConTitularInstitucion(){
        return $this->hasMany(TitularInstitucion::class,'id_institucion','id');
    }
    
    // RELACION CON TABLA RESIDENCIA 
    public function institucionResidencia()
    {
        return $this->belongsTo(Residencia::class,'id_residencia','id');
    }
}


