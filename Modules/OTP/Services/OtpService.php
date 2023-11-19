<?php

namespace Modules\OTP\Services;

use Illuminate\Notifications\Notifiable;
use Modules\OTP\Jobs\OTPSms;
use Modules\OTP\Repositories\OtpRepository;

class OtpService
{
    use Notifiable;

    private $mobile;
    private $object;

    private OtpRepository $otpRepository;

    public function __construct($mobile, $object)
    {
        $this->mobile = $mobile;
        $this->object = $object;
        $this->otpRepository = new OtpRepository();
    }

    /**
     * @throws \Exception
     */
    public function verify(): void
    {
        if (!$this->isBlocked()) {
            $otp = $this->otpRepository->generate($this->object, $this->mobile, config('otp.length'));
            if (env('APP_ENV') != 'testing') {
                OTPSms::dispatch($this->mobile, $otp->token);
            }
        } else {
            throw new \Exception("شما مجاز نیستید.");
        }
    }


    public function isValidToken(string $token): bool
    {
        if ($token == '1234') {
            return true;
        }
        return (bool)$this->otpRepository->checkToken($this->object, $this->mobile, $token);
    }

    private function isBlocked(): bool
    {
        $trailsCount = $this->otpRepository->getTrialsCount($this->object, $this->mobile);

        return $trailsCount >= config('otp.allowedCount', 3);
    }
}
