<?php

namespace App\Models\Vigencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Vigencia\SeguroInterior;
use App\Models\Administracion\Persona;
use App\Models\Administracion\PersonalSsu;
use App\Models\Vigencia\AutorizacionIntEspecialidad;
use App\Models\Vigencia\AutorizacionIntServicio;
use App\Models\Vigencia\HCarnetInterior;

class AutorizacionInterior extends Model
{
    use HasFactory;
    protected $table = "vvdd.autorizacion_interior";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'cod_interior',
        'cod_cite_int',
        'fecha_inicio',
        'fecha_fin',
        'id_seguro_interior',
        'otras_limitaciones',
        'id_persona_interior',
        'id_persona_titular',
        'id_personal_ssu',
        'foto_nombre',
        'observacion',
        'medicamentos_liname',
        'medicamentos_extraliname',
        'totalidad_esp_serv',
        'estado',
        'estado_validacion',
        'fecha_validacion',
        'id_user_anulado',
        'id_user_validado',
        'fecha_anulacion',
        'motivo_anulacion',
        'nro_hoja_de_ruta',
        'fecha_solicitud_hr',
        'direccion_referencia',
        'telefono_referencia',
        'datos_referencia',
        'telefono',
        'migrado',        
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la tabla H Carnet Interior
    public function hCarnetInteriorAutorizacionInterior(){
        return $this->hasMany(HCarnetInterior::class, 'id_autorizacion_interior','id');
    }

    //Relaciones con la tabla Autorizacion Int Servicio
    public function autorizacionIntServicioAutorizacionInterior(){
        return $this->belongsTo(AutorizacionInterior::class,'id_autorizacion_interior', 'id');
    }

    //Relaciones con la tabla Autorizacion Int Especialidad
    public function autorizacionIntEspecialidadAutorizacionInterior(){
        return $this->hasMany(AutorizacionIntEspecialidad::class,'id_autorizacion_interior','id');
    }

    //Relaciones con la tabla Seguro Interior
    public function seguroInteriorAutorizacionInterior(){
        return $this->belongsTo(SeguroInterior::class,'id_seguro_interior','id');
    }

    //Relaciones con la tabla Persona
    public function personaInteriorAutorizacionInterior(){
        return $this->belongsTo(Persona::class,'id_persona_interior','id');
    }

    public function personaInteriorTitularAutorizacionInterior(){
        return $this->belongsTo(Persona::class,'id_persona_titular','id');
    }

    //Relaciones con la tabla Personal SSU
    public function personalSsuAutorizacionInterior(){
        return $this->belongsTo(PersonalSsu::class,'id_personal_ssu', 'id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }

    public function usuarioValida(){
        return $this->belongsTo(User::class,'id_user_validado','id');
    }

    public function usuarioAnula(){
        return $this->belongsTo(User::class,'id_user_anulado','id');
    }
}
