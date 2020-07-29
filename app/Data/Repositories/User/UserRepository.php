<?php

namespace App\Data\Repositories\User;
use Illuminate\Http\Request;

interface UserRepository
{
    public function register($user);

}
