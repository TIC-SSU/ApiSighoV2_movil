<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administracion\Persona;
use App\Models\User;

class NotificacionUsuario extends Model
{
    use HasFactory;
    protected $connection = "pgsql";
    protected $table = 'administracion.notificacion_usuario';

    protected $fillable = [
        'estado',  // 0 = no leido, 1 = recibido, 2=leido
        'tipo_prioridades',
        'IDPersonaEmisor',
        'IDPersonaReceptor',
        'usuarioEmisor',
        'prioridades',
        'FechaHoraEnviada',
        'FechaHoraRecibida',
        'FechaHoraAbierto',
        'Mensaje',
        'Asunto',
        'idUsuario',
    ];

    // Relaciones con otras tablas
    public function emisor()
    {
        return $this->belongsTo(Persona::class, 'IDPersonaEmisor');
    }

    public function receptor()
    {
        return $this->belongsTo(Persona::class, 'IDPersonaReceptor');
    }
    // * Relaciones con la tabla Usuario
    public function usuarioEmisor()
    {
        return $this->belongsTo(User::class, 'id_user_emisor', 'id');
    }

    public function usuarioReceptor()
    {
        return $this->belongsTo(User::class, 'id_user_receptor', 'id');
    }

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }
}
