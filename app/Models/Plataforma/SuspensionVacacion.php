<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Plataforma\Especialista;
use App\Models\Plataforma\Motivo;

class SuspensionVacacion extends Model
{
    use HasFactory;
    protected $table = "plataforma.suspension_vacacion";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'id_especialista',
        'nro_registro_vacacion_rrhh',
        'observacion',
        'id_user_anulacion',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la tabla Especialista
    public function especialistaSuspensionVacacion(){
        return $this->belongsTo(Especialista::class,'id_especialista', 'id');
    }


    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
    public function usuarioAnula(){
        return $this->belongsTo(User::class,'id_user_anulacion','id');
    }
}
