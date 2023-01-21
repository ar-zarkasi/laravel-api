<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\Authentication\UserAuthentication;

class CustomAuthMiddleware
{
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
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization');
        if($token){
            //get token data from your table
            $tokenData = Token::where('token',$token)->first();
            //check if token is valid and not expired
            if($tokenData && $tokenData->is_valid){
                //set user on request
                $request->user = $tokenData->user;
                return $next($request);
            }
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
