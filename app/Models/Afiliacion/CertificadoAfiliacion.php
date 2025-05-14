<?php

namespace App\Models\Afiliacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Administracion\Persona;

class CertificadoAfiliacion extends Model
{
    use HasFactory;

    protected $table = 'afiliacion.certificado_afiliacion';
    protected $connection = 'pgsql';

    protected $fillable = [
        'id',
        'nro_cite',
        'gestion',
        'fecha_emision',
        'fecha_caducidad',
        'cod_certificado',
        'codigo_seguridad',
        'id_persona',
        'motivo',
        'estado',
        'id_user_created',
	    'id_user_updated', 
    ];

    public function personaCertificadoAfiliacion()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }
    //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
