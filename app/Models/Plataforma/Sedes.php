<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Plataforma\Consultorio;
use App\Models\Administracion\Residencia;
use Illuminate\Database\Eloquent\Model;

class Sedes extends Model
{
    use HasFactory;
    protected $table = 'plataforma.sedes';
    protected $connection = 'pgsql';
    protected $fillable = ['ubicacion', 'piso', 'id_residencia', 'id_user_created', 'id_user_updated'];

    //Relaciones con la tabla Consultorio
    public function sedesConsultorio()
    {
        return $this->hasMany(Consultorio::class, 'id_sede', 'id');
    }

    public function residenciaSedes()
    {
        return $this->belongsTo(Residencia::class, 'id_residencia', 'id');
    }
}
