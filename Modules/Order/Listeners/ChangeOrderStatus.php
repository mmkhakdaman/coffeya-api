<?php

namespace Modules\Order\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Repositories\OrderRepository;
use Modules\Payment\Events\PaymentWasSuccessful;

class ChangeOrderStatus
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentWasSuccessful $event): void
    {
        $payment = $event->payment;
        $order = $payment->paymentable;
        $orderRepo = resolve(OrderRepository::class);
        $orderRepo->changeStatus($order, OrderStatusEnum::PENDING);
    }
}
