<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'sku',
        'product_category_id',
        'unit',
        'selling_price',
        'image_name',
        'original_image_name',
        'image_extension',
        'image_mime_type',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [

    ];

    public static function getFieldValidations($params): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'sku' => ['nullable', 'string'],
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'unit' => ['required', 'string'],
            'selling_price' => ['required', 'decimal:2,4'],
            'image' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10048'],
        ];
    }

    public static function getValidationMessages()
    {
        return [

        ];
    }
}
