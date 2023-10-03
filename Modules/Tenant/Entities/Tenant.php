<?php

namespace Modules\Tenant\Entities;

use Illuminate\Auth\Authenticatable;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;

use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
// AuthenticatableBaseTenant


class Tenant extends BaseTenant implements
    AuthenticatableContract,
    AuthorizableContract,
    TenantWithDatabase
{
    use Authenticatable, Authorizable, HasDatabase, HasDomains, HasFactory;

    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Tenant\Database\factories\TenantFactory::new();
    }
}
