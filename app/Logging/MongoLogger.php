<?php

namespace App\Logging;

use MongoDB\Client as MongoClient;
use Monolog\Handler\MongoDBHandler;
use Monolog\Logger;

class MongoLogger
{

    public function __invoke(array $config)
    {
        $mongoUri = config('database.connections.mongodb.dsn');
        $database = config('database.connections.mongodb.database', 'log_database');
        $collection = config('database.connections.mongodb.collection', 'logs');

        $mongoClient = new MongoClient($mongoUri);
        $handler = new MongoDBHandler($mongoClient, $database, $collection);

        $logger = new Logger('mongodb');
        $logger->pushHandler($handler);

        return $logger;
    }

}
