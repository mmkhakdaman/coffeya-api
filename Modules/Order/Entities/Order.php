<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Customer\Entities\Address;
use Modules\Customer\Entities\Customer;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Table\Entities\Table;

class Order extends Model
{
    use HasFactory;


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
        'is_pay_in_restaurant'
    ];

    protected $casts = [
        'is_delivery' => 'boolean',
        'is_packaging' => 'boolean',
        'pending_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    protected static function newFactory()
    {
        return \Modules\Order\Database\factories\OrderFactory::new();
    }

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function table(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function address(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

}
