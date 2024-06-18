<?php

namespace App\DTO;

use App\Models\Employee;
use Spatie\LaravelData\Data;

class TrackTikEmployee extends Data {

    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $customId,
        public ?string $email,
        public ?string $jobTitle,
        public ?string $primaryPhone,
        public ?array $tags,
    ) {
    }

    public static function fromEmployee(Employee $employee)
    {
        return self::from([
            'firstName' => $employee->first_name,
            'lastName' => $employee->last_name,
            'email' => $employee->email,
            'jobTitle' => $employee->job_title,
            'primaryPhone' => $employee->primary_phone,
            'customId' => $employee->internal_id,
            'tags' => $employee->tags
        ]);
    }
}
