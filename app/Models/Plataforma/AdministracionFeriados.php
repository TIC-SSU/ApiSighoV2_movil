<?php

namespace App\Models\Plataforma;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministracionFeriados extends Model
{
    use HasFactory;
    protected $table = "plataforma.administracion_feriados";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'fecha',
        'nro_dia',
        'nombre_dia',
        'descripcion',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];
    //Relaciones con la tabla DIA
    //    public function diaAdministracionFeriados(){
    //     return $this->belongsTo(dia::class,'id_dia','id');
    // }
    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }
}
