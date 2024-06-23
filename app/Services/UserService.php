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

     /**
     * Get all users with the role of 'admin'.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCustomerUser()
    {
        return User::customer()->get();
    }

    /**
     * Count the number of users with the role of 'admin'.
     *
     * @return int
     */
    public function countAdminUsers()
    {
        return User::admins()->count();
    }

  /**
     * Count the total number of users.
     *
     * @return int
     */
    public function countAllUsers()
    {
        return User::count();
    }


}
