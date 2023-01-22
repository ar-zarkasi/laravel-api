<?php

namespace App\Services\Authentication;

use App\Interfaces\Authentication\UserInterface;
use App\Interfaces\Authentication\TokenInterface;
use App\Http\Requests\LoginRequest;
use App\Repositories\Authentication\TokenRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Traits\ReturnServices;
use Carbon\Carbon;

class UserAuthentication
{
    use ReturnServices;
    /**
     * The auth repository instance.
     *
     * @var \App\Repositories\AuthRepository
     */
    protected $authRepository;
    protected $tokenRepository;

    /**
     * Create a new service instance.
     *
     * @param  \App\Repositories\AuthRepository  $authRepository
     * @return void
     */
    public function __construct(UserInterface $authRepository, TokenRepository $tokenRepository)
    {
        $this->authRepository = $authRepository;
        $this->tokenRepository = $tokenRepository;
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
        $response = Password::broker()->createToken($user);
        $token = $response->accessToken;
        $expired = Carbon::parse($response->token->expires_at)->toDateTimeString();
        // store a generate token and add to cache
        $data = [
            'id_user'=>$user->id,
            'username'=>$user->username,
            'email'=>$user->email,
            'phone'=>$user->phone,
            'id_roles'=>$user->id_roles,
            'roles'=>$user->roles,
        ];
        $this->tokenRepository->store([
            'id_user'=>$user->id,
            'token'=>$token,
            'data'=>json_encode($data),
            'expired'=>$expired
        ]);
        // Return the token
        return $this->response($data,'Successfully Login');
    }

    public function cekToken($token)
    {
        $userToken = $this->tokenRepository->getUserByToken($token);
        // if not exists
        if(!$userToken->id) {
            return false;
        }

        $now = Carbon::now();
        $expired = Carbon::parse($userToken->expired);
        $diff = $now->diff($expired);
        return $diff > 0;
    }

    public function getUserByToken($token)
    {
        $userToken = $this->tokenRepository->getUserByToken($token);
        // if not exists
        if(!$userToken->id || !$userToken->getUser) {
            return false;
        }

        return $userToken->getUser;
    }
}
