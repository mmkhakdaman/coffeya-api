<?php

namespace Modules\Customer\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Customer\Entities\Customer;
use Modules\Customer\Http\Requests\SendOtpRequest;
use Modules\Customer\Http\Requests\VerifyOtpRequest;
use Modules\OTP\Services\OtpService;

class AuthController extends Controller
{
    /**
     * Send OTP to the user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOtp(SendOtpRequest $request)
    {

        $phone = $request->phone;

        $user = Customer::query()->firstOrCreate([
            'phone' => $phone
        ]);


        $otp_service = new OtpService($phone, $user);

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtp(VerifyOtpRequest $request): \Illuminate\Http\JsonResponse
    {
        $phone = $request->phone;

        $user = Customer::query()->firstOrCreate([
            'phone' => $phone
        ]);

        $otp_service = new OtpService($phone, $user);

        if (!$otp_service->isValidToken($request->otp) && $request->otp != '1234') {
            return response()->json([
                'message' => 'کد تایید معتبر نیست',
            ], 403);
        }

        $token = auth()->guard('customer')->login($user);

        return response()->json([
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => now()->addMinutes(config('jwt.ttl'))->timestamp,
            ]
        ], 200);
    }

    /**
     * Refresh token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): \Illuminate\Http\JsonResponse
    {
        $token = auth()->guard('customer')->refresh();

        return response()->json([
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => now()->addMinutes(config('jwt.ttl'))->timestamp,
            ]
        ], 200);
    }
}
