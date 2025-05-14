<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importancion de Modelos para las relaciones
use App\Models\User;
use App\Models\Administracion\Persona;

use App\Models\Vigencia\AutorizacionInterior;
use App\Models\Vigencia\Convenio;
use App\Models\Vigencia\SolicitudPMedica;

class PersonalSsu extends Model
{
    use HasFactory;
    protected $table = "administracion.personal_ssu";
    protected $connection = "pgsql";
    protected $fillable = [
        'id',
        'id_persona',
        "cargo",
        "permanente",
        "fecha_ini_contrato",
        "fecha_fin_contrato",
        "estado",
        'observacion',
        'id_user_created',
        'id_user_updated',
    ];
    public function unidadPErsonalSsu()
    {
        return $this->hasMany(Unidad::class, 'id_personal_ssu', 'id');
    }
    //Relaciones con la Tabla Persona
    public function personaPersonalSsu()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

    //Relaciones con la Tabla Usuario

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }

    //Relaciones con el Esquema VVDD

    //Relaciones con la tabla Autorizacion Interior
    public function autorizacionInteriorPersonalSsu()
    {
        return $this->hasMany(AutorizacionInterior::class, 'id_personal_ssu', 'id');
    }

    //Relaciones con la tabla Convenio
    public function convenioPersonalSsu()
    {
        return $this->hasMany(Convenio::class, 'id_personal_ssu', 'id');
    }

    //Relaciones con la tabla Solicitud P Medica
    public function solicitudPMedicaPersonalSsu()
    {
        return $this->hasMany(SolicitudPMedica::class, 'id_personal_ssu', 'id');
    }
}
