<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonaAsus extends Model
{
    use HasFactory;
    protected $table='persona_asus';
    protected $connection = 'pgsql';

    protected $fillable=[
        'nombre',
        'apPaterno',
        'apMaterno',
        'ci',
        'complemento',
        'tipo_documento',
        'matricula',
        'fecha_nacimiento',
    ];
}
