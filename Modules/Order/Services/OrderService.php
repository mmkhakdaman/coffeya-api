<?php

namespace Modules\Order\Services;

use Modules\Address\Entities\Address;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Http\Requests\OrderRequest;
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
                auth()->id(),
                $order_price,
                $orderRequest->description,
                OrderStatusEnum::PENDING,
                $orderRequest->table_id,
                now(),
                $orderRequest->is_delivery,
                $orderRequest->address_id,
                $post_cost
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
        return 10000;
    }
}
