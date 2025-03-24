<?php

namespace App\Providers;

use App\Repositories\FilmRepository;
use App\Repositories\interface\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Services\FilmService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserRepositoryInterface::class, FilmRepository::class);
        
        $this->app->bind(UserService::class, function ($app) {
            return new UserService($app->make(UserRepositoryInterface::class));
        });

        $this->app->bind(FilmService::class, function ($app) {
            return new FilmService($app->make(UserRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
