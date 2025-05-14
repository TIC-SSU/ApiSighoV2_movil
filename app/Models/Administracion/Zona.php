<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importancion de Modelos para las relaciones
use App\Models\User;
use App\Models\Administracion\Residencia;
use App\Models\Administracion\Municipio;
use App\Models\Vigencia\InstitucionConvenio;
use App\Models\Plataforma\Consultorio;
use App\Models\Aportes\ContratoVoluntario;

class Zona extends Model
{
    use HasFactory;
    protected $table = 'administracion.zona';
    protected $connection = 'pgsql';
    protected $fillable = ['id', 'nombre', 'id_municipio', 'estado', 'id_user_created', 'id_user_updated'];

    //Relaciones con la Tabla Municipio
    public function municipioZona()
    {
        return $this->belongsTo(Municipio::class, 'id_municipio', 'id');
    }

    //Relaciones con la Tabla Residencia
    public function residenciaZona()
    {
        return $this->hasMany(Residencia::class, 'id_zona', 'id');
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
    //Relaciones con la tabla Intitucion Convenio
    public function institucionConvenioZona()
    {
        return $this->hasMany(InstitucionConvenio::class, 'id_zona', 'id');
    }

    //Relaciones con el Esquema Plataforma
    //Relaciones con la tabla Consultorio
    public function consultorioZona()
    {
        return $this->hasMany(Consultorio::class, 'id_zona', 'id');
    }

    //Relaciones con el Esquema Aportes
    //Relaciones con la tabla ContratoVoluntario
    public function contratoVoluntarioZona()
    {
        return $this->hasMany(ContratoVoluntario::class, 'id_zona', 'id');
    }
}
