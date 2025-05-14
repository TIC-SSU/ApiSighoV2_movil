<?php

namespace App\Models\Administracion;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $table = 'administracion.roles';
    protected $connection='pgsql';

    protected $filable=[
        "name",
        "guard_name",
        "enabled",
    ];
}
