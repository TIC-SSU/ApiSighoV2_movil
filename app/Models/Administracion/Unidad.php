<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importancion de Modelos para las relaciones
use App\Models\User;

class Unidad extends Model
{
    use HasFactory;
    protected $table = "administracion.unidad";
    protected $connection = "pgsql";
    protected $fillable = [
        'id',
        'nombre',
        'sigla',
        'id_unidad_dep',
        'estado',
        'id_personal_ssu',
        'id_user_created',
        'id_user_updated',
    ];

    public function unidadPersonalSsu()
    {
        return $this->belongsTo(Unidad::class, 'id_personal_ssu', 'id');
    }
    //Relaciones con la misma tabla
    public function unidadUnidad(){
        return $this->belongsTo(Unidad::class, 'id_unidad_dep','id');
    }
    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
