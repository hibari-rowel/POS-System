<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'user_id',
        'product_id',
        'quantity',
        'stock_date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [

    ];
}
