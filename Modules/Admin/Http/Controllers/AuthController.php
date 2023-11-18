<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Admin;
use Modules\Customer\Http\Requests\SendOtpRequest;
use Modules\Customer\Http\Requests\VerifyOtpRequest;
use Modules\OTP\Services\OtpService;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:admins,phone',
            'password' => 'required'
        ]);

        $admin = Admin::where('phone', $request->phone)->first();

        if (!auth('tenant_admin')->attempt($request->only('phone', 'password'))) {
            return response()->json([
                'errors' => [
                    'phone' => ['نام کاربری یا رمز عبور اشتباه است']
                ],
                'message' => 'نام کاربری یا رمز عبور اشتباه است'
            ], 422);
        }

        $token = auth('tenant_admin')->claims([
            'name' => $admin->name,
            'phone' => $admin->phone,
        ])->attempt($request->only('phone', 'password'));

        return $this->respondWithToken($token);
    }

    public function refresh()
    {
        try {
            $token = auth('tenant_admin')->refresh();
            return $this->respondWithToken();
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    public function logout()
    {
        try {
            auth('tenant_admin')->logout();

            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }


    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => now()->addMinutes(config('jwt.ttl'))->timestamp
        ]);
    }
}
