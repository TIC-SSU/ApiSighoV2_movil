<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiReplicaLogs extends Model
{
    use HasFactory;

    protected $table = 'administracion.api_replica_logs';
    // protected $primaryKey = 'id';
    protected $connection = 'pgsql';
    protected $fillable = [
        "id",
        "sistema_destino",
        "modulo_destino",
        "modulo_emisor",
        "datos_enviados",
        "codigo_respuesta",
        "respuesta_servidor",
        "mensaje_error",
        "fecha_replicacion",
        "created_at",
        "updated_at",
        "id_user_created",
    ];
}
