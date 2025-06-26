<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Plataforma\DiasHabilitadosAgenda;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class CantidadHorario extends Model
{
    use HasFactory;
    protected $table = "plataforma.cantidad_horario";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id_dias_habilitados_agenda',
        'estado',
        'hora_inicio',
        'hora_fin',
        'eliminado',
        'porcentaje',
        'id_user_created',
        'id_user_updated',
    ];
    //Relaciones con la Tabla Usuario
    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }
    //Relacion con dias habilitados_agenda
    public function diasHabilitadosAgendaCantidadHorario()
    {
        return $this->belongsTo(DiasHabilitadosAgenda::class, 'id_dias_habilitados_agenda', 'id');
    }
}
