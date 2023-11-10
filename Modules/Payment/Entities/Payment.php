<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Customer\Entities\Customer;

class Payment extends Model
{

    use HasFactory;

    /**
     * Fillable columns.
     *
     * @var string[]
     */
    protected $fillable = [
        'buyer_id',
        'seller_id',
        'paymentable_id',
        'paymentable_type',
        'amount',
        'invoice_id',
        'gateway',
        'seller_share',
        'site_share',
        'seller_p',
        'status',
    ];

    protected static function newFactory()
    {
//        return \Modules\Payment\Database\factories\PaymentFactory::new();
    }

    // Relations

    /**
     * Relation polymorphic.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function paymentable()
    {
        return $this->morphTo();
    }

    /**
     * Relations to Discount model, relation is one to many.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function discounts()
    {
//        return $this->belongsToMany(Discount::class, 'discount_payment')->withTimestamps();
    }

    /**
     * Relations to User model, relation is one to many.
     *
     * @return BelongsTo
     */
    public function buyer()
    {
        return $this->belongsTo(Customer::class, 'buyer_id');
    }

    /**
     * Relations to User model, relation is one to many.
     *
     * @return BelongsTo
     */
    public function seller()
    {
//        return $this->belongsTo(User::class, 'seller_id');
    }
}

