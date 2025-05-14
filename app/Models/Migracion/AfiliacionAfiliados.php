<?php

namespace App\Models\Migracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AfiliacionAfiliados extends Model
{
    use HasFactory;
    protected $connection = 'sqlsrv';
    protected $table = 'tbl_afiliacion_afiliado';
    protected $primaryKey = 'id_afiliado';
    public $timestamps = false;
    protected $fillable = [
        'id_tipoafiliado',
        'matricula',
        'secuencial',
        'nombres',
        'apellidoPaterno',
        'apellidoMaterno',
        'apellidoEsposo',
        'fechaNacimiento',
        'id_estadocivil',
        'sexo',
        'id_tipoidentificacion',
        'DocIdentificacion',
        'id_departamento',
        'id_departamentonac',
        'id_zona',
        'domicilio',
        'telefonoDomicilio',
        'telefonoCelular',
        'fechaRegistro',
        'id_gruposanguineo',
        'alergias',
        'telefonocontacto',
        'detallecontacto',
        'observaciones',
        'id_estadoAfiliado',
        'idUsuario',
        'migrado_s2',
    ];
}
