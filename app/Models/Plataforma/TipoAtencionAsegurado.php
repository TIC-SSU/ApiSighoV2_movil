<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Plataforma\Especialista;

class TipoAtencionAsegurado extends Model
{
    use HasFactory;
    protected $table = "plataforma.tipo_atencion_asegurado";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id_especialista',
        'fecha_inicio_titular',
        'fecha_fin_titular',
        'permanente_titular',
        'fecha_inicio_interior',
        'fecha_fin_interior',
        'permanente_interior',
        'fecha_inicio_estudiante',
        'fecha_fin_estudiante',
        'permanente_estudiante',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la tabla Especialista
    public function especialistaTipoAtencionAsegurado(){
        return $this->belongsTo(Especialista::class,'id_especialista', 'id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
