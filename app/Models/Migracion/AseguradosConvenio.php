<?php

namespace App\Models\Migracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AseguradosConvenio extends Model
{
    use HasFactory;
    protected $connection = 'VigenciaG2';
    protected $table = 'vig_asegurados_convenio';
    protected $primaryKey = 'id_asegurado_convenio';
    public $timestamps = false;

    protected $fillable = [
        'id_asegurado_convenio',
        'id_asegurado_conv_padre',
        'id_persona',
        'id_tipo_convenio',
        'codigo_convenio',
        'matricula',
        'fecha_registro',
        'dias_vigencia',
        'fecha_fin_vig_der',
        'id_afiliado',
        'id_seguro_origen',
        'nombre_completo_aut',
        'nombre_completo_aut_ssu',
        'id_cargo_aut_ssu',
        'limitacion',
        'enabled',
        'id_creator',
        'date_of_creation',
        'id_modificator',
        'last_modification',
        'migracion_s2',
    ];
}
