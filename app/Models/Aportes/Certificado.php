<?php

namespace App\Models\Aportes;
use App\Models\User;
use App\Models\Administracion\Persona;
use App\Models\Aportes\Institucion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    use HasFactory;
    protected $table ='aportes.certificado';
    protected $connection = 'pgsql';
    
    protected $fillable = [
	'cod_certificado',
    'cite',
	'id_persona',
    'id_institucion',
    'nombre_institucion',
    'nit',
    'fecha_solicitud',
    'cod_seguridad',
    'fecha_validez',
    'tipo_certificado',
    'id_user_created',
	'id_user_updated',];


    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    
    
    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
    public function personaRepresentanteLegalInstitucion()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id');
    }
    public function institucionCertificado()
    {
        return $this->belongsTo(Institucion::class, 'id_institucion', 'id');
    }
}

