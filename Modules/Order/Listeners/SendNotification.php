<?php

namespace Modules\Order\Listeners;

use App\Jobs\SmsJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Order\Enums\OrderStatusEnum;
use Modules\Order\Repositories\OrderRepository;
use Modules\Payment\Events\PaymentWasSuccessful;

class SendNotification
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
        SmsJob::dispatch(
            config('otp.owner_phone_number'),
            config('sms.otp'),
            [
                'ORDERS' => $event->payment->paymentable_id,
            ]
        );
    }
}
