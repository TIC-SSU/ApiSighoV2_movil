<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importancion de Modelos para las relaciones
use App\Models\User;

class Notificacion extends Model
{
    use HasFactory;
    protected $table = "administracion.notificacion";
    protected $connection = "pgsql";
    protected $fillable = [
        'id',
        'notificacion',
        'fecha_enviado',
        'grado_alerta',
        'fecha_leido',
        'id_usuario_emisor',
        'id_usuario_receptor',
        'leido',
        'id_user_created',
        'id_user_updated',
    ];

    //Relaciones con la Tabla Usuario

    public function usuarioEmisor(){
        return $this->belongsTo(User::class,'id_user_emisor','id');
    }    

    public function usuarioReceptor(){
        return $this->belongsTo(User::class,'id_user_receptor','id');
    }

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
