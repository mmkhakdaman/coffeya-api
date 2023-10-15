<?php

namespace Modules\Tenant\Entities;

use Modules\Admin\Entities\Admin;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

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
}
