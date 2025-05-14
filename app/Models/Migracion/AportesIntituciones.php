<?php

namespace App\Models\Migracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AportesIntituciones extends Model
{
    use HasFactory;
    protected $connection = 'AportesG2';
    protected $table = 'tbl_aportes_Institucion';
    protected $primaryKey = 'id_institucion';
    public $timestamps = false; 

    protected $fillable = [
        'codig',
        'codigo',
        'razon_social',
        'fecha_registro',
        'siglaInstitucion',
        'nit',
        'nro_empleador',
        'actEconomica',
        'FechaInicioAportes',
        'dia_pago',
        'fechavencimiento',
        'direccion',
        'nro_casa',
        'id_zona',
        'id_departamento',
        'telefono',
        'id_estado',
        'tipoInstitucion',
        'id_institucion_s2',
    ];
}
