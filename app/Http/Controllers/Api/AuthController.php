<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;

use App\Http\Resources\UserResource;

use App\Services\Authentication\UserAuthentication;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserAuthentication $user)
    {
        $this->userService = $user;
    }
    
    public function login(Request $request)
    {
        $params = $request->all();
        $validate = $this->userService->validationLogin($request);
        if($validate['error']) {
            return $this->error($validate['message'],$validate['data'],$validate['code']);
        }

        $login = $this->userService->login($params);
        if($login['error']) {
            return $this->error($login['message'],$login['data'],$login['code']);
        }

        return $this->success($login['message'],$login['data']);
    }

    public function getDetail(Request $request)
    {
        $user = $request->user;
        return $this->success('Your Logged In', (new UserResource($user))->jsonSerialize());
    }
}
