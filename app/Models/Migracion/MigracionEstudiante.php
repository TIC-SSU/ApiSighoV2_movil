<?php

namespace App\Models\Migracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MigracionEstudiante extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = "afiliados_estudiantes_sighov2";
    protected $fillable = [
        'id_afiliado_foto',
        //PERSONA
        'complemento',
        'nombres',
        'primer_apellido',
        'segundo_apellido',
        'sexo',
        'afiliado',
        'matricula',
        'id_nacimiento_municipio',
        'fecha_nacimiento',
        'id_tipo_asegurado',
        'es_extranjero',
        'id_estado_civil',
        'id_dept_exp',
        'alergias',
        'grupo_sanguineo',
        //RESIDENCIA
        'direccion',
        'nro_vivienda',
        'id_zona',
        'latitud',
        'longitud',
        //ZONA
        'id_nacimiento_municipio',
        'nombre',
        //TITULAR
        'estado_requisitos',
        'telefono_referencia',
        'nombre_referencia',
        'observacion',
        'fecha_afiliacion',
        'telefono',
        'correo',
        'permanente',
        'tipo_rentista',
        //TITULAR INSTITUCION
        'id_institucion',
        'estado',
        'fecha_ingreso',
        'salario',
        'tipo_institucion',
        'facultad',
        'carrera',
        'ru',

        'migrado_s2',


    ]; 



}
