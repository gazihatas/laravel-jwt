<?php

namespace App\Repositories\Log\Contracts;

use App\Data\LogData;
use Illuminate\Support\Collection;

interface LogRepositoryInterface
{
    public function save(LogData $logData): void;

    public function getAllLogs(): Collection;
}
