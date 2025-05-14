<?php

namespace App\Models\Afiliacion;

use App\Models\Administracion\Persona;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class HistorialMatriculaEstudiante extends Model
{
    use HasFactory;

    protected $table = 'afiliacion.historial_matricula_estudiante';
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'id_persona',
        'registro_universitario',
        'nro_matricula',
        'gestion_matricula',
        'carrera',
        'facultad',
        'gestion_ingreso',
        'tipo_estudiante',
        'id_user_created',
        'id_user_updated',
    ];

    //Relaciones con la tabla Historial Matricula Estudiante 

    public function personaHistorialMatriculaEstudiante()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }

      //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
