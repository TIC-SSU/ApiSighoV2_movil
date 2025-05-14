<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Plataforma\AsignacionHorario;
use App\Models\Plataforma\EspecialistaHabilitadoServicio;
use App\Models\Plataforma\AdministracionFeriados;

class Dia extends Model
{
    use HasFactory;
    protected $table = "plataforma.dia";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'nombre',
        'numero',
        'id_user_created',
        'id_user_updated',
    ];

    //Relaciones con la tabla Especialista Habilitado Servicio
    public function especialistaHabilitadoServicioDia()
    {
        return $this->hasMany(EspecialistaHabilitadoServicio::class, 'id_dia', 'id');
    }

    //Relaciones con la tabla Asignacion Horario
    public function asignacionHorarioDia()
    {
        return $this->hasMany(AsignacionHorario::class, 'id_dia', 'id');
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

    //Relaciones con la Tabla Admnistracion feriados
    public function administracionFeriadosDia()
    {
        return $this->hasMany(AdministracionFeriados::class, 'id_dia', 'id');
    }
}
