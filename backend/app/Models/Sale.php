<?php

namespace App\Models;

use App\Rules\SalesItemRule;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'sales';

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'invoice_number',
        'subtotal_amount',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'sale_date',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [

    ];

    public static function getFieldValidations($params): array
    {
        return [
            'subtotal_amount' => ['required'],
            'tax_amount' => ['required'],
            'discount_amount' => ['required'],
            'total_amount' => ['required'],
            'items' => ['required', 'array', new SalesItemRule()],
        ];
    }

    public static function getValidationMessages()
    {
        return [

        ];
    }
}
