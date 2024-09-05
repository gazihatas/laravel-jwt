<?php

namespace App\Services\Log\Implementations;

use App\Data\LogData;
use App\Enums\LogLevel;
use App\Repositories\Log\Contracts\LogRepositoryInterface;
use App\Services\Log\Contracts\LogServiceInterface;

class LogService implements LogServiceInterface
{
    protected $logRepository;

    public function __construct(LogRepositoryInterface $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    public function log(LogLevel $level, string $message, array $context = []): void
    {
        $logData = new LogData($level->value, $message, $context);
        $this->logRepository->save($logData);
    }

    public function getAllLogs()
    {
        return $this->logRepository->getAllLogs();
    }
}
