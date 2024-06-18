<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'internal_id' => $this->internal_id,
            'external_id' => $this->external_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'primary_phone' => $this->primary_phone,
            'tags' => $this->tags,
            'user_id' => $this->user_id,
            'integration_logs' => ApiLogsResource::collection($this->requestLogs)
        ];
    }
}
