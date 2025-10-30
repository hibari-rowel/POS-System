<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSale extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'product_sales';

    protected $fillable = [
        'name',
        'sale_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [

    ];

    protected $hidden = [
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];
}
