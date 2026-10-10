<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_phone',
        'customer_address',
        'product_id',
        'product_name',
        'product_price',
        'quantity',
        'total_amount',
        'status',
        'platform',
        'conversation_id',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
