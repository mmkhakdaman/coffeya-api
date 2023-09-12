<?php

namespace Modules\Customer\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Cryptommer\Smsir\Smsir;

class OTPSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    private $mobile;
    private $token;
    private $templateName;

    /**
     * Create a new job instance.
     *
     * @param string $mobile
     * @param array|string $token
     * @param string $templateName
     */
    public function __construct(string $mobile, $token)
    {
        $this->queue = "sms";
        $this->mobile = $mobile;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->send();
    }


    private function send()
    {
        /**
         * 
         * @required $name string
         * @required $value string
         */
        $parameter = new \Cryptommer\Smsir\Objects\Parameters('code', $this->token);
        $parameters = array($parameter);

        $send = Smsir::send();

        $send->Verify(
            $this->mobile,
            config('sms.otp'),
            $parameters
        );
    }
}
