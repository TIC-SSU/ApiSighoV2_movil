<?php

namespace App\Models\Migracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConveniosInterior extends Model
{
    use HasFactory;
    protected $connection = 'VigenciaG2';
    protected $table = 'convenios_sighov2';
    protected $primaryKey = 'id_asegurado_convenio';
    public $timestamps = false;
    protected $fillable = [
        'id_asegurado_convenio',
        'cod_interior',
        'fecha_inicio',
        'fecha_fin',
        'id_seguro_interior',
        'otras_limitaciones',
        'numero_documento',
        'apellido_paterno',
        'apellido_materno',
        'nombres',
        'sexo',
        'fecha_nacimiento',
        'foto_nombre',
        'observaciones',
        'estado',
        'estado_validacion',
        'direccion_referencia',
        'telefono_referencia',
        'dato_referencia',
        'telefono',
        'migracion_s2'
    ];
}
