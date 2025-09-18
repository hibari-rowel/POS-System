<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSales extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'product_sales';

    protected $fillable = [
        'name',
        'description',
        'sale_id',
        'product_id',
        'quantity',
        'price',
        'discount',
        'subtotal',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [

    ];
}
