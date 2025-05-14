<?php

namespace App\Models\Aportes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Aportes\Institucion;
use App\Models\Aportes\Mes;

class ComprobanteEliminado extends Model
{
    use HasFactory;
    protected $table ='aportes.comprobante_eliminado';
    protected $connection = 'pgsql';
    
    protected $fillable = [
	'id_usuario_elimina',
	'id_comprobante',
	'id_institucion',
	'id_mes',
	'gestion',
	'total_salario_cotizable',
	'nro_aportantes',
    'motivo_eliminacion'  ,
    'id_user_created',
    'id_user_updated'    
    ];
    
    public function usuarioCreador(){
        return $this->belongsTo(User::class,'id_user_created','id');
    }  
      
    public function institucionSeleccionada(){
        return $this->belongsTo(Institucion::class,'id_institucion','id');
    }  
    public function mesSeleccionado(){
        return $this->belongsTo(Mes::class,'id_mes','id');
    }  
    public function usuarioElimina(){
        return $this->belongsTo(User::class,'id_usuario_elimina','id');
    } 
}
