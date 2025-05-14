<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AccesoAsussToken extends Model
{
    use HasFactory;
    protected $table = "administracion.acceso_asuss_token";
    protected $connection = "pgsql";
    protected $fillable = [
        'username',
        'password',
        'token',
        'estado',
        'ip',
        'nombre_equipo',
        'id_user_created',
    ];

    //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    } 
}
