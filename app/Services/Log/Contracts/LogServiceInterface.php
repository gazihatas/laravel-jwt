<?php

namespace App\Services\Log\Contracts;

use App\Enums\LogLevel;

interface LogServiceInterface
{
    public function log(LogLevel $level, string $message, array $context = []): void;
    public function getAllLogs();
}
