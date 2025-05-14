<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importancion de Modelos para las relaciones
use App\Models\User;

use App\Models\Vigencia\AutorizacionIntServicio;
use App\Models\Vigencia\ConvenioServicio;
use App\Models\Vigencia\SolMedicaServicio;

use App\Models\Plataforma\EspecialidadHabilitadoServicio;
use App\Models\Plataforma\EspecialistaHabilitadoServicio;

class Servicio extends Model
{
    use HasFactory;
    protected $table = "administracion.servicio";
    protected $connection = "pgsql";
    protected $fillable = [
        'id',
        'servicio',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }


    //Relaciones con el Esquema VVDD
    //Relaciones con la tabla Autorizacion Int Servicio

    public function autorizacionIntServicioServicio(){
        return $this->hasMany(AutorizacionIntServicio::class, 'id_servicio','id');
    }

    //Relaciones con la tabla Convenio Servicio
    public function convenioServicioServicio(){
        return $this->hasMany(ConvenioServicio::class, 'id_servicio','id');
    }

    //Relaciones con la tabla Sol Medica Servicio
    public function solMedicaServicioServicio(){
        return $this->hasMany(SolMedicaServicio::class, 'id_servicio','id');
    }

  

    //Relaciones con la tabla Especialista Habilitado Servicio
    public function especialistaHabilitadoServicioServicio(){
        return $this->hasMany(EspecialistaHabilitadoServicio::class,'id_servicio', 'id');
    }
}
