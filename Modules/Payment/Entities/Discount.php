<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Admin\Entities\Admin;

class Discount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admin_id',
        'code',
        'usage_limitation',
        'user_limitation',
        'percent',
        'price',
        'expire_at',
        'status',
    ];

    protected static function newFactory()
    {
        return \Modules\Payment\Database\factories\DiscountFactory::new();
    }

    /**
     * Get the user that owns the Discount
     *
     * @return BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
