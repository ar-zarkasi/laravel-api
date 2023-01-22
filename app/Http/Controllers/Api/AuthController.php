<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;

use App\Http\Requests\LoginRequest;

use App\Services\Authentication\UserAuthentication;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserAuthentication $user)
    {
        $this->userService = $user;
    }
    
    public function login(LoginRequest $request)
    {
        $login = $this->userService->login($request);
        if($login['error']) {
            return $this->error($login['message'],$login['code']);
        }

        return $this->success($login['message'],$login['data']);
    }
}
