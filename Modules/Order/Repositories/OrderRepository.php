<?php

namespace Modules\Order\Repositories;

use Modules\Customer\Entities\Customer;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatusEnum;

class OrderRepository
{

    private function query()
    {
        return Order::query();
    }

    public function storeOrder(
        $customer_id,
        $order_price,
        $description,
        $status,
        $table_id,
        $pending_at,
        $is_delivery,
        $address_id,
        $post_cost
    )
    {
        return $this->query()->create(
            [
                'customer_id' => $customer_id,
                'order_price' => $order_price,
                'description' => $description,
                'status' => $status,
                'table_id' => $table_id,
                'pending_at' => $pending_at,
                'is_delivery' => $is_delivery,
                'address_id' => $address_id,
                'post_cost' => $post_cost,
            ]
        );
    }

    public function storeOrderProducts(\Illuminate\Database\Eloquent\Model|Order $order, \Illuminate\Support\Collection $orderProducts)
    {
        return $order->items()->createMany(
            $orderProducts->map(
                function ($product) {
                    return [
                        'customer_id' => auth()->id(),
                        'product_id' => $product['product_id'],
                        'quantity' => $product['quantity'],
                        'price' => $product['price'],
                        'total' => $product['price'] * $product['quantity']
                    ];
                }
            )->toArray()
        );
    }
}
