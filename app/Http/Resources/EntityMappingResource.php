<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntityMappingResource extends JsonResource
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
            'user_id' => $this->user_id,
            'integration_id' => $this->integration_id,
            'entity_type' => $this->entity_type,
            'mapping' => $this->mapping,
            'integration' => new IntegrationResource($this->integration),
        ];
    }
}
