<?php

namespace App\Models\Afiliacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Afiliacion\Carrera;

class Facultad extends Model
{
    use HasFactory;

    protected $table = 'afiliacion.facultad';
    protected $connection = 'pgsql';
    protected $fillable = [
        'nombre',
        'sigla',
    ];

    public function carrerasFacultad()
    {
        return $this->hasMany(Carrera::class, 'id_facultad');
    }
    //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
