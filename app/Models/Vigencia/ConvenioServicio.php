<?php

namespace App\Models\Vigencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Importando los modelos para las relaciones
use App\Models\User;

use App\Models\Vigencia\Convenio;
use App\Models\Administracion\Servicio;

class ConvenioServicio extends Model
{
    use HasFactory;
    protected $table = "vvdd.convenio_servicio";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'id_convenio',
        'id_servicio',
        'id_user_created',
        'id_user_updated',
    ] ;

    //Relaciones con la Tabla Convenio
    public function convenioConvenioServicio(){
        return $this->belongsTo(Convenio::class,'id_convenio','id');
    }

    //Relaciones con la tabla Servicio
    public function servicioConvenioServicio(){
        return $this->belongsTo(Servicio::class,'id_servicio','id');
    }

    //Relaciones con la Tabla Usuario
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
