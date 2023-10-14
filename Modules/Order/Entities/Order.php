<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

//$table->string('customer_id');
//$table->foreignIdFor(\Modules\Table\Entities\Table::class)->nullable();
//
//$table->boolean('is_delivery')->default(false);
//$table->foreignIdFor(\Modules\Customer\Entities\Address::class)->nullable();
//
//$table->boolean('is_packaging')->default(false);
//
//$table->text('description')->nullable();
//
//$table->bigInteger('post_cost')->default(0);
//$table->bigInteger('order_price')->default(0);
//$table->bigInteger('total_price')->default(0);
//
//$table->timestamp('pending_at')->nullable();
//$table->timestamp('confirmed_at')->nullable();
//$table->timestamp('completed_at')->nullable();
//$table->timestamp('cancelled_at')->nullable();
//
//$table->enum('status', get_value_enums(OrderStatusEnum::cases()))->default(OrderStatusEnum::NOT_PAID->value);


    protected $fillable = [
        'customer_id',
        'table_id',
        'is_delivery',
        'address_id',
        'is_packaging',
        'description',
        'status',
        'pending_at',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
        'post_cost',
        'order_price',
        'total_price',
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
