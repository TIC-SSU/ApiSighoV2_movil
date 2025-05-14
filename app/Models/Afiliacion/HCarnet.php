<?php

namespace App\Models\Afiliacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administracion\Persona;
use App\Models\User;

class HCarnet extends Model
{
    use HasFactory;

    protected $table = 'afiliacion.h_carnet';
    protected $connection = 'pgsql';
    protected $fillable = [
        'fecha_emision',
        'fecha_vencimiento',
        'motivo',
        'id_persona',
        'indefinido',
        'id_user_created',
        'id_user_updated',
    ];

    public function personaHCarnet()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }
      //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
