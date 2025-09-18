<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'stocks';

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'product_id',
        'quantity_added',
        'stock_date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [

    ];
}
