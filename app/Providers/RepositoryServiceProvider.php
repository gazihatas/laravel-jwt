<?php

namespace App\Providers;

use App\Repositories\User\Contracts\UserRepositoryInterface;
use App\Repositories\User\Implementations\PostgreSQLUserRepository;
use Exception;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

        $this->app->bind(UserRepositoryInterface::class, function () {

            $connection = config('database.default');

            if ($connection === 'pgsql') {
                return new PostgreSQLUserRepository();
            }

            //if ($connection === 'mongodb') {
            //    return new MongoDBUserRepository(
            //        new Client(config('database.mongodb.uri'))
            //    );
            //}

            throw new Exception("Unsupported database connection: " . $connection);

        });

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
