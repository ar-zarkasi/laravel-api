<?php
namespace CustomRouting\v1;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

class AuthRoutes
{

    public function __construct()
    {
        $this->registerRoutes();
    }

    private function registerRoutes(){
        Route::prefix('auth')->group(function(){
            Route::post('login', [AuthController::class, 'login'])->name('auth.login');
            Route::post('register', [AuthController::class, 'register'])->name('auth.register');
        });

        Route::middleware('auth.admin')->prefix('user')->group(function(){
            Route::post('detail',[AuthController::class,'detail'])->name('auth.detail');
        });
    }
}
