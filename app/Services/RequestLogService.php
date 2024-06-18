<?php

namespace App\Services;

use App\Enums\RequestType;
use App\Models\RequestLog;

class RequestLogService {

    public function logRequest(
        int $employeeId,
        int $integrationId,
        string $uri,
        RequestType $type,
        array $payload
    ): RequestLog
    {
        return RequestLog::create([
            'employee_id' => $employeeId,
            'integration_id' => $integrationId,
            'uri' => $uri,
            'type' => $type,
            'payload' => $payload
        ]);
    }
}
