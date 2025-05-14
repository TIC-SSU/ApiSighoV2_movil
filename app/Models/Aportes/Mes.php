<?php

namespace App\Models\Aportes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Aportes\AportesInstitucional;
use App\Models\Aportes\AporteVoluntario;
use App\Models\Aportes\Comprobante;
use App\Models\Aportes\ComprobanteTemp;
use App\Models\Aportes\PlanillaBajaMedica;
use App\Models\User;

class Mes extends Model
{
    use HasFactory;
    protected $table = 'aportes.mes';

    protected $fillable = [
        'mes',
        'numero',
    ];

    public function aportesInstitucionalMes()
    {
        return $this->hasMany(AportesInstitucional::class, 'id_mes');
    }

    public function aportesVoluntarioMes()
    {
        return $this->hasMany(AporteVoluntario::class, 'id_mes');
    }

    public function comprobantesMes()
    {
        return $this->hasMany(Comprobante::class, 'id_mes');
    }

    public function planillasBajaMedicaMes()
    {
        return $this->hasMany(PlanillaBajaMedica::class, 'id_mes');
    }
    //Relaciones con la Tabla Usuario

    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }    

    public function usuarioEditor(){
        return $this->belongsTo(User::class,'id_user_updated','id');
    }

    //Relaciones has many

    public function comprobanteMes(){
        return $this->hasMany(Comprobante::class,'id_mes', 'id');
    }

    public function comprobanteTempMes(){
        return $this->hasMany(ComprobanteTemp::class,'id_mes', 'id');
    }
}
