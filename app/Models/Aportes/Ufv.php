<?php

namespace App\Models\Aportes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ufv extends Model
{
    use HasFactory;
    protected $table = 'aportes.ufv';
    protected $connection = 'pgsql';
    protected $fillable = [
        'valor',
        'fecha',
        'estado',
        'id_user_created',
        'id_user_updated',
    ];



    public function userCreated()
    {
        return $this->belongsTo(User::class, 'id_user_created');
    }

    public function userUpdated()
    {
        return $this->belongsTo(User::class, 'id_user_updated');
    }
}
