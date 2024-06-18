<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetTokenRequest;
use App\Http\Resources\IntegrationResource;
use App\Models\Integration;
use App\Services\HttpClient\TrackTikClient;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    public function setToken(Integration $integration, SetTokenRequest $request)
    {
        if ($integration->update($request->toArray())) {
            return $this->respond(
                new IntegrationResource($integration)
            );
        }

        return $this->respondErrorMessage('Error setting new token');
    }
}
