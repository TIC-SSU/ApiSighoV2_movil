<?php

namespace App\Models\Vigencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Administracion\Especialidad;
use App\Models\Vigencia\InstitucionConvenio;
use App\Models\Administracion\Persona;
use App\Models\Plataforma\Especialista;

class OrdenServicio extends Model
{
    use HasFactory;
    protected $table = "vvdd.orden_servicio";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'nro_orden_servicio',
        'fecha_recepcion',
        'observacion',
        'id_persona',
        'id_orden_servicio_s',
        'fecha_entregado',
        'fecha_validacion',
        'fecha_anulacion',
        'id_persona_entregado',
        'estudio',
        'estado',
        'id_institucion_convenio',
        'id_especialidad',
        'id_especialista',
        'id_user_created',
        'id_user_updated',
        'id_user_validado',
        'id_user_anulado',
    ] ;
    
    //Relaciones con la tabla Especialidad
    public function especialidadOrdenServicio(){
        return $this->belongsTo(Especialidad::class,'id_especialidad', 'id');
    }

    //Relaciones con la tabla Especialista
    public function especialistaOrdenServicio(){
        return $this->belongsTo(Especialista::class,'id_especialista', 'id');
    }

    //Relaciones con la tabla Institucion Convenio
    public function institucionConvenioOrdenServicio(){
        return $this->belongsTo(InstitucionConvenio::class,'id_institucion_convenio','id');
    }

    //Relaciones con la tabla Persona
    public function personaEntregadoOrdenServicio(){
        return $this->belongsTo(Persona::class,'id_persona_entregado','id');
    }

    public function personaOrdenServicio(){
        return $this->belongsTo(Persona::class,'id_persona','id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
