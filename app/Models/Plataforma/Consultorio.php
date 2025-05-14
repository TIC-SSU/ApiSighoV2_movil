<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Administracion\Zona;
use App\Models\Plataforma\Sedes;

use App\Models\Plataforma\AsignacionHorario;

class Consultorio extends Model
{
    use HasFactory;
    protected $table = 'plataforma.consultorio';
    protected $connection = 'pgsql';
    protected $fillable = ['numero_consultorio', 'id_sedes', 'id_user_created', 'estado', 'id_user_updated'];
    
    //Relaciones con la tabla Sedes
    public function consultorioSedes()
    {
        return $this->belongsTo(Sedes::class, 'id_sedes', 'id');
    }

    //Relaciones con la tabla Asignacion Consultorio
    public function asignacionHorarioConsultorio()
    {
        return $this->hasMany(AsignacionHorario::class, 'id_consultorio', 'id');
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
}
