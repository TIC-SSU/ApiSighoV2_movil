<?php

namespace App\Models\Vigencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;
use App\Models\Vigencia\Convenio;

class TipoConvenio extends Model
{
    use HasFactory;
    protected $table = "vvdd.tipo_convenio";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'tipo_convenio',
        'sigla',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la tabla Convenio
    public function convenioTipoConvenio(){
        return $this->hasMany(Convenio::class,'id_tipo_convenio','id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }


}
