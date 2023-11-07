<?php

namespace Modules\Tenant\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        "work_day",
        "start_time",
        "end_time",
    ];

    protected static function newFactory()
    {
        return \Modules\Tenant\Database\factories\WorkScheduleFactory::new();
    }
}
