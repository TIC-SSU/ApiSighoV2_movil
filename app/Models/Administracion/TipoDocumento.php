<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Administracion\DocumentoPDF;

class TipoDocumento extends Model
{
    use HasFactory;
    protected $table = 'administracion.tipo_documento';
    protected $connection = 'pgsql';
    protected $fillable = [
        'id',
        'nombre',
        'descripcion',
    ];
     public function DocumentoPDF(){
        return $this->hasMany(DocumentoPDF::class,'id_tipo_documento','id');
    } 
}
