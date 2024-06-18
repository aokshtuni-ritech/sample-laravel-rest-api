<?php

namespace App\Services\HttpClient;

use App\DTO\TrackTikEmployee;
use App\Enums\RequestType;
use App\Models\Employee;
use App\Models\Integration;
use App\Services\RequestLogService;
use Illuminate\Http\Response;

class TrackTikClient extends BaseClient {

    public const INTEGRATION_ID = 1;

    public function __construct(
        public RequestLogService $requestLogService
    ) {
        $this->initializeIntegration();

        parent::__construct($this->requestLogService);
    }

    private function initializeIntegration(): void
    {
        $integration = Integration::find(self::INTEGRATION_ID);
        if ($integration && $integration->enabled) {
            $this->integration = $integration;
            $this->setBaseUrl($integration->base_url);
            if ($integration->has_auth && !empty($integration->token_type) && !empty($integration->access_token)) {
                $this->setHeaders([
                    'Authorization' => $integration->token_type.' '.$integration->access_token,
                    // 'Content-Type' => 'application/json',
                ]);
                $this->initialized = true;
            }
        }
    }

    /**
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     * @throws \Exception
     */
    public function listEmployees()
    {
        $response = $this->trySend('GET', 'employees');
        if ($response->status() === Response::HTTP_OK) {
            return json_decode($response->body());
        }

        return null;
    }

    /**
     * @param array $body
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     * @throws \Exception
     */
    public function createEmployee(Employee $employee, TrackTikEmployee $dto)
    {
        $uri = 'v1/employees';

        $requestLog = $this->logRequest(
            $employee->id,
            $this->integration->id,
            $uri,
            RequestType::POST,
            $dto->toArray()
        );

        $response = $this->trySendPost(
            $uri,
            $dto->toArray()
        );

        $requestLog->update([
            'status' => $response->status(),
            'response' => $response->body()
        ]);

        if ($this->isOK($response->status())) {
            return json_decode($response->body(), true);
        }

        return [];
    }

    /**
     * @param int $id
     * @param array $body
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     * @throws \Exception
     */
    public function updateEmployee(Employee $employee, TrackTikEmployee $dto)
    {
        $uri = 'v1/employees/'.$employee->external_id;

        $requestLog = $this->logRequest(
            $employee->id,
            $this->integration->id,
            $uri,
            RequestType::PUT,
            $dto->toArray()
        );

        $response = $this->trySendPut(
            $uri,
            $dto->toArray()
        );

        $requestLog->update([
            'status' => $response->status(),
            'response' => $response->body()
        ]);

        if ($this->isOK($response->status())) {
            return json_decode($response->body(), true);
        }

        return [];
    }

    public function trySendPost($uri, $body = [])
    {
        $response = $this->post($uri, $body);
        if ($response->status() == Response::HTTP_UNAUTHORIZED) {
            // REFRESH TOKEN
            $refreshBody = [
                'grant_type' => 'refresh_token',
                'client_id' => $this->integration->client_id,
                'client_secret' => $this->integration->client_secret,
                'refresh_token' => $this->integration->refresh_token,
            ];
            $response = $this->refreshToken('oauth2/access_token', $refreshBody);

            if ($this->isOK($response->status())) {
                $responseDecoded = json_decode($response->body(), true);
                $this->integration->update([
                    'access_token' => $responseDecoded['access_token'],
                    'refresh_token' => $responseDecoded['refresh_token'],
                ]);

                $this->initializeIntegration();

                return $this->post($uri, $body);
            }
        }

        return $response;
    }

    public function trySendPut($uri, $body = [])
    {
        $response = $this->put($uri, $body);
        if ($response->status() == Response::HTTP_UNAUTHORIZED) {
            // REFRESH TOKEN
            $refreshBody = [
                'grant_type' => 'refresh_token',
                'client_id' => $this->integration->client_id,
                'client_secret' => $this->integration->client_secret,
                'refresh_token' => $this->integration->refresh_token,
            ];
            $response = $this->refreshToken('oauth2/access_token', $refreshBody);

            if ($this->isOK($response->status())) {
                $responseDecoded = json_decode($response->body(), true);
                $this->integration->update([
                    'access_token' => $responseDecoded['access_token'],
                    'refresh_token' => $responseDecoded['refresh_token'],
                ]);

                $this->initializeIntegration();

                return $this->put($uri, $body);
            }
        }

        return $response;
    }
}
