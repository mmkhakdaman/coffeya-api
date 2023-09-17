<?php

namespace Modules\Table\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
        'title',
        'token'
    ];
    
    protected static function newFactory()
    {
        return \Modules\Table\Database\factories\TableFactory::new();
    }
}
