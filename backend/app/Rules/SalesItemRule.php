<?php

namespace App\Rules;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Log;

class SalesItemRule implements ValidationRule
{
    protected $params;

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach ($value as $item) {
            if (empty($item['id']) || empty($item['quantity']) || empty($item['subtotal']) || empty($item['price'])) {
                Log::info('[Validation][SalesItemRule]: An error occurred while processing the request');
                Log::info($item);

                $fail('Invoice Items has a missing parameters');
            }

            if (!filter_var($item['quantity'], FILTER_VALIDATE_FLOAT)
                || !filter_var($item['subtotal'], FILTER_VALIDATE_FLOAT)
                || !filter_var($item['price'], FILTER_VALIDATE_FLOAT)
            ) {
                Log::info('[Validation][SalesItemRule]: An error occurred while processing the request');
                Log::info($item);

                $fail('Invoice Items has an invalid parameters');
            }

            $product = Product::select(['id'])
                ->where('id', $item['id'])
                ->first();

            if (empty($product)) {
                Log::info('[Validation][SalesItemRule]: An error occurred while processing the request');
                Log::info($item);

                $fail('Invoice Items has an invalid/missing product');
            }
        }
    }
}
