<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * Get all users with the role of 'admin'.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAdminUsers()
    {
        return User::admins()->get();
    }
}
