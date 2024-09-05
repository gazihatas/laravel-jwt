<?php

namespace App\Repositories\Log\Implementations;

use App\Data\LogData;
use App\Repositories\Log\Contracts\LogRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MySQLLogRepository implements LogRepositoryInterface
{

    public function save(LogData $logData): void
    {
        DB::table('logs')->insert([
            'level' => $logData->level,
            'message' => $logData->message,
            'context' => json_encode($logData->context),
            'created_at' => now(),
        ]);
    }

    public function getAllLogs(): Collection
    {
       return [];
    }
}
