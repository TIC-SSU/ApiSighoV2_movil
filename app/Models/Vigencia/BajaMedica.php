<?php

namespace App\Models\Vigencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Administracion\Especialidad;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Titular;
use App\Models\Plataforma\Especialista;

class BajaMedica extends Model
{
    use HasFactory;
    protected $table = "vvdd.baja_medica";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'cod_baja_medica',
        'fecha_recepcion',
        'fecha_inicio',
        'fecha_fin',
        
        'id_baja_medica_s',
        'id_especialidad',
        'id_especialista',
        'estado_validacion',
        'diagnostico',
        'id_institucion',
        'nombre_institucion',
        'nombre_especialista',
        'id_persona',
        'estado_registro',
        'id_user_created',
        'id_user_updated', 
        'id_user_validado',
        'fecha_validacion',
        'observacion',
        'id_user_anulado',
        'fecha_anulado',
        'id_persona_entregado',
        'id_user_entregado',
        'fecha_homologado',
        'nombre_persona',
        'ci_persona',
        'complemento_persona',
        'matricula_persona',
        'genero_persona',
        'fecha_nacimiento_persona',
    ] ;

    //Relaciones con la tabla Especialidad
    public function especialidadBajaMedica(){
        return $this->belongsTo(Especialidad::class,'id_especialidad', 'id');
    }

    //Relaciones con la tabla Especialista
    public function especialistaBajaMedica(){
        return $this->belongsTo(Especialista::class, 'id_especialista', 'id');
    }

    //Relaciones con la tabla Persona
    public function personaBajaMedica(){
        return $this->belongsTo(Persona::class,'id_persona','id');
    }

    public function personaEntregadoBajaMedica(){
        return $this->belongsTo(Persona::class,'id_persona_entregado','id');
    }

    //Relaciones con la tabla Titular
    /*public function titularBajaMedica(){
        return $this->belongsTo(Titular::class,'id_titular','id');
    }*/

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
