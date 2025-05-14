<?php

namespace App\Models\Vigencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Administracion\Servicio;
use App\Models\Vigencia\SolicitudPMedica;

class SolMedicaServicio extends Model
{
    use HasFactory;
    protected $table = "vvdd.solmedica_servicio";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'id_solicitud_p_medica',
        'id_servicio',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la tabla Servicio
    public function servicioSolMedicaServicio(){
        return $this->belongsTo(Servicio::class,'id_servicio','id');
    }

    //Relaciones con la tabla Solicitud P Medica
    public function solicitudPMedicaSolMedicaServicio(){
        return $this->belongsTo(SolicitudPMedica::class,'id_solicitud_p_medica','id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
