<?php

namespace Modules\Payment\Gateways;

use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class ZarinpallGateway
{
    public function getInvoiceIdFromRequest($request)
    {
        return $request->Authority;
    }

    public function getName()
    {
        return 'zarinpall';
    }

    public function request(int $amount, $id)
    {

        // Create new invoice.
        $invoice = new Invoice();

        // Set invoice amount.
        $invoice->amount($amount);

        // Set invoice description.
        $invoice->detail('Invoice description');

        // Set invoice transaction id.
        $invoice->transactionId($id);

        Payment::callbackUrl(
            route('payment.callback', ['gateway' => 'zarinpall'])
        )->purchase($invoice, function ($driver, $transactionId) {
            // Store transactionId in database as we need it to verify payment in the future.
            // $this->storeTransactionId($transactionId);
        });


        return $invoice;
    }


    public function redirectUrl($payment)
    {
        $pay = Payment::transactionId($payment->invoice_id)->pay();



        return $pay->getUrl();
    }


    public function verify($payment)
    {
        $result = Payment::amount($payment->amount)
            ->transactionId($payment->invoice_id)
            ->verify();

        return $result;
    }
}
