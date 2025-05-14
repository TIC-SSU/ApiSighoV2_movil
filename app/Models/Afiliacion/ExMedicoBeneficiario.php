<?php

namespace App\Models\Afiliacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Administracion\Persona;
use App\Models\Afiliacion\Titular;
use App\Models\Plataforma\Agenda;
use App\Models\Administracion\Personal;
use App\Models\Administracion\GerenciaGeneral;

class ExMedicoBeneficiario extends Model
{
    use HasFactory;

    protected $table = 'afiliacion.ex_medico_beneficiario';
    protected $connection = 'pgsql';

    protected $fillable = [
        'idExmed',
        'fecha_solicitud',
        'fecha_fin',
        'estado_solicitud',
        'estado',
        'fecha_liq',
        'observacion',
        'id_persona',
        'id_titular',
        'id_agenda',
        'id_personal_liq_rec',
        'id_personal_plataforma',
        'id_gerencia_general',
        'nro_tramite',
        'fecha_tramite',
        'procedencia',
    ];

    public function personaExMedicoBeneficiario()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    public function titularExMedicoBeneficiario()
    {
        return $this->belongsTo(Titular::class, 'id_titular');
    }

    public function agendaExMedicoBeneficiario()
    {
        return $this->belongsTo(Agenda::class, 'id_agenda');
    }

    public function personalLiqRecExMedicoBeneficiario()
    {
        return $this->belongsTo(Personal::class, 'id_personal_liq_rec');
    }

    public function personalPlataformaExMedicoBeneficiario()
    {
        return $this->belongsTo(Personal::class, 'id_personal_plataforma');
    }

    public function gerenciaGeneralExMedicoBeneficiario()
    {
        return $this->belongsTo(GerenciaGeneral::class, 'id_gerencia_general');
    }
    //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
