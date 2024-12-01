<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'total_price',
        'financial_status',
        'fulfillment_status',
        'currency',
        'order_number',
        'processed_at',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

}
