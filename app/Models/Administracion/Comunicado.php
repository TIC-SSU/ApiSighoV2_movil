<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importancion de Modelos para las relaciones
use App\Models\User;

class Comunicado extends Model
{
    use HasFactory;

    protected $table = "administracion.comunicado";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'nro_comunicado',
        'comunicado',
        'fecha_inicio',
        'fecha_fin',
        'id_usuario_emisor',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    //Relaciones

    public function  usuarioEmisor()
    {
        return $this->belongsTo(User::class, 'id_usuario_emisor', 'id');
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
