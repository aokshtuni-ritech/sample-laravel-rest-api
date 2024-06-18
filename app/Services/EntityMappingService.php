<?php

namespace App\Services;

use App\Models\EntityMapping;

class EntityMappingService {

    public function createMapping(
        array $data
    ): EntityMapping
    {
        return EntityMapping::create($data);
    }

    public function getExisting(
        array $data
    ): ?EntityMapping
    {
        return EntityMapping::query()
            ->where('user_id', $data['user_id'])
            ->where('integration_id', $data['integration_id'])
            ->where('entity_type', $data['entity_type'])
            ->first();
    }

    public function updateMapping(
        EntityMapping $mapping,
        array $data
    ): EntityMapping
    {
        $mapping->update($data);

        return $mapping;
    }
}
