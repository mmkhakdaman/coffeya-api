<?php

namespace Modules\OTP\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Customer\Entities\Customer;
use Modules\OTP\Http\Requests\SendOtpRequest;
use Modules\OTP\Http\Requests\VerifyOtpRequest;
use Modules\OTP\Services\OtpService;

class AuthController extends Controller
{
    /**
     * Send OTP to the user
     *
     * @param Request $request
     * @return void
     */
    public function sendOtp(SendOtpRequest $request)
    {
        $user = Customer::firstOrCreate([
            'phone' => $request->phone,
        ]);


        $phone = $request->phone;

        $otp_service = new OtpService($phone);

        try {
            $otp_service->verify();
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 403);
        }

        return response()->json([
            'message' => 'کد تایید به شماره موبایل شما ارسال شد',
        ]);
    }

    /**
     * Verify OTP
     *
     * @param Request $request
     * @return void
     */
    public function verifyOtp(VerifyOtpRequest $request)
    {

        $otp_service = new OtpService($request->phone);

        if (!$otp_service->isValidToken($request->otp)) {
            return response()->json([
                'message' => 'کد تایید معتبر نیست',
            ], 403);
        }

        $user = Customer::firstOrCreate([
            'phone' => $request->phone,
        ]);

        $token = auth()->guard('customer')->login($user);

        return response()->json([
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => now()->addMinutes(config('jwt.ttl'))->timestamp,
            ]
        ], 200);
    }
}
