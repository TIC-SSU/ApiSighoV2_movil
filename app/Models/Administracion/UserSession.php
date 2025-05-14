<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    use HasFactory;
    protected $table = 'administracion.user_sessions';


    protected $fillable = [
        'user_id',          
        'session_token',    
        'ip_address',       
        'device_name',      
        'login_time',       
        'logout_time',   
        'user_agent'  
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
