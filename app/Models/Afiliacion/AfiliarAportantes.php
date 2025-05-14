<?php

namespace App\Models\Afiliacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AfiliarAportantes extends Model
{
    use HasFactory;

    protected $table = 'afiliacion.afiliar_aportantes';
    protected $connection = 'pgsql';

    protected $fillable = [
        'nombres',
        'p_apellido',
        's_apellido',
        'sexo',
       
        
    ];
}
