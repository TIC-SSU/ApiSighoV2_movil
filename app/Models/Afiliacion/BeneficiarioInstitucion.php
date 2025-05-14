<?php

namespace App\Models\Afiliacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Afiliacion\Beneficiario;
use App\Models\Aportes\Institucion;
use App\Models\User;

class BeneficiarioInstitucion extends Model
{
    use HasFactory;
    protected $table = 'afiliacion.beneficiario_institucion';
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'id_beneficiario',
        'id_institucion',
        'id_titular',
        'fecha_ingreso',
        'cargo',
        'salario',
        'estado',
        'item',
        'carga_horaria',
        'titularidad',
        'id_user_created',
        'id_user_updated',
    ];

    //Relacion con la tabla Beneficiario

    public function beneficiarioInstitucionBeneficiario(){
        return $this->belongsTo(Beneficiario::class, 'id_beneficiario', 'id');
    }

    //Relacion con la tabla Institucion

    public function institucionBeneficiarioInstitucion(){
        return $this->belongsTo(Institucion::class, 'id_institucion', 'id');
    }

    //Relacion con la tabla titular
    
    public function titularBeneficiarioInstitucion(){
        return $this->belongsTo(Titular::class, 'id_titular', 'id');
    }

    //Relacion con la tabla Users

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }

}
