<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Http\Requests\UpdateOrderRequest;
use Modules\Order\Repositories\OrderRepository;
use Modules\Order\Services\OrderService;
use Modules\Order\Transformers\OrderResource;
use Modules\Payment\Services\PaymentService;

class OrderController extends Controller
{
    private function service(): OrderService
    {
        return resolve(OrderService::class);
    }

    private function repository(): OrderRepository
    {
        return resolve(OrderRepository::class);
    }

    private function paymentService(): PaymentService
    {
        return resolve(PaymentService::class);
    }

    /**
     * Display a listing of the resource.
     * @return array
     * @throws \Exception
     */
    public function checkOut(OrderRequest $request): array
    {
        $order = $this->service()->storeOrder($request);

        $transaction = $this->paymentService()->generate(
            $order->total_price,
            $order,
            $request->user(),
            []
        );

        return [
            'order_id' => $order->id,
            'redirect_url' => $transaction->getUrl(),
        ];
    }

    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'status' => 'nullable|in:' . implode(',', get_value_enums(OrderStatusEnum::cases())),
            'per_page' => 'nullable|integer',
            'page' => 'nullable|integer',
        ]);

        return OrderResource::collection($this->repository()->ordersWithPagination(
            $request->get('status', OrderStatusEnum::PENDING),
            $request->get('per_page', 10),
            $request->get('page', 1),
        ));
    }

    /**
     * Display a listing of the resource.
     * @param Order $order
     * @return OrderResource
     */
    public function show(Order $order): OrderResource
    {
        $order->load(['items.product', 'customer', 'address', 'table']);
        return OrderResource::make($order);
    }


    /**
     * Display a listing of the resource.
     * @param Order $order
     * @return OrderResource
     */
    public function update(UpdateOrderRequest $request, Order $order): OrderResource
    {
        $this->service()->updateOrder($request->validated(), $order);

        return OrderResource::make($this->repository()->orderById($order->id));
    }


    public function notCompletedOrders(): AnonymousResourceCollection
    {
        return OrderResource::collection($this->repository()->customerNotCompletedOrders(auth()->id()));
    }

    public function completedOrders(): AnonymousResourceCollection
    {
        return OrderResource::collection($this->repository()->customerCompletedOrders(auth()->id()));
    }
}
