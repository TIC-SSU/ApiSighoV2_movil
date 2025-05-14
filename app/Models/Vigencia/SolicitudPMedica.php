<?php

namespace App\Models\Vigencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Vigencia\AseguradoSolicitante;
use App\Models\Administracion\PersonalSsu;
use App\Models\Vigencia\SeguroInterior;
use App\Models\Vigencia\SolMedicaEspecialidad;
use App\Models\Vigencia\SolMedicaServicio;

class SolicitudPMedica extends Model
{
    use HasFactory;
    protected $table = "vvdd.solicitud_p_medica";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'cod_cite',
        'nro_hoja_de_ruta',
        'fecha_solicitud_hr',
        'estado_validacion',
        'fecha_inicio',
        'fecha_fin',
        'otras_limitaciones',
        'id_seguro_interior',
        'fecha_validacion',
        'observacion',
        'medicamentos_liname',
        'medicamentos_extraliname',
        'totalidad_esp_serv',
        'estado',
        'id_user_validado',
        'documento_nombre',
        'id_user_anulado',
        'motivo_anulacion',
        'fecha_anulacion',
        'firmado_por',
        'fecha_firmado',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la tabla Sol Medica Servicio
    public function solMedicaServicioSolicitudPMedica(){
        return $this->hasMany(SolMedicaServicio::class,'id_solicitud_p_medica','id');
    }

    //Relaciones con la tabla SolMedicaEspecialidad
    public function solMedicaEspecialidadSolicitudPMedica(){
        return $this->hasMany(SolMedicaEspecialidad::class,'id_solicitud_p_medica', 'id');
    }

    //Relaciones con la tabla Personal Ssu
    public function personalSsuSolicitudPMedica(){
        return $this->belongsTo(PersonalSsu::class,'id_personal_ssu', 'id');
    }

    //Relaciones con la tabla Seguro Interior
    public function seguroInteriorSolicitudPMedica(){
        return $this->belongsTo(SeguroInterior::class,'id_seguro_interior','id');
    }

    //Relaciones con la tabla Asegurado Solicitante
    public function aseguradoSolicitanteSolicitudPMedica(){
        return $this->hasMany(AseguradoSolicitante::class, 'id_solicitud_p_medica','id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
