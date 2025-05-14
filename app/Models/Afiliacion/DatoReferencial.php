<?php

namespace App\Models\Afiliacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class DatoReferencial extends Model
{
    use HasFactory;
    protected $table = 'afiliacion.dato_referencial';
    protected $connection = 'pgsql';

    protected $fillable = [
        'id',
        'telefono',
        'telefono_referencia',
        'nombre_referencia',
        'correo',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];

    public function userCreated(){
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function userUpdated(){
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }
}
