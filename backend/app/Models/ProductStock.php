<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ProductStock extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'product_stocks';

    protected $fillable = [
        'name',
        'description',
        'supplier_name',
        'product_id',
        'price',
        'quantity',
        'unit',
        'subtotal',
        'stock_date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [

    ];

    public static function getFieldValidations($params): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'supplier_name' => ['required', 'string'],
            'stock_date' => ['required', 'date'],
            'quantity' => ['required', 'decimal:2,4'],
            'unit' => ['required', 'string'],
            'price' => ['required', 'decimal:2,4'],
            'subtotal' => ['required', 'decimal:2,4'],
            'description' => ['nullable', 'string'],
        ];
    }

    public static function getValidationMessages()
    {
        return [

        ];
    }

    // ##################### Accessors & Mutators ######################
    protected function product(): Attribute
    {
        return new Attribute(
            get: function () {
                return DB::table('products')
                    ->select(['id', 'name'])
                    ->whereNull('deleted_at')
                    ->where('id', $this->getOriginal('product_id'))
                    ->first();
            },
        );
    }
}
