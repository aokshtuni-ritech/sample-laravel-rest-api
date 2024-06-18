<?php

namespace App\Http\Requests;

use App\Enums\EntityType;
use App\Models\EntityMapping;
use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function fallbackRules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['sometimes', 'nullable', 'string', 'email', 'max:255'],
            'job_title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'primary_phone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'tags' => ['sometimes', 'nullable', 'array', 'min:1'],
            'tags.*' => ['string', 'max:100'],
            'integration_id' => ['sometimes', 'exists:integrations,id']
        ];
    }

    public function getEmployeeEntityMapping(): ?EntityMapping
    {
        $user = $this->user();

        $entityMapping = EntityMapping::query()
            ->where('user_id', $user->id)
            ->where('integration_id', $this->input('integration_id'))
            ->where('entity_type', EntityType::EMPLOYEE->value)
            ->first();

        return $entityMapping ?: null;
    }

    public function getRules(): array
    {
        $mapping = $this->getEmployeeEntityMapping();
        if ($mapping) {
            return $this->generateRules($mapping);
        }

        return $this->fallbackRules();
    }

    public function generateRules(EntityMapping $mapping): array
    {
        $rules = [];

        foreach ($mapping as $field) {
            $fieldRules = [];
            if ($field['is_required']) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'sometimes';
                $fieldRules[] = 'nullable';
            }

            if ($field['type']) {
                $fieldRules[] = $field['type'];
            } else {
                $fieldRules[] = 'string';
            }

            if ($field['max_length']) {
                $fieldRules[] = 'max:' . $field['max_length'];
            }

            $rules[$fieldRules['provider_field']] = $fieldRules;
        }

        return $rules;
    }
}
