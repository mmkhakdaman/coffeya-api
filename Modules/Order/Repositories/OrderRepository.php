<?php

namespace Modules\Order\Repositories;

use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Models\Order;

class OrderRepository
{

    private function query()
    {
        return Order::query();
    }

    public function storeOrder($data)
    {
        return $this->query()->create(
            [
                'customer_id' => auth()->id(),
                'price' => $data['price'],
                'description' => $data['description'],
                'status' => $data['status'],
                'is_delivery' => $data['is_delivery'],
                'address' => $data['address'],
                'pending_at' => $data['pending_at']
            ]
        );
    }

    public function storeOrderProducts(\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder $order, \Illuminate\Support\Collection $orderProducts)
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

    public function getOrdersByCustomerId(int|string|null $id)
    {
        return $this->query()
            ->where('customer_id', $id)
            ->with('items.product')
            ->orderBy('pending_at', 'desc')
            ->get();
    }

    public function getOrdersHistoryByCustomerId(int|string|null $id)
    {
        return $this->query()
            ->where('customer_id', $id)
            ->whereNotIn('status', [OrderStatusEnum::PENDING])
            ->with('items.product')
            ->orderBy('pending_at', 'desc')
            ->get();
    }

    public function changeStatus(\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder $order, OrderStatusEnum $status)
    {
        return $order->update(['status' => $status->value]);
    }
    public function getPendingOrdersByCustomerId(int|string|null $id)
    {
        return $this->query()
            ->where('customer_id', $id)
            ->where('status', OrderStatusEnum::PENDING)
            ->with('items.product')
            ->orderBy('pending_at', 'desc')
            ->get();
    }
}
