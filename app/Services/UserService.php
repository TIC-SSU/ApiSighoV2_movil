<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    // Add your service logic here
    public function getAllUsers()
    {
        return User::all();
    }
}
