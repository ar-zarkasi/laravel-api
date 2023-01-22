<?php

namespace App\Services\Authentication;

use App\Interfaces\Authentication\UserInterface;
use App\Interfaces\Authentication\TokenInterface;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Models\UserAuth;

use App\Traits\ReturnServices;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserAuthentication
{
    use ReturnServices;

    private $regex_email = '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$';
    private $regex_phone = '^(?:(?:\+|00) [1-9] [0-9]{0,2}|0)[1-9][0-9]{9}$';
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
    public function __construct(UserInterface $authRepository, TokenInterface $tokenRepository)
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
    public function login($params)
    {
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
        $data = [
            'id_user'=>$user->id,
            'username'=>$user->username,
            'email'=>$user->email,
            'phone'=>$user->phone,
            'id_roles'=>$user->id_roles,
            'roles'=>$user->roles->roles_name,
            'deviceOs'=>isset($params['deviceos']) ? $params['deviceos'] : null,
            'deviceData'=>isset($params['devicedata']) ? $params['devicedata'] : null,
        ];
        $encoded_data = json_encode($data);
        // store a generate token and add to cache
        $expired = Carbon::now()->addDay()->toDateTimeString(); // 1 day expired
        $token = $user->createToken($encoded_data)->plainTextToken;
        $this->tokenRepository->store([
            'id_user'=>$user->id,
            'token'=>$token,
            'data'=>$encoded_data,
            'expired'=>$expired
        ]);
        $data['token'] = $token;
        $data['expired_at'] = $expired;
        
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
        $diff = $now->diffInHours($expired);
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

    public function validationLogin(Request $request)
    {
        $rules = [
            'username' => 'required_without_all:email,phone',
            'email' => 'required_without_all:username,phone|email',
            'phone' => "required_without_all:username,email|regex:$this->regex_phone",
            'password' => 'required',
            'deviceos' => 'nullable',
            'devicedata' => 'nullable',
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            $error = $this->errorValidation($validator);
            return $this->response($validator->errors()->getMessages(),$error,true,422);
        }

        return $this->response();
    }
}
