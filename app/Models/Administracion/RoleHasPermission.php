<?php

namespace App\Models\Administracion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleHasPermission extends Model
{
    use HasFactory;
    protected $table = 'administracion.role_has_permissions';
    protected $connection='pgsql';

    protected $fillable=[
        "permission_id",
        "role_id",
        "updated_at",
        "created_at",
        "id_user_created",
        "id_user_updated",
    ];
}
