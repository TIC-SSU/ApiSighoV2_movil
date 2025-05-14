<?php

namespace App\Models\Vigencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Administracion\Persona;
use App\Models\Administracion\PersonalSsu;
use App\Models\Vigencia\TipoConvenio;
use App\Models\Afiliacion\Titular;
use App\Models\Vigencia\ConvenioEspecialidad;
use App\Models\Vigencia\ConvenioServicio;
use App\Models\Vigencia\HCarnetInterior;

class Convenio extends Model
{
    use HasFactory;
    protected $table = "vvdd.convenio";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'cod_convenio',
        'fecha_inicio',
        'fecha_fin',
        'otras_limitaciones',
        'id_persona',
        'id_titular_autoriza',
        'id_tipo_convenio',
        'id_personal_ssu',
        'foto_nombre',
        'observacion',
        'medicamentos_liname',
        'medicamentos_extraliname',
        'totalidad_esp_serv',
        'estado',
        'descripcion_estado',
        'id_user_created',
        'id_user_updated',
        'nro_hoja_de_ruta',
        'fecha_solicitud_hr',
        'estado_validez',
        'fecha_anulacion',
        'motivo_anulacion',
        'id_user_anulado',
        'nro_documento_respaldo',
    ] ;

    //Relaciones con la tabla H Carnet Interior
    public function hCarnetInteriorConvenio(){
        return $this->hasMany(HCarnetInterior::class, 'id_convenio','id');
    }

    //Relaciones con la tabla Convenio Servicio
    public function convenioServicioConvenio(){
        return $this->hasMany(ConvenioServicio::class, 'id_convenio','id');
    }

    //Relaciones con la tabla Convenio Especialidad
    public function convenioEspecialidadConvenio(){
        return $this->hasMany(ConvenioEspecialidad::class, 'id_convenio','id');
    }

    //Relaciones con la tabla Persona
    /*public function personaAutorizaConvenio(){
        return $this->belongsTo(Persona::class,'id_persona_autoriza','id');
    }*/

    public function personaConvenio(){
        return $this->belongsTo(Persona::class,'id_persona', 'id');
    }

    //Relaciones con la tabla Personal Ssu
    public function personalSsuConvenio(){
        return $this->belongsTo(PersonalSsu::class,'id_personal_ssu', 'id');
    }

    //Relaciones con la tabla Tipo Convenio
    public function tipoConvenioConvenio(){
        return $this->belongsTo(TipoConvenio::class,'id_tipo_convenio', 'id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
 //Relaciones con la tabla titular
 public function titularAutorizaConvenio(){
    return $this->belongsTo(Titular::class,'id_titular_autoriza','id');
}

}
