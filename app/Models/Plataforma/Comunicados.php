<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comunicados extends Model
{
    use HasFactory;
    protected $table = "plataforma.comunicados";
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'mensaje',
        'permanente',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'id_unidad',
        'nombre_unidad',
        'id_user_created',
        'id_user_updated',
    ];
}
