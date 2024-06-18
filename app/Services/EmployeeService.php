<?php

namespace App\Services;

use App\DTO\TrackTikEmployee;
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

        $toIntegration = $this->sendEmployeeToIntegration($employee);

        if ($toIntegration && isset($toIntegration['data']['id'])) {
            $employee->update([
                'external_id' => $toIntegration['data']['id']
            ]);
        }

        return $employee;
    }

    public function updateEmployee(Employee $employee, array $data)
    {
         $employee->update(
            $data
        );

        if (!empty($employee->external_id)) {
            $toIntegration = $this->updateEmployeeToIntegration($employee);
        }

        return $employee;
    }

    public function sendEmployeeToIntegration(Employee $employee)
    {
        return $this
            ->trackTikClient
            ->createEmployee($employee, TrackTikEmployee::fromEmployee($employee));
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
