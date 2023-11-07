<?php

namespace Modules\Tenant\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection as CollectionAlias;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Modules\Tenant\Entities\WorkSchedule;
use Modules\Tenant\Transformers\WorkScheduleResource;

class WorkScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return WorkScheduleResource::collection(WorkSchedule::all());
    }

    public function isOpen()
    {
        $workSchedule = WorkSchedule::where('work_day', now()->dayOfWeek)->first();

        if (!$workSchedule) {
            return response()->json(['data' => false]);
        }


        if (now()->between($workSchedule->start_time, $workSchedule->end_time)) {
            return response()->json(['data' => true]);
        }

        return response()->json(['data' => false]);
    }
}
