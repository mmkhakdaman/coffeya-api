<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    private $mobile;
    private $templateName;
    private $parameters;

    /**
     * Create a new job instance.
     *
     * @param string $mobile
     * @param array|string $token
     * @param string $templateName
     */
    public function __construct(string $mobile, $templateName, array $parameters)
    {
        $this->queue = "sms";
        $this->mobile = $mobile;
        $this->templateName = $templateName;
        $this->parameters = $parameters;
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

        $send = Smsir::send();

        $parameters = [];

        foreach ($this->parameters as $key => $value) {
            array_push($parameters, new \Cryptommer\Smsir\Objects\Parameters($key, $value));
        }


        $send->Verify(
            $this->mobile,
            $this->templateName,
            $parameters
        );
    }
}
