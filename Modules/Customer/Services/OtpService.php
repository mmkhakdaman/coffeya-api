<?php

namespace Modules\Customer\Services;

use Illuminate\Notifications\Notifiable;
use Modules\Customer\Jobs\OTPSms;
use Modules\Customer\Repositories\Auth\OtpRepository;

class OtpService
{
    use Notifiable;

    private $mobile = null;

    private $otpRepository = null;

    public function __construct($mobile)
    {
        $this->mobile = $mobile;
        $this->otpRepository = new OtpRepository();
    }

    public function verify()
    {
        if (!$this->isBlocked()) {
            $otp = $this->otpRepository->generate($this->mobile, config('otp.length'));
            if (env('APP_ENV') != 'testing') {
                OTPSms::dispatch($this->mobile, $otp->token);
            }
        } else {
            throw new \Exception("شما مجاز نیستید.");
        }
    }

    public function recover()
    {
        if (!$this->isBlocked()) {
            $otp = $this->otpRepository->generate($this->mobile, config('otp.length'));
        } else {
            throw new \Exception("شما مجاز نیستید.");
        }
    }


    public function isValidToken(string $token): bool
    {
        return (bool)$this->otpRepository->checkToken($this->mobile, $token);
    }

    public function useOtp(string $token)
    {
        return $this->otpRepository->delete($this->mobile, $token);
    }

    private function isBlocked(): bool
    {
        $trailsCount = $this->otpRepository->getTrialsCount($this->mobile);

        return $trailsCount >= config('otp.allowedCount', 3);
    }
}
