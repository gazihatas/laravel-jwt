<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Services\Log\Contracts\LogServiceInterface;
use Illuminate\Http\Request;

class LogController extends Controller
{

    protected $logService;

    public function __construct(LogServiceInterface $logService)
    {
        $this->logService = $logService;
    }

    public function store(Request $request)
    {
        $this->logService->log($request->level, $request->message, $request->context ?? []);
        return response()->json(['message' => 'Log saved successfully'], 201);
    }

    public function index()
    {
        $logs = $this->logService->getAllLogs();
        return response()->json(['logs' => $logs]);
    }


}
