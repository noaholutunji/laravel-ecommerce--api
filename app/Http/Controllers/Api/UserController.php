<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\User;
use App\Data\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


class UserController extends Controller
{
    protected $user_repository;

    public function __construct(UserRepository $user_repository)
	{
		$this->user_repository = $user_repository;
	}

    public function register()
     {
        $attributes = request()->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'phonenumber' => 'required|digits:11',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);

        $attributes['password'] = bcrypt($attributes['password']);

        $user = new User($attributes);

        $createdUser = $this->user_repository->register($user);

        $token = $this->createAccessToken($createdUser);

        return response()->json([
            'message' => 'User created successfully!',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login()
    {
        $userLogin = request()->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if(!Auth::attempt($userLogin))
        return response()->json([
            'message' => 'Unauthorized'
        ], 401);

        $user = Auth::user();

        $token = $this->createAccessToken($user);

        return response()->json([
            'message' => 'User logged in successfully!',
            'token' => $token,
            'user' => $user,
        ], 200);
    }

    public function logout (Request $request)
     {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'User logged out successfully'
        ], 200);
    }

    public function createAccessToken($user)
    {
        return $user->createToken('Laravel Password Grant Client')->accessToken;
    }

}


