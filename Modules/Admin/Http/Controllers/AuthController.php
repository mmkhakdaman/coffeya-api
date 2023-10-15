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
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = auth('tenant_admin')->claims([
            'name' => $admin->name,
            'phone' => $admin->phone,
        ])->attempt($request->only('phone', 'password'));

        return response()->json([
            'access_token' => $token,
            'refresh_token' => auth('tenant_admin')->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth('tenant_admin')->factory()->getTTL() * 60
        ]);
    }

    public function refresh()
    {

        return response()->json([
            'access_token' => auth('tenant_admin')->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth('tenant_admin')->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        auth('tenant_admin')->logout();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
