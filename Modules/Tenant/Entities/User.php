<?php

namespace Modules\Tenant\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'password'
    ];


    protected $hidden = [
        'password',
    ];

    protected static function newFactory()
    {
        return \Modules\Tenant\Database\factories\UserFactory::new();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     * @override
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array<string, mixed>
     * @override
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
