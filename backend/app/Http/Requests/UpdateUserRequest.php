<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    private $excludedFields = [
        'confirm_password',
        'password',
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = User::getFieldValidations(request()->all());
        foreach ($this->excludedFields as $field) {
            if (array_key_exists($field, $rules)) {
                unset($rules[$field]);
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return User::getValidationMessages();
    }
}
