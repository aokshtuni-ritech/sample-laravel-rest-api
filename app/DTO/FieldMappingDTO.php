<?php

namespace App\DTO;

use Spatie\LaravelData\Data;

class FieldMappingDTO extends Data {

    public function __construct(
        public string $firstName,
        public string $lastName,
        public ?string $email,
        public ?string $jobTitle,
        public ?string $primaryPhone,
        public ?array $tags,
    ) {
    }
}
