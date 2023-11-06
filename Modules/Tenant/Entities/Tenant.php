<?php

namespace Modules\Tenant\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Admin\Entities\Admin;
use Modules\Tenant\Database\factories\TenantFactory;
use Stancl\Tenancy\Contracts\Domain;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, HasFactory;


    protected static function newFactory()
    {
        return TenantFactory::new();
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'user_id',
            'name',
            'english_name',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function domain(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Domain::class);
    }
}
