<?php

namespace App\Models\Afiliacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Administracion\Estado;
use App\Models\Administracion\Persona;
class Preocupacional extends Model
{
    use HasFactory;

    protected $table = 'afiliacion.preocupacional';
    protected $connection = 'pgsql';
    protected $fillable = [
        'fecha_contrato',
        'fecha_solicitud',
        'fecha_fin',
        'cargo',
        'observaciones',
        'id_estado_solicitud',
        'estado',
        'fecha_liq_validacion',
        'fecha_entrega',
        'id_agenda',
        'id_persona',
        'id_personal_rrhh_ext',
        'id_personal_liq_rec',
        'id_personal_plataforma',
        'id_personal_liq_entre',
    ];

    public function personaPreocupuacional()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    public function agendaPreocupuacional()
    {
        return $this->belongsTo(Agenda::class, 'id_agenda');
    }

    // public function personalLiqRecPreocupuacional()
    // {
    //     return $this->belongsTo(Personal::class, 'id_personal_liq_rec');
    // }

    // public function personalPlataformaPreocupuacional()
    // {
    //     return $this->belongsTo(Personal::class, 'id_personal_plataforma');
    // }

    // public function personalLiqEntrePreocupuacional()
    // {
    //     return $this->belongsTo(Personal::class, 'id_personal_liq_entre');
    // }
    public function estadoExamen()
    {
        return $this->belongsTo(Estado::class, 'id_estado_solicitud');
    }
//Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
