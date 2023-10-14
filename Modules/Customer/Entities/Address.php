<?php

namespace Modules\Customer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class Address extends Model
{
    use HasFactory, CentralConnection;

    protected $fillable = [
        'name',
        'address',
        'customer_id',
    ];

    protected static function newFactory()
    {
        return \Modules\Customer\Database\factories\AddressFactory::new();
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
