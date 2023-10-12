<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'quantity',
        'price',
        'total',
    ];

    protected static function newFactory()
    {
        return \Modules\Order\Database\factories\OrderItemFactory::new();
    }
}
