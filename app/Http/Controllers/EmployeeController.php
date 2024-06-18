<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function __construct(
        public EmployeeService $service
    ) {
    }

    public function index(Request $request)
    {
        $employees = Employee::query()
            ->where('user_id', $request->user()->id)
            ->orderBy('updated_at', 'DESC')
            ->get();

        return $this->respond(EmployeeResource::collection($employees));
    }

    public function store(EmployeeRequest $request)
    {
        try {
            return $this->respond(
                new EmployeeResource(
                    $this->service->createEmployee(Auth::user(), $request->all())
                )
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            return $this->respondErrorMessage('Error creating employee.');
        }
    }

    public function show(Employee $employee)
    {
        if ($employee->user_id == Auth()->user()->id) {
            return $this->respond(
                new EmployeeResource($employee)
            );
        }

        return $this->respondNotFound('Employee not found');
    }

    public function update(
        Employee $employee,
        EmployeeRequest $request
    ) {
        try {
            if ($employee->user_id == Auth()->user()->id) {
                $this->service->updateEmployee($employee, $request->all());
                return $this->respond(
                    new EmployeeResource($employee->refresh())
                );
            }

            return $this->respondNotFound('Employee not found');
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            return $this->respondErrorMessage('Error updating employee.');
        }
    }

    public function destroy(Employee $employee)
    {
        if ($employee->user_id == Auth()->user()->id) {
            $externalId = $employee->external_id;
            if ($employee->delete()) {
                if (!empty($externalId)) {
                    $this->service->deleteEmployee($externalId);
                }
                return $this->respond([
                    'success' => true,
                    'message' => 'Successfully deleted employee.'
                ]);
            }

            return $this->respondErrorMessage('Error on deleting employee.');
        }

        return $this->respondNotFound('Employee not found');
    }
}
