<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\HorizonServiceProvider::class,
    App\Providers\RepositoryServiceProvider::class,
    L5Swagger\L5SwaggerServiceProvider::class,
    MongoDB\Laravel\MongoDBServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
];
