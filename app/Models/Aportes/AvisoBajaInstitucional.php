<?php

namespace App\Models\Aportes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Administracion\Persona;

class AvisoBajaInstitucional extends Model
{
    use HasFactory;
    protected $table ='aportes.aviso_baja_institucional';
    protected $connection = 'pgsql';
    
    protected $fillable = [
    'id',
	'id_persona',
    'fecha_baja',
    'motivo_baja',
    'ultimo_salario',
    'fecha_solicitud',
    'fecha_validacion',
    'id_usuario_solicitud',
    'id_usuario_valida',
    'descripcion',
    'id_user_created',
    'id_user_updated',
    'created_at',
    'updated_at',
    'estado_valida',
    'id_institucion',
    'anulado'
	    
    ];  
    public function personaAportante()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }
	  //Relaciones con la Tabla Usuario

    public function usuarioSolicitud(){
        return $this->belongsTo(User::class,'id_usuario_solicitud','id');
    }  
    public function usuarioValida(){
        return $this->belongsTo(User::class,'id_usuario_valida','id');
    } 
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
