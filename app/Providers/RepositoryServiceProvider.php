<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Users;
use App\Interfaces\Authentication\UserInterface;
use App\Repositories\Authentication\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // $this->app->singleton(UserStatsInterface::class, function () {
        //     return new UserStatsRepository(new UserStatsModel());
        // });
        $this->app->singleton(UserInterface::class, function (){
            return new UserRepository(new Users());
        });
    }

    public function provides()
    {
        return [
            UserInterface::class,
        ];
    }
}