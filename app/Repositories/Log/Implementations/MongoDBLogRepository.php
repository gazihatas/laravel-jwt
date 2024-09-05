<?php

namespace App\Repositories\Log\Implementations;

use App\Data\LogData;
use App\Repositories\Log\Contracts\LogRepositoryInterface;
use MongoDB\Laravel\Connection;
use Illuminate\Support\Collection;

class MongoDBLogRepository implements LogRepositoryInterface
{

    protected $collection;

    public function __construct(Connection $mongo)
    {
        $this->collection = $mongo->selectCollection('logs');
    }

    public function save(LogData $logData): void
    {
        $this->collection->insertOne([
            'level' => $logData->level,
            'message' => $logData->message,
            'context' => json_encode($logData->context),
            'created_at' => now(),
        ]);
    }

    public function getAllLogs(): Collection
    {
        return collect($this->collection->find()->toArray());
    }
}
