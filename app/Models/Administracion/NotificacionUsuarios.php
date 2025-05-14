<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class NotificacionUsuarios extends Model
{
    use HasFactory;
    protected $table = "administracion.notificaciones_usuarios";
    protected $connection = "pgsql";
    protected $fillable = [
        'estado',
        'prioridades',
        'id_usu_emisor',
        'id_usu_receptor',
        'fecha_hora_enviada',
        'fecha_hora_leida',
        'fecha_hora_abierto',
        'asunto',
        'mensaje'
    ];
    public $timestamps = true;

    public function emisor()
    {
        return $this->belongsTo(User::class, 'id_usu_emisor');
    }
}
