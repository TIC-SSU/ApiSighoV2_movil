<?php

namespace App\Models\Afiliacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Afiliacion\Estudiante;

class DocumentosEstudiante extends Model
{
    use HasFactory;
    protected $table='afiliacion.documentos_estudiante';
    protected $connection = 'pgsql';
    protected $fillable =[
        'id',
        'id_estudiante',
        'file_carnet',
        'estado_carnet',
        'file_matricula',
        'estado_matricula',
        'file_boleta',
        'estado_boleta',
        'file_certificado_gestora',
        'estado_certificado_gestora',
        'file_certificado_cajas',
        'estado_certificado_cajas',
        'file_tipo_sangre',
        'estado_tipo_sangre',
        'activo',
        'id_user_created',
        'id_user_updated',
    ];
    
    //RELACION CON LA TABLA EESTUDAINTE
    public function documentosEstudiante(){
        return $this->belongsTo(Estudiante::class,'id_estudiante');
    }
     //Relaciones con la Tabla Usuario
     public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    
    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
