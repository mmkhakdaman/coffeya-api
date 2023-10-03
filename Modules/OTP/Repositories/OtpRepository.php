<?php

namespace Modules\OTP\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Modules\OTP\Entities\OTP;

class OtpRepository
{

    /** @var OTP $otp */
    private $otp;

    public function generate(string $mobile, int $length): OTP
    {
        $min = str_pad(1, $length, 0);
        $max = str_pad(9, $length, 9);
        $oneTimeEntity = OTP::create([
            'token' => random_int($min, $max),
            'mobile' => $mobile,
            'expires_on' => Carbon::now()->addMinutes(Config::get('otp.expire'))
        ]);

        return $oneTimeEntity;
    }


    public function getTrialsCount(string $mobile): int
    {
        return OTP::where('mobile', $mobile)->where('created_at', '>=', Carbon::now()->subMinutes(Config::get('otp.blacklistTimeout')))->count();
    }


    public function removeExpiredTokens(): bool
    {
        OTP::where('expires_on', '<=', Carbon::now())->delete();

        return true;
    }

    public function delete(string $mobile, string $token): bool
    {
        return OTP::where('mobile', $mobile)->delete();
    }


    public function checkToken(string $mobile, int $token): bool
    {
        $this->otp = OTP::where('token', $token)->where(
            'mobile', $mobile
        )->where(
            'expires_on', '>=', date('Y-m-d H:i:s')
        )->first();
        return $this->otp ? true : false;
    }

    public function removeOtp(): bool
    {
        OTP::where('expires_on', '<=', Carbon::now())->delete();

        return true;
    }
}
