<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Services\OrderService;
use Modules\Payment\Services\PaymentService;

class OrderController extends Controller
{
    private function service(): OrderService
    {
        return resolve(OrderService::class);
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
}
