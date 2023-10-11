<?php

namespace Modules\OTP\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Modules\Customer\Entities\Customer;
use Modules\OTP\Entities\OTP;

class OtpRepository
{

    public function generate($object, string $mobile, int $length)
    {
        $min = str_pad(1, $length, 0);
        $max = str_pad(9, $length, 9);
        return $object->otps()->create([
            'token' => random_int($min, $max),
            'mobile' => $mobile,
            'expires_on' => Carbon::now()->addMinutes(Config::get('otp.expire'))
        ]);
    }


    public function getTrialsCount($obj, string $mobile): int
    {
        return $obj->otps()->where('mobile', $mobile)->where('created_at', '>=', Carbon::now()->subMinutes(Config::get('otp.blacklistTimeout')))->count();
    }


    public function checkToken($obj, string $mobile, int $token): bool
    {
        return $obj->otps()->where('token', $token)->where(
            'mobile', $mobile
        )->where(
            'expires_on', '>=', date('Y-m-d H:i:s')
        )->exists();
    }
}
