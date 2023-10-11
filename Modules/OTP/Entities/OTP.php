<?php

namespace Modules\OTP\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OTP extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'mobile',
        'expired_on',
    ];

    protected $table = "otps";

    protected static function newFactory()
    {
        return \Modules\OTP\Database\factories\OTPFactory::new();
    }

    public function otps(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(OTP::class, 'otpable');
    }
}
