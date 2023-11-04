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

    public function storeOrder($data): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
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

    public function pendingOrders(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()->where('status', OrderStatusEnum::PENDING)->get();
    }

    public function ordersWithPagination($status = null, $perPage = 10, $page = null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query()
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->with(['customer', 'items.product', 'address', 'table'])
            ->latest()
            ->paginate($perPage, ['*'], 'page', $page);
    }


    public function changeStatus(\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder $order, OrderStatusEnum $status)
    {
        return $order->update(['status' => $status->value]);
    }
}
