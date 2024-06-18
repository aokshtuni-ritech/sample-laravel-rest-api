<?php

namespace App\Services\HttpClient;

use App\Enums\RequestType;
use App\Models\Integration;
use App\Models\RequestLog;
use App\Services\RequestLogService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class BaseClient {

    public bool $initialized = false;
    public string $baseUrl = '';
    public array $headers = [];
    public ?Integration $integration = null;

    public function __construct(
        public RequestLogService $requestLogService
    ) {
    }

    public function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    public function get($uri, $query = [])
    {
        try {
             return Http::baseUrl($this->baseUrl)
                ->withHeaders($this->headers)
                ->get($uri, $query);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function post($uri, $body = [])
    {
        try {
            return Http::baseUrl($this->baseUrl)
                ->withHeaders($this->headers)
                ->post($uri, $body);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function put($uri, $body = [])
    {
        try {
            return Http::baseUrl($this->baseUrl)
                ->withHeaders($this->headers)
                ->put($uri, $body);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function refreshToken($uri, $body = null)
    {
        try {
            return Http::baseUrl($this->baseUrl)
                ->asForm()
                ->post($uri, $body);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function logRequest(
        int $employeeId,
        int $integrationId,
        string $uri,
        RequestType $type,
        array $payload
    ): RequestLog
    {
        return $this
            ->requestLogService
            ->logRequest($employeeId, $integrationId, $uri, $type, $payload);
    }

    public function isOK(int $status)
    {
        return $status >= Response::HTTP_OK && $status < 300;
    }
}
