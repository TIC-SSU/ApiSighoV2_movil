<?php

namespace App\Models\Aportes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Aportes\ContratoVoluntario;

class ClaseAportante extends Model
{
    use HasFactory;
    protected $table = 'aportes.clase_aportante';
    protected $connection = 'pgsql';

    protected $fillable = ['id', 'nombre', 'tipo', 'porcentaje', 'id_user_created', 'id_user_updated'];

    public function contratoVoluntarioClaseAportante()
    {
        return $this->hasMany(ContratoVoluntario::class, 'id_clase_aportante', 'id');
    }
    //Relaciones con la Tabla Usuario

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'id_user_created', 'id');
    }

    public function usuarioEditor()
    {
        return $this->belongsTo(User::class, 'id_user_updated', 'id');
    }
}
