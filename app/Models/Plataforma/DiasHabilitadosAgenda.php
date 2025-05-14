<?php

namespace App\Models\Plataforma;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Plataforma\ServicioPlataforma;
class DiasHabilitadosAgenda extends Model
{
    use HasFactory;

    protected $table = "plataforma.dias_habilitados_agenda";
    protected $connection = 'pgsql';
    protected $fillable = [
        'nro_dias',
        'id_servicio_plataforma',
        'id_user_created',
        'estado',
        'id_user_updated',
    ] ;

        //Relaciones con la Tabla Usuario
        public function usuarioCreador(){
            return $this->belongsTo(User::class,'id_user_created','id');
        }    
    
        public function usuarioEditor(){
            return $this->belongsTo(User::class,'id_user_updated','id');
        }
        //Relacion con servicio
        public function servicioPlataforma(){
            return $this->belongsTo(ServicioPlataforma::class,'id_servicio_plataforma','id');
        }
}
