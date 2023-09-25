<?php

namespace Modules\Order\Services;

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
            function ($product) use ($products) {
                $product['price'] = $products->find($product['product_id'])->price;
                return $product;
            }
        );

        $price = $this->calculatePrice($cart);

        $order = $this
            ->repo()
            ->storeOrder(
                [
                    'price' => $price,
                    'description' => $orderRequest->description,
                    'status' => OrderStatusEnum::NOT_PAID,
                    'pending_at' => now()
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

    public function listOrders()
    {
        return $this->repo()->getPendingOrdersByCustomerId(auth()->id());
    }

    public function historyOrders()
    {
        return $this->repo()->getOrdersHistoryByCustomerId(auth()->id());
    }
}
