<?php

namespace Modules\Customer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OTP extends Model
{
    use HasFactory;

    protected $table = 'otps';

    protected $fillable = [
        "mobile",
        "token",
        "expires_on",
    ];

    protected static function newFactory()
    {
        return \Modules\Customer\Database\factories\OTPFactory::new();
    }
}
