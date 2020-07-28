<?php

namespace App\Data\Repositories\User;

use App\User;

class EloquentRepository implements UserRepository
{
    public function register($user)
    {
        $user->save();

        return $user;
    }
}
