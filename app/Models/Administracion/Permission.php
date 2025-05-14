<?php
// BORIS NO TOQUES WEY :V
namespace App\Models\Administracion;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $table = 'administracion.permissions';
    protected $connection='pgsql';

    protected $fillable=[
        "name",
        "guard_name",
        'permissions_enabled'
    ];

}
