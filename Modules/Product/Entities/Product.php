<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'category_id',
        'order',
        'price',
        'image',
        'is_active',
        'in_stock',
    ];

    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\ProductFactory::new();
    }
}
