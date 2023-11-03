<?php

namespace Modules\OTP\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Stancl\Tenancy\Contracts\SyncMaster;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class OTP extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'mobile',
        'expires_on',
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

    public function tenants(): BelongsToMany
    {
        // TODO: Implement tenants() method.
    }

    public function getTenantModelName(): string
    {
        // TODO: Implement getTenantModelName() method.
    }

    public function getGlobalIdentifierKeyName(): string
    {
        // TODO: Implement getGlobalIdentifierKeyName() method.
    }

    public function getGlobalIdentifierKey()
    {
        // TODO: Implement getGlobalIdentifierKey() method.
    }

    public function getCentralModelName(): string
    {
        // TODO: Implement getCentralModelName() method.
    }

    public function getSyncedAttributeNames(): array
    {
        // TODO: Implement getSyncedAttributeNames() method.
    }

    public function triggerSyncEvent()
    {
        // TODO: Implement triggerSyncEvent() method.
    }
}
