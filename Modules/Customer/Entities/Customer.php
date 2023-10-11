<?php

namespace Modules\Customer\Entities;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\OTP\Entities\OTP;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Customer extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;


    /**
     * Guard for the model
     *
     * @var string
     */
    protected $guard = 'customer';

    protected $fillable = [
        'phone',
        'name'
    ];

    protected static function newFactory()
    {
        return \Modules\Customer\Database\factories\CustomerFactory::new();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return MorphMany
     */
    public function otps(): MorphMany
    {
        return $this->morphMany(OTP::class, 'otpable');
    }
}
