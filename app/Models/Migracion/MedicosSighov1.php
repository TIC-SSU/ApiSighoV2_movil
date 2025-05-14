<?php

namespace App\Models\Migracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicosSighov1 extends Model
{
    use HasFactory;
     protected $connection = 'sqlsrv';
     protected $table = 'medicos_sighov2';
     protected $primaryKey = 'id_persona';
     public $timestamps = false;

     protected $fillable = [
         'id_persona',
         'ap_Paterno',
         'ap_Materno',
         'nombres',
         'numero_identificacion',
         'fecha_nacimiento',
         'especialidad',
         'id_especialidad',
     ];
}
