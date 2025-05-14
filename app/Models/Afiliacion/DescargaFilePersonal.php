<?php

namespace App\Models\Afiliacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescargaFilePersonal extends Model
{
    use HasFactory;
    protected $table = 'afiliacion.descarga_file_personal';
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'id_titular',
        'id_beneficiario',
        'motivo',
        'created_at',
        'updated_at',
        'id_user_created',
    
    ];

    // relacion con la tabla titular 
    public function titularDescargaFilePersonal()
    {
        return $this->belongsTo(Titular::class, 'id_titular', 'id');
    }
    // relacion con la tabla beneficiario 
    public function beneficiarioDescargaFilePersonal()
    {
        return $this->belongsTo(Beneficiario::class, 'id_beneficiario', 'id');
    }
}
