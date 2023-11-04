<?php

namespace Modules\Payment\Services;

use Illuminate\Http\RedirectResponse;
use Modules\Customer\Entities\Customer;
use Modules\Payment\Entities\Payment;
use Modules\Payment\Enums\PaymentStatusEnum;
use Modules\Payment\Gateways\ZarinpallGateway;

class PaymentService
{
    /**
     * Generate payments.
     *
     * @param string $amount
     * @param object $paymentable
     * @param User $buyer
     * @param array $discounts
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function generate(
        int      $amount,
        object   $paymentable,
        Customer $buyer,
        array    $discounts = []
    ) {
        if ($amount <= 0 || is_null($paymentable->id) || is_null($buyer->id)) {
            return false;
        }

        $gateway = new ZarinpallGateway();
        $invoice = $gateway->request($amount, $paymentable->id, function ($driver, $transactionId) use (
            $paymentable,
            $amount,
            $buyer,
            $gateway,
            $discounts
        ) {
            if (!is_null($paymentable->percent)) {
                $seller_p = $paymentable->percent;
                $seller_share = ($amount / 100 * $seller_p);
                $site_share = $amount - $seller_share;
            } else {
                $seller_p = $seller_share = 0;
                $site_share = $amount;
            }


            $this->store([
                'buyer_id' => $buyer->id,
                'paymentable_id' => $paymentable->id,
                'paymentable_type' => get_class($paymentable),
                'amount' => $amount,
                'invoice_id' => $transactionId,
                'gateway' => $gateway->getName(),
                'status' => PaymentStatusEnum::STATUS_PENDING->value,
                'seller_p' => $seller_p,
                'seller_share' => $seller_share,
                'site_share' => $site_share,
            ], $discounts);
        });

        if (is_null($invoice)) {
            return back();
        }


        return $invoice->pay();
    }

    /**
     * Change status by id.
     *
     * @param int $id
     * @param string $status
     *
     * @return int
     */
    public function changeStatus(int $id, string $status)
    {
        return Payment::query()
            ->where('id', $id)
            ->update(['status' => $status]);
    }

    // Private methods

    /**
     * Store payments.
     *
     * @param array $data
     * @param array $discounts
     *
     * @return Payment
     */
    private function store(array $data, array $discounts = [])
    {
        $payments = Payment::query()->create([
            'buyer_id' => $data['buyer_id'],
            'paymentable_id' => $data['paymentable_id'],
            'paymentable_type' => $data['paymentable_type'],
            'amount' => $data['amount'],
            'invoice_id' => $data['invoice_id'],
            'gateway' => $data['gateway'],
            'status' => $data['status'],
            'seller_p' => $data['seller_p'],
            'seller_share' => $data['seller_share'],
            'site_share' => $data['site_share'],
        ]);
        //        $this->syncDiscountToPayments($payments, $discounts);

        return $payments;
    }

    /**
     * Sync discount to payments.
     *
     * @param Payment $payments
     * @param array $discounts
     *
     * @return Payment
     */
    private function syncDiscountToPayments(Payment $payments, array $discounts = [])
    {
        foreach ($discounts as $discount) {
            $discountIds[] = $discount->id;
        }
        if (isset($discountIds)) {
            $payments->discounts()->sync($discountIds);
        }

        return $payments;
    }
}
