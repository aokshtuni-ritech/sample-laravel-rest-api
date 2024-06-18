<?php

namespace App\Http\Requests;

use App\Enums\EntityType;
use Illuminate\Foundation\Http\FormRequest;

class EntityMappingRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'integration_id' => ['required', 'integer', 'exists:integrations,id'],
            'entity_type' => ['required', 'string', 'in:'.implode(EntityType::values())],
            'mapping' => ['required', 'array', 'min:1'],
            'mapping.*.integration_field' => ['required', 'string', 'max:100'],
            'mapping.*.is_required' => ['required', 'boolean'],
            'mapping.*.type' => ['required', 'string', 'in:string,boolean,array,integer'],
            'mapping.*.provider_field' => ['required', 'string', 'max:100'],
            'mapping.*.max_length' => ['sometimes', 'nullable', 'integer', 'min:1'],
        ];
    }
}
