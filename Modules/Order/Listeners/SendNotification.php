<?php

namespace Modules\Order\Listeners;

use App\Jobs\SMSJob;
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
        SMSJob::dispatch(
            config('otp.owner_phone_number'),
            config('sms.otp'),
            [
                'ORDERS' => $event->payment->paymentable_id,
            ]
        );
    }
}
