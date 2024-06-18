<?php

namespace App\Http\Requests;

class SetTokenRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'access_token' => ['required', 'string', 'min:1'],
            'refresh_token' => ['required', 'string', 'min:1'],
            'client_id' => ['required', 'string', 'min:1'],
            'client_secret' => ['required', 'string', 'min:1'],
        ];
    }
}
