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

    public function storeOrder($data)
    {
        return $this->query()->create($data);
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
