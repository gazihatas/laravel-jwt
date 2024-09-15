<?php

namespace App\Providers;

use App\Repositories\Permission\Contracts\PermissionRepositoryInterface;
use App\Repositories\Permission\Implementations\PostgreSQLPermissionRepository;
use App\Repositories\Role\Contracts\RoleRepositoryInterface;
use App\Repositories\Role\Implementations\PostgreSQLRoleRepository;
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

        $this->app->bind(RoleRepositoryInterface::class, PostgreSQLRoleRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PostgreSQLPermissionRepository::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
