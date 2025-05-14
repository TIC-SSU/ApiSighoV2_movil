<?php

namespace App\Models\Aportes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// importacion de relaciones
use App\Models\User;
class TasaInteres extends Model
{
    use HasFactory;
    protected $table ='aportes.tasa_interes';
    protected $connection = 'pgsql';

    protected $fillable = [
        'id',
        'valor',
        'fecha',
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
