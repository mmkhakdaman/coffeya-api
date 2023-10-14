<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'price',
        'description',
        'status',
        'is_delivery',
        'address_id',
        'table_id',
        'pending_at'
    ];

    protected static function newFactory()
    {
        return \Modules\Order\Database\factories\OrderFactory::new();
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

}
