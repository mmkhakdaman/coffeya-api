<?php

namespace Modules\Category\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'order',
        'is_active',
    ];

    protected static function newFactory(): \Modules\Category\Database\factories\CategoryFactory
    {
        return \Modules\Category\Database\factories\CategoryFactory::new();
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Product\Entities\Product::class);
    }
}
