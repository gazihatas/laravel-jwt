<?php

namespace App\Providers;

use App\Repositories\Log\Contracts\LogRepositoryInterface;
use App\Repositories\Log\Implementations\MongoDBLogRepository;
use App\Repositories\Log\Implementations\MySQLLogRepository;
use App\Repositories\Log\Implementations\PostgreSQLLogRepository;
use App\Services\Log\Contracts\LogServiceInterface;
use App\Services\Log\Implementations\LogService;
use Illuminate\Support\ServiceProvider;

class LogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
//        $this->app->singleton(LogRepositoryInterface::class, function ($app) {
//            switch (config('database.log')) {
//                case 'mysql':
//                    return new MySQLLogRepository();
//                case 'pgsql':
//                    return new PostgreSQLLogRepository();
//                case 'mongodb':
//                    return new MongoDBLogRepository($app['db']->connection('mongodb'));
//                default:
//                    throw new \Exception("Unsupported database type");
//            }
//        });

//        $this->app->bind(LogServiceInterface::class, function ($app) {
//            return new LogService($app->make(LogRepositoryInterface::class));
//        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
