<?php

namespace App\Models\Aportes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class SalarioMinimoNacional extends Model
{
    use HasFactory;
    protected $table = 'aportes.salario_minimo_nacional';
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'gestion',
        'monto',
        'estado',
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
