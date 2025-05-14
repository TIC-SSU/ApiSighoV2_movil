<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolUsuario extends Model
{
    use HasFactory;
    protected $table = 'administracion.model_has_roles';
    protected $connection='pgsql';

    protected $fillable=[
        "role_id",
        "model_type",
        "model_id",
    ];
}
