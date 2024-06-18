<?php

namespace App\Services;

use App\DTO\TrackTikEmployee;
use App\Jobs\CreateEmployeeJob;
use App\Jobs\UpdateEmployeeJob;
use App\Models\Employee;
use App\Models\User;
use App\Services\HttpClient\TrackTikClient;
use Illuminate\Support\Str;

class EmployeeService {

    public function __construct(
        public TrackTikClient $trackTikClient
    ) {
    }

    public function createEmployee(User $authUser, array $data)
    {
        $employee = Employee::create(
            array_merge(
                $data,
                [
                    'internal_id' => Str::uuid(),
                    'user_id' => $authUser->id,
                ]
            )
        );

        $employee = $this->sendEmployeeToIntegration($employee);

        // Here this is better to be handled by a Job on the queue instead of waiting for the HTTP request.
        // CreateEmployeeJob::dispatch($employee);

        return $employee;
    }

    public function updateEmployee(Employee $employee, array $data)
    {
         $employee->update(
            $data
        );

        if (!empty($employee->external_id)) {
            $toIntegration = $this->updateEmployeeToIntegration($employee);

            // Here this is better to be handled by a Job on the queue instead of waiting for the HTTP request.
            // UpdateEmployeeJob::dispatch($employee);
        }

        return $employee;
    }

    public function sendEmployeeToIntegration(Employee $employee): Employee
    {
        $response = $this
            ->trackTikClient
            ->createEmployee($employee, TrackTikEmployee::fromEmployee($employee));

        if ($response && isset($response['data']['id'])) {
            $employee->update([
                'external_id' => $response['data']['id']
            ]);
        }

        return $employee;
    }

    public function updateEmployeeToIntegration(Employee $employee)
    {
        return $this
            ->trackTikClient
            ->updateEmployee(
                $employee,
                TrackTikEmployee::fromEmployee($employee)
            );
    }
}
