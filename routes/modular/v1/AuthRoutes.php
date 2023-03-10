<?php
namespace CustomRouting\v1;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Request;

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

        Route::middleware(['auth.user.admin'])->prefix('user')->group(function(){
            Route::get('detail',[AuthController::class,'getDetail'])->name('auth.detail');
        });
    }
}
