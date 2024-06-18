<?php

namespace App\Http\Controllers;

use App\Http\Resources\EntityMappingResource;
use App\Models\EntityMapping;
use Illuminate\Http\Request;

class EntityMappingController extends Controller
{
    public function index(Request $request)
    {
        $mapping = EntityMapping::query()
            ->where('user_id', $request->user()->id)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return $this->respond(EntityMappingResource::collection($mapping));
    }

    public function store(EmployeeCreateRequest $request, EmployeeService $service)
    {
        try {
            return $this->respond(
                new EmployeeResource(
                    $service->createEmployee($request->all())
                )
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            return $this->respondErrorMessage('Error creating employee.');
        }
    }

    public function show(Employee $employee)
    {
        return $this->respond(
            new EmployeeResource($employee)
        );
    }

    public function update(
        Employee $employee,
        EmployeeCreateRequest $request,
        EmployeeService $service
    ) {
        try {
            return $this->respond(
                new EmployeeResource(
                    $service->updateEmployee($employee, $request->all())
                )
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            return $this->respondErrorMessage('Error updating employee.');
        }
    }

    public function destroy(Employee $employee)
    {
        if ($employee->delete()) {
            return $this->respond([
                'success' => true,
                'message' => 'Successfully deleted employee.'
            ]);
        }

        return $this->respondErrorMessage('Error on deleting employee.');
    }
}
