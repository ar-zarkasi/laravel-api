<?php

namespace App\Http\Middleware;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Traits\ResponseAPI;
use Closure;

class CheckHeader
{
    use ResponseAPI;
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return mixed
     */

    protected $exceptRoutes = [
        
    ];

    public function handle($request, Closure $next)
    {
        if(!$request->hasHeader('Accept') || $request->hasHeader('tn-client') || !in_array($request->path(), $this->exceptRoutes)) {
            return $this->error('Cannot Authorized',[],401);
        }
        
        return $next($request);
    }
}
