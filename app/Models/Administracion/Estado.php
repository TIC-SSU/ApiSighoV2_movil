<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Aportes\Comprobante;

class Estado extends Model
{
    use HasFactory;
    protected $table = "administracion.estado";
    protected $connection = "pgsql";
    protected $fillable = [
        'id',
        'descripcion', 
        'numero',  
        'id_user_created',
        'id_user_updated',
    ];

    public function comprobanteEstado(){
        return $this->hasMany(Comprobante::class, 'id_estado', 'id');
    }
    public function estadoExamen(){
        return $this->hasMany(Preocupacional::class, 'id_estado_solicitud', 'id');
    }

    

}
