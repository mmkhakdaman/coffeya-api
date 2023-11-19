<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payment\Enums\PaymentStatusEnum;
use Modules\Payment\Events\PaymentWasSuccessful;
use Modules\Payment\Gateways\ZarinpallGateway;
use Modules\Payment\Repositories\PaymentRepo;
use Modules\Payment\Services\PaymentService;

class PaymentController extends Controller
{
    private function paymentRepo(): PaymentRepo
    {
        return resolve(PaymentRepo::class);
    }


    /**
     * Callback from gateway.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function callback(Request $request)
    {
        $gateway = new ZarinpallGateway();
        $payment = $this->paymentRepo()->findByInvoiceId($gateway->getInvoiceIdFromRequest($request));
        try {
            $result = $gateway->verify($payment);
        } catch (\Exception $e) {
            $this->changeStatus($payment, PaymentStatusEnum::STATUS_FAIL->value);

            return redirect()->route('payment.fail')->with('message', $e->getMessage());
        }

        if (is_array($result)) {
            $this->changeStatus($payment, PaymentStatusEnum::STATUS_FAIL->value);

            return redirect("//" . tenant()->domains->first()->front_domain . "/fail")->with('message', $result['message']);
        }

        event(new PaymentWasSuccessful($payment));
        $this->changeStatus($payment, PaymentStatusEnum::STATUS_SUCCESS->value);
        return redirect("//" . tenant()->domains->first()->front_domain . "/success");
    }

    /**
     * Change payment status.
     *
     * @param        $payment
     * @param string $status
     *
     * @return void
     */
    private function changeStatus($payment, string $status): void
    {
        resolve(PaymentService::class)->changeStatus($payment->id, $status);
    }


    public function fail()
    {
        // return Inertia::render('Payment/Fail', [
        //     'message' => session('message')
        // ]);
    }
}
