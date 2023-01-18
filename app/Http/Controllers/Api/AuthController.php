<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;

use App\Http\Requests\LoginRequest;

use App\Interfaces\Authentication\UserInterface;

class AuthController extends Controller
{
    protected $userInterface;

    public function __construct(UserInterface $user)
    {
        $this->userInterface = $user;
    }
    
    public function login(LoginRequest $request)
    {
        $params = $request->all();
        
    }
}
