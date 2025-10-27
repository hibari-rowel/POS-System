<?php

namespace App\Http\Requests;

use App\Models\Sale;
use Illuminate\Foundation\Http\FormRequest;

class CreateSalesRequest extends FormRequest
{
    private $excludedFields = [];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return Sale::getFieldValidations(request()->all());
    }

    public function messages(): array
    {
        return Sale::getValidationMessages();
    }
}
