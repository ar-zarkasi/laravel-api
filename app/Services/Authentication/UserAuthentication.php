<?php

namespace App\Services\Authentication;

use App\Interfaces\Authentication\UserInterface;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Traits\ReturnServices;

class UserAuthentication
{
    use ReturnServices;
    /**
     * The auth repository instance.
     *
     * @var \App\Repositories\AuthRepository
     */
    protected $authRepository;

    /**
     * Create a new service instance.
     *
     * @param  \App\Repositories\AuthRepository  $authRepository
     * @return void
     */
    public function __construct(UserInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Attempt to log the user in.
     *
     * @param  array  $credentials
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $params = $request->all();
        if(isset($params['username'])){
            $user = $this->authRepository->getUserByUsername($params['username']);
        } else if(isset($params['phone'])) {
            $user = $this->authRepository->getUserByPhone($params['phone']);
        } else {
            $user = $this->authRepository->getUserByEmail($params['email']);
        }
        // Check if the user exists
        if(!$user->id) {
            $message = 'User Not Registered!';
            return $this->response(['user'=>$message],$message,true,422);
        }
        // if the password is correct
        if (!Hash::check($params['password'], $user->password)) {
            $message = 'Invalid credentials';
            return $this->response(['password'=>$message],true,422);
        }

        // Generate a JWT token
        $token = JWTAuth::fromUser($user);
        // store a generate token and add to cache
        
        // Return the token
        return response()->json(compact('token'));
    }

    public function cekToken($token)
    {
        $userToken = '';
    }
}
