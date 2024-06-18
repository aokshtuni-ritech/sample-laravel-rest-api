<?php

namespace App\Services;

use App\DTO\TrackTikEmployee;
use App\Enums\EntityType;
use App\Jobs\CreateEmployeeJob;
use App\Jobs\UpdateEmployeeJob;
use App\Models\Employee;
use App\Models\EntityMapping;
use App\Models\User;
use App\Services\HttpClient\TrackTikClient;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mockery\Exception;

class EmployeeService {

    public function __construct(
        public TrackTikClient $trackTikClient
    ) {
    }

    public function createEmployee(User $authUser, array $data)
    {
        $converted = $this->convertForIntegration($authUser, $data);
        $forModel = $this->convertSnakeCase($converted);

        $employee = Employee::create(
            array_merge(
                $forModel,
                [
                    'internal_id' => Str::uuid(),
                    'user_id' => $authUser->id,
                ]
            )
        );

        $employee = $this->sendEmployeeToIntegration($employee, $converted);

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

    public function sendEmployeeToIntegration(Employee $employee, array $converted = []): Employee
    {
        $payload = $converted;
        if (empty($payload)) {
            $payload = TrackTikEmployee::fromEmployee($employee)->toArray();
        }

        $response = $this
            ->trackTikClient
            ->createEmployee($employee, $payload);

        if ($response && isset($response['data']['id'])) {
            $employee->update([
                'external_id' => $response['data']['id']
            ]);
        }

        return $employee;
    }

    public function updateEmployeeToIntegration(Employee $employee, array $converted = [])
    {
        $payload = $converted;
        if (empty($payload)) {
            $payload = TrackTikEmployee::fromEmployee($employee)->toArray();
        }

        return $this
            ->trackTikClient
            ->updateEmployee(
                $employee,
                $payload
            );
    }

    private function convertForIntegration(User $authUser, array $requestInput): array
    {
        $entityMapping = $this->getEmployeeEntityMapping($authUser);
        if ($entityMapping && $entityMapping->mapping) {
            $converted = [];
            foreach ($requestInput as $key => $input) {
                $providerFiled = array_filter($entityMapping->mapping, function ($element) use ($key) {
                    return $element['provider_field'] == $key;
                });

                $config = array_pop($providerFiled);
                if (!empty($config) && !empty($config['integration_field'])) {
                    $converted[$config['integration_field']] = $input;
                } else {
                    abort(Response::HTTP_BAD_REQUEST, 'Error in field mapping.');
                }
            }

            return $converted;
        }

        return $requestInput;
    }
    private function getEmployeeEntityMapping(User $authUser): ?EntityMapping
    {
        $entityMapping = EntityMapping::query()
            ->where('user_id', $authUser->id)
            // ->where('integration_id', $this->input('integration_id'))
            ->where('integration_id', 1)
            ->where('entity_type', EntityType::EMPLOYEE->value)
            ->first();

        return $entityMapping ?: null;
    }

    private function convertSnakeCase(array $data): array
    {
        $converted = [];

        foreach ($data as $key => $value) {
            $converted[Str::snake($key)] = $value;
        }

        return $converted;
    }
}
