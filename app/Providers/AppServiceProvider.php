<?php

namespace App\Providers;

use App\Repositories\Log\Contracts\LogRepositoryInterface;
use App\Repositories\Log\Implementations\MongoDBLogRepository;
use App\Repositories\Log\Implementations\MySQLLogRepository;
use App\Repositories\Log\Implementations\PostgreSQLLogRepository;
use App\Services\Auth\Contracts\JWTAuthInterface;
use App\Services\Auth\Implementations\JWTAuthService;
use App\Services\Log\Contracts\LogServiceInterface;
use App\Services\Log\Implementations\LogService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(JWTAuthInterface::class,JWTAuthService::class);

        $this->app->bind(LogServiceInterface::class, function (Application $app) {
            return new LogService($app->make(LogRepositoryInterface::class));
        });

        $this->app->singleton(LogRepositoryInterface::class, function ($app) {

            switch (config('database.log')) {
                case 'mysql':
                    return new MySQLLogRepository();
                case 'pgsql':
                    return new PostgreSQLLogRepository();
                case 'mongodb':
                    return new MongoDBLogRepository($app['db']->connection('mongodb'));
                default:
                    throw new \Exception("Unsupported database type");
            }
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
