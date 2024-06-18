<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiLogsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'integration_id' => $this->integration_id,
            'type' => $this->type,
            'uri' => $this->uri,
            'payload' => $this->payload,
            'status' => $this->status,
            'response' => $this->response,
        ];
    }
}
