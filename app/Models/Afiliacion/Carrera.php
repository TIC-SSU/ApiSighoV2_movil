<?php

namespace App\Models\Afiliacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Afiliacion\Facultad;
use App\Models\Afiliacion\Estudiante;

class Carrera extends Model
{
    use HasFactory;

    protected $table = 'afiliacion.carrera';
    protected $connection = 'pgsql';

    protected $fillable = [
        'nombre',
        'sigla',
        'id_facultad',
    ];

    public function facultadCarrera()
    {
        return $this->belongsTo(Facultad::class, 'id_facultad');
    }

    public function estudiantesCarrera()
    {
        return $this->hasMany(Estudiante::class, 'id_carrera');
    }
    //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
