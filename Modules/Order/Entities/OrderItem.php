<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Entities\Product;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

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


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
