<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Validation\Rules\Password;

class UserCreateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'unique:users,email'],
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                'confirmed'
            ],
            'role' => ['required', 'string', 'in:'.implode(',', UserRole::values())],
        ];
    }
}
