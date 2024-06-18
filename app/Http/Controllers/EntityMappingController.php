<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntityMappingRequest;
use App\Http\Resources\EntityMappingResource;
use App\Models\EntityMapping;
use App\Services\EntityMappingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EntityMappingController extends Controller
{
    public function __construct(
        public EntityMappingService $service
    ) {
    }
    public function index(Request $request)
    {
        $mapping = EntityMapping::query()
            ->orderBy('id', 'DESC')
            ->get();

        return $this->respond(EntityMappingResource::collection($mapping));
    }

    public function store(EntityMappingRequest $request)
    {
        try {
            $existingMapping = $this->service->getExisting($request->all());
            if ($existingMapping) {
                return $this->respondErrorMessage('An existing mapping for this user and integration of this type already exists with ID: ' . $existingMapping->id);
            }
            return $this->respond(
                new EntityMappingResource(
                    $this->service->createMapping($request->all())
                )
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            return $this->respondErrorMessage('Error creating EntityMapping.');
        }
    }

    public function show(EntityMapping $entityMapping)
    {
        return $this->respond(
            new EntityMappingResource($entityMapping)
        );
    }

    public function update(
        EntityMapping $entityMapping,
        EntityMappingRequest $request
    ) {
        try {
            return $this->respond(
                new EntityMappingResource(
                    $this->service->updateMapping($entityMapping, $request->all())
                )
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            return $this->respondErrorMessage('Error updating EntityMapping.');
        }
    }

    public function destroy(EntityMapping $entityMapping)
    {
        if ($entityMapping->delete()) {
            return $this->respond([
                'success' => true,
                'message' => 'Successfully deleted EntityMapping.'
            ]);
        }

        return $this->respondErrorMessage('Error on deleting EntityMapping.');
    }
}
