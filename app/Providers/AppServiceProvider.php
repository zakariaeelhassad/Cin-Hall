<?php

namespace App\Providers;

use App\Repositories\FilmRepository;
use App\Repositories\interface\RepositoryInterface;
use App\Repositories\SalleRepository;
use App\Repositories\SeanceRepository;
use App\Repositories\SiegeRepository;
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
        // $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        // $this->app->bind(FilmRepositoryInterface::class, FilmRepository::class);
        // $this->app->bind(SiegeRepositoryInterface::class, SiegeRepository::class);
        // $this->app->bind(SalleRepositoryInterface::class, SalleRepository::class);
        // $this->app->bind(SeanceRepositoryInterface::class, SeanceRepository::class);
        
        // $this->app->bind(UserService::class, function ($app) {
        //     return new UserService($app->make(UserRepositoryInterface::class));
        // });

        // $this->app->bind(FilmService::class, function ($app) {
        //     return new FilmService($app->make(FilmRepositoryInterface::class));
        // });

        // $this->app->bind(SiegeService::class, function ($app) {
        //     return new SiegeService($app->make(SiegeRepositoryInterface::class));
        // });

        // $this->app->bind(SalleService::class, function ($app) {
        //     return new SalleService($app->make(SalleRepositoryInterface::class));
        // });

        // $this->app->bind(SeanceService::class, function ($app) {
        //     return new SeanceService($app->make(SeanceRepositoryInterface::class));
        // });
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
