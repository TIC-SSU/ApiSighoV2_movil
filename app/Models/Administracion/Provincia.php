<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importancion de Modelos para las relaciones
use App\Models\User;
use App\Models\Administracion\Municipio;
use App\Models\Administracion\Departamento;

class Provincia extends Model
{
    use HasFactory;
    protected $table = "administracion.provincia";
    protected $connection = "pgsql";
    protected $fillable = [
        'id',
        'nombre',
        'id_departamento',
        'id_user_created',
        'id_user_updated',
    ];

    //Relaciones con la Tabla Departamento
    public function departamentoProvincia(){
        return $this->belongsTo(Departamento::class, 'id_departamento', 'id');
    }

    //Relaciones con la Tabla Municipio

    public function municipioProvincia(){
        return $this->belongsTo(Municipio::class, 'id_provincia', 'id');
    }

    //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
