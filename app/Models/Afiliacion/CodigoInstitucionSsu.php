<?php

namespace App\Models\Afiliacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Aportes\Institucion;

class CodigoInstitucionSsu extends Model
{
    use HasFactory;

    protected $table = 'afiliacion.codigo_institucion_ssu';

    protected $connection = 'pgsql';
    protected $fillable = [
        'codigo',
        'tipo_titular',
        'tipo_beneficiario',
        'usado',
        'id_institucion',
    ];

    public function institucionCodigoInstitucion()
    {
        return $this->belongsTo(Institucion::class, 'id_institucion');
    }
    //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }
}
