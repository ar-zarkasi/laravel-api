<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Authentication\UserAuthentication;
use App\Traits\ResponseAPI;

class AdminAuth
{
    use ResponseAPI;

    protected $userService;

    public function __construct(UserAuthentication $userInterface)
    {
        $this->userService = $userInterface;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        if($token){
            //get token data from your table
            $tokenExists = $this->userService->cekToken($token);
            //check if token is valid and not expired
            if($tokenExists){
                // get user data
                $tokenData = $this->userService->getUserByToken($token);
                if($tokenData != false){
                    //set user on request
                    $request->user = $tokenData;
                    return $next($request);   
                }
            }
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
