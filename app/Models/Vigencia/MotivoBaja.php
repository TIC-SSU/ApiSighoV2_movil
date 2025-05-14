<?php

namespace App\Models\Vigencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Vigencia\BajaInstitucional;

class MotivoBaja extends Model
{
    use HasFactory;
    protected $table = "vvdd.motivo_baja";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'motivo_baja',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la tabla Baja Institucional
    public function bajaInstitucionalMotivoBaja(){
        return $this->hasMany(BajaInstitucional::class,'id_motivo_baja','id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
