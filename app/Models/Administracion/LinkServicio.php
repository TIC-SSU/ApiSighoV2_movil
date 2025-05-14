<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class LinkServicio extends Model
{
    use HasFactory;
    protected $table = "administracion.link_servicio";
    protected $connection = "pgsql";
    protected $fillable = [
        'id',
        'nombre_servicio',
        'link_servicio',
        'modulo',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
