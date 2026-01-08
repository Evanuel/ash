<?php
// app/Services/LogService.php

namespace App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogService
{
    public function apiRequest(Request $request, $response, $duration): void
    {
        Log::channel('api')->info('API Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_id' => $request->user()?->id,
            'client_id' => $request->user()?->client_id,
            'duration' => $duration,
            'status' => $response->status(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}