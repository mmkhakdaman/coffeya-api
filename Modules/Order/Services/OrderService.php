<?php

namespace Modules\Order\Services;

use Modules\Address\Entities\Address;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Http\Requests\UpdateOrderRequest;
use Modules\Order\Repositories\OrderRepository;
use Modules\Product\Repositories\ProductRepository;

class OrderService
{
    private function repo(): OrderRepository
    {
        return resolve(OrderRepository::class);
    }


    public function storeOrder(OrderRequest $orderRequest)
    {
        $products = resolve(ProductRepository::class)
            ->findProductsByIds(
                collect($orderRequest->cart)->pluck('product_id')->toArray()
            );

        $cart = collect($orderRequest->cart)->map(
            function ($item) use ($products) {
                $item['price'] = $products->find($item['product_id'])->price;
                return $item;
            }
        );

        $order_price = $this->calculatePrice($cart);
        $post_cost = $orderRequest->is_delivery ? $this->postCost() : 0;

        $order = $this
            ->repo()
            ->storeOrder(
                [
                    'customer_id' => auth()->id(),
                    'order_price' => $order_price,
                    'description' => $orderRequest->description,
                    'status' => $orderRequest->is_pay_in_restaurant ? OrderStatusEnum::PENDING : OrderStatusEnum::NOT_PAID,
                    'table_id' => $orderRequest->table_id,
                    'pending_at' => now(),
                    'is_delivery' => $orderRequest->is_delivery,
                    'address_id' => $orderRequest->address_id,
                    'post_cost' => $post_cost,
                    'total_price' => $order_price + $post_cost,
                    'is_pay_in_restaurant' => $orderRequest->is_pay_in_restaurant ?? false,
                    'is_packaging' => $orderRequest->is_packaging ?? false,
                ]
            );
        $this->repo()->storeOrderProducts($order, $cart);

        return $order;
    }

    private function calculatePrice($products)
    {
        return $products->sum(
            function ($product) {
                return $product['price'] * $product['quantity'];
            }
        );
    }

    private function postCost(): int
    {
        return tenant()->cost_of_post ?? 10000;
    }

    public function updateOrder($data, \Modules\Order\Entities\Order $order)
    {
        if ($order->status == OrderStatusEnum::PENDING->value && $data['status'] == OrderStatusEnum::CONFIRMED->value) {
            $data['confirmed_at'] = now();
        }

        $order->update($data);

        return $order;
    }
}
