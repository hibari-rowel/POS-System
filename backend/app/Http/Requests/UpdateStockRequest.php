<?php

namespace App\Http\Requests;

use App\Models\ProductStock;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('quantity')) {
            $this->merge([
                'quantity' => preg_replace('/[^\d.]/', '', $this->quantity),
            ]);
        }

        if ($this->has('price')) {
            $this->merge([
                'price' => preg_replace('/[^\d.]/', '', $this->price),
            ]);
        }

        if ($this->has('subtotal')) {
            $this->merge([
                'subtotal' => preg_replace('/[^\d.]/', '', $this->subtotal),
            ]);
        }
    }

    public function rules(): array
    {
        return ProductStock::getFieldValidations(request()->all());
    }

    public function messages(): array
    {
        return ProductStock::getValidationMessages();
    }
}
